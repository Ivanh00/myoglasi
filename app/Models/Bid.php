<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
        'is_winning',
        'is_auto_bid',
        'max_bid',
        'ip_address'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'max_bid' => 'decimal:2',
        'is_winning' => 'boolean',
        'is_auto_bid' => 'boolean'
    ];

    // Relationships
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bid validation
    public function isValidBid()
    {
        return $this->amount >= $this->auction->minimum_bid &&
               $this->amount > $this->auction->current_price;
    }

    // Place a new bid
    public static function placeBid($auctionId, $userId, $amount, $isAutoBid = false, $maxBid = null)
    {
        $auction = Auction::find($auctionId);
        
        if (!$auction->canBid()) {
            throw new \Exception('Aukcija nije aktivna.');
        }

        if ($amount < $auction->minimum_bid) {
            throw new \Exception('Ponuda mora biti najmanje ' . number_format($auction->minimum_bid, 0, ',', '.') . ' RSD.');
        }

        // Check auto-bid priority for manual bids
        if (!$isAutoBid) {
            $existingAutoBids = self::where('auction_id', $auctionId)
                ->where('is_auto_bid', true)
                ->whereNotNull('max_bid')
                ->get();
                
            $highestAutoBidMax = $existingAutoBids->max('max_bid');
            
            if ($highestAutoBidMax && $amount <= $highestAutoBidMax) {
                // Manual bid conflicts with auto-bid - auto-bid wins at their max or manual bid amount
                $autoBidWinner = $existingAutoBids->where('max_bid', $highestAutoBidMax)->first();
                $autoWinAmount = min($amount, $autoBidWinner->max_bid);
                
                \DB::transaction(function () use ($auction, $autoBidWinner, $autoWinAmount, $userId, $amount) {
                    // First, create the manual bid (shows in history)
                    self::create([
                        'auction_id' => $auction->id,
                        'user_id' => $userId,
                        'amount' => $amount,
                        'is_winning' => false, // Won't win due to auto-bid
                        'is_auto_bid' => false,
                        'max_bid' => null,
                        'ip_address' => request()->ip()
                    ]);
                    
                    // Then, auto-bid wins
                    $auction->bids()->update(['is_winning' => false]);
                    
                    self::create([
                        'auction_id' => $auction->id,
                        'user_id' => $autoBidWinner->user_id,
                        'amount' => $autoWinAmount,
                        'is_winning' => true,
                        'is_auto_bid' => true,
                        'max_bid' => $autoBidWinner->max_bid,
                        'ip_address' => $autoBidWinner->ip_address
                    ]);

                    // Update auction  
                    $auction->update([
                        'current_price' => $autoWinAmount,
                        'total_bids' => $auction->total_bids + 2 // Both bids count
                    ]);
                });
                
                return true;
            }
        }

        \DB::transaction(function () use ($auction, $userId, $amount, $isAutoBid, $maxBid) {
            // Mark previous winning bid as not winning
            $auction->bids()->where('is_winning', true)->update(['is_winning' => false]);

            // Create new bid
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $userId,
                'amount' => $amount,
                'is_winning' => true,
                'is_auto_bid' => $isAutoBid,
                'max_bid' => $maxBid,
                'ip_address' => request()->ip()
            ]);

            // Update auction
            $auction->update([
                'current_price' => $amount,
                'total_bids' => $auction->total_bids + 1
            ]);

            // Check for time extension
            if ($auction->needsExtension()) {
                $auction->extendAuction();
            }
        });
        
        // Note: Auto-bid processing is now handled in Livewire component

        return true;
    }

    // Process auto-bids when a new manual bid is placed
    public static function processAutoBids($auction, $excludeUserId = null)
    {
        // Get fresh auction data
        $auction = $auction->fresh();
        
        // Get active auto-bids for this auction (excluding the user who just bid)
        $autoBids = self::where('auction_id', $auction->id)
            ->where('is_auto_bid', true)
            ->whereNotNull('max_bid')
            ->when($excludeUserId, function($query, $userId) {
                return $query->where('user_id', '!=', $userId);
            })
            ->whereRaw('max_bid > ?', [$auction->current_price])
            ->orderBy('max_bid', 'desc') // Highest max bid first
            ->orderBy('created_at', 'asc') // Earlier auto-bid wins ties
            ->get();

        // Find who should win and calculate step-by-step battle resolution
        if ($autoBids->count() > 0) {
            // Find the auto-bid that will ultimately win (highest max_bid)
            $winnerAutoBid = $autoBids->sortByDesc('max_bid')->first();
            
            // Find what the winner should pay (second highest max_bid + increment)
            $otherAutoBids = $autoBids->where('user_id', '!=', $winnerAutoBid->user_id);
            
            if ($otherAutoBids->count() > 0) {
                // Multiple auto-bids: winner pays (second_highest_max + increment) 
                $secondHighestMax = $otherAutoBids->sortByDesc('max_bid')->first()->max_bid;
                $calculatedAmount = $secondHighestMax + $auction->bid_increment;
                
                // Never exceed winner's maximum bid amount
                $winningAmount = min($calculatedAmount, $winnerAutoBid->max_bid);
            } else {
                // Single auto-bid: outbid current price but don't exceed max
                $calculatedAmount = $auction->current_price + $auction->bid_increment;
                $winningAmount = min($calculatedAmount, $winnerAutoBid->max_bid);
            }
            
            // Check if winner can afford the winning amount and it's higher than current
            if ($winnerAutoBid->max_bid >= $winningAmount && $winningAmount > $auction->current_price) {
                if (app()->environment('local')) {
                }
                
                \DB::transaction(function () use ($auction, $winnerAutoBid, $winningAmount) {
                    // Mark all bids as not winning
                    $auction->bids()->update(['is_winning' => false]);

                    // Create winning auto-bid
                    Bid::create([
                        'auction_id' => $auction->id,
                        'user_id' => $winnerAutoBid->user_id,
                        'amount' => $winningAmount,
                        'is_winning' => true,
                        'is_auto_bid' => true,
                        'max_bid' => $winnerAutoBid->max_bid,
                        'ip_address' => $winnerAutoBid->ip_address
                    ]);

                    // Update auction price
                    $auction->update([
                        'current_price' => $winningAmount,
                        'total_bids' => $auction->total_bids + 1
                    ]);
                });

                // Notify winner
                Message::create([
                    'sender_id' => 1,
                    'receiver_id' => $winnerAutoBid->user_id,
                    'listing_id' => $auction->listing_id,
                    'message' => "Auto-bid aktiviran! Vaša ponuda: " . number_format($winningAmount, 0, ',', '.') . " RSD.",
                    'subject' => 'Auto-bid - ' . $auction->listing->title,
                    'is_system_message' => true,
                    'is_read' => false
                ]);
            }
        }
    }

    // Check if user has active auto-bid for auction
    public static function hasActivAutoBid($auctionId, $userId)
    {
        return self::where('auction_id', $auctionId)
            ->where('user_id', $userId)
            ->where('is_auto_bid', true)
            ->whereNotNull('max_bid')
            ->exists();
    }
}