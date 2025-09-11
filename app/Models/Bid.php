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
        // Prevent multiple simultaneous auto-bid processing
        $cacheKey = "auto_bid_processing_{$auction->id}";
        if (\Cache::has($cacheKey)) {
            \Log::info("Auto-bid processing already in progress for auction {$auction->id}, skipping");
            return;
        }
        
        \Cache::put($cacheKey, true, 10); // Lock for 10 seconds
        
        try {
            // Debug logging
            if (app()->environment('local')) {
                \Log::info("ProcessAutoBids called for auction {$auction->id}, excluding user {$excludeUserId}");
            }
            
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
        } finally {
            \Cache::forget($cacheKey);
        }
            
        if (app()->environment('local')) {
            \Log::info("Found " . $autoBids->count() . " eligible auto-bids");
        }

        foreach ($autoBids as $autoBid) {
            // Calculate next bid amount
            $nextBidAmount = $auction->current_price + $auction->bid_increment;
            
            if (app()->environment('local')) {
                \Log::info("Auto-bid calculation - Current: {$auction->current_price}, Increment: {$auction->bid_increment}, Next: {$nextBidAmount}");
                \Log::info("Auto-bid candidate - User {$autoBid->user_id}, Max: {$autoBid->max_bid}, Can afford: " . ($autoBid->max_bid >= $nextBidAmount ? 'Yes' : 'No'));
            }
            
            // Check if auto-bidder can afford this bid
            if ($autoBid->max_bid >= $nextBidAmount) {
                if (app()->environment('local')) {
                    \Log::info("Activating auto-bid for user {$autoBid->user_id}: {$nextBidAmount} RSD");
                }
                
                // Place automatic bid
                \DB::transaction(function () use ($auction, $autoBid, $nextBidAmount) {
                    // Mark ALL previous bids as not winning
                    $auction->bids()->update(['is_winning' => false]);

                    // Create NEW auto-bid entry (preserve history)
                    Bid::create([
                        'auction_id' => $auction->id,
                        'user_id' => $autoBid->user_id,
                        'amount' => $nextBidAmount,
                        'is_winning' => true,
                        'is_auto_bid' => true,
                        'max_bid' => $autoBid->max_bid,
                        'ip_address' => $autoBid->ip_address
                    ]);

                    // Update auction current price
                    $auction->update([
                        'current_price' => $nextBidAmount,
                        'total_bids' => $auction->total_bids + 1
                    ]);
                });

                // Send notification to auto-bidder
                Message::create([
                    'sender_id' => 1, // System
                    'receiver_id' => $autoBid->user_id,
                    'listing_id' => $auction->listing_id,
                    'message' => "Vaša automatska ponuda je aktivirana na aukciji '{$auction->listing->title}'. Nova ponuda: " . 
                                number_format($nextBidAmount, 0, ',', '.') . ' RSD.',
                    'subject' => 'Automatska ponuda - ' . $auction->listing->title,
                    'is_system_message' => true,
                    'is_read' => false
                ]);

                // Refresh auction for next iteration
                $auction = $auction->fresh();
                
                // Break after first successful auto-bid to prevent chain reactions
                break;
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