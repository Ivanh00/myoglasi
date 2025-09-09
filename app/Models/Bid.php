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

        return true;
    }
}