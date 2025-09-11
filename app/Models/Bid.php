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

        // Find the optimal final bid amount by simulating the entire auto-bid battle
        if ($autoBids->count() > 0) {
            // Get the two highest auto-bids for battle simulation
            $autoBidsByMax = $autoBids->sortByDesc('max_bid');
            $highestAutoBid = $autoBidsByMax->first();
            $secondHighestAutoBid = $autoBidsByMax->skip(1)->first();
            
            // Calculate final winning bid amount
            if ($secondHighestAutoBid) {
                // Two or more auto-bids: winner bids (second_highest_max + increment)
                $finalBidAmount = $secondHighestAutoBid->max_bid + $auction->bid_increment;
                if (app()->environment('local')) {
                    \Log::info("Auto-bid battle: Winner {$highestAutoBid->user_id} (max {$highestAutoBid->max_bid}) vs Runner-up {$secondHighestAutoBid->user_id} (max {$secondHighestAutoBid->max_bid})");
                    \Log::info("Final auto-bid amount: {$secondHighestAutoBid->max_bid} + {$auction->bid_increment} = {$finalBidAmount}");
                }
            } else {
                // Only one auto-bid: simple increment from current price
                $finalBidAmount = $auction->current_price + $auction->bid_increment;
                if (app()->environment('local')) {
                    \Log::info("Single auto-bid: {$auction->current_price} + {$auction->bid_increment} = {$finalBidAmount}");
                }
            }
            
            // Only place auto-bid if the calculated amount is higher than current price
            if ($highestAutoBid->max_bid >= $finalBidAmount && $finalBidAmount > $auction->current_price) {
                if (app()->environment('local')) {
                    \Log::info("Placing final auto-bid for user {$highestAutoBid->user_id}: {$finalBidAmount} RSD");
                }
                
                // Place final winning auto-bid
                \DB::transaction(function () use ($auction, $highestAutoBid, $finalBidAmount) {
                    // Mark ALL previous bids as not winning
                    $auction->bids()->update(['is_winning' => false]);

                    // Create final auto-bid entry
                    Bid::create([
                        'auction_id' => $auction->id,
                        'user_id' => $highestAutoBid->user_id,
                        'amount' => $finalBidAmount,
                        'is_winning' => true,
                        'is_auto_bid' => true,
                        'max_bid' => $highestAutoBid->max_bid,
                        'ip_address' => $highestAutoBid->ip_address
                    ]);

                    // Update auction current price
                    $auction->update([
                        'current_price' => $finalBidAmount,
                        'total_bids' => $auction->total_bids + 1
                    ]);
                });

                // Send notification to winner
                Message::create([
                    'sender_id' => 1,
                    'receiver_id' => $highestAutoBid->user_id,
                    'listing_id' => $auction->listing_id,
                    'message' => "Auto-bid pobeda! Ponuda: " . number_format($finalBidAmount, 0, ',', '.') . " RSD protiv konkurencije.",
                    'subject' => 'Auto-bid pobeda - ' . $auction->listing->title,
                    'is_system_message' => true,
                    'is_read' => false
                ]);
            } else {
                if (app()->environment('local')) {
                    \Log::info("No auto-bid needed - current price {$auction->current_price} already higher than calculated {$finalBidAmount}");
                }
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