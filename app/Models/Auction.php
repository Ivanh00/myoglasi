<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'listing_id',
        'user_id',
        'starting_price',
        'buy_now_price',
        'current_price',
        'total_bids',
        'starts_at',
        'ends_at',
        'extended_at',
        'extension_count',
        'status',
        'winner_id',
        'deleted_by'
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'buy_now_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'extended_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winningBid()
    {
        return $this->hasOne(Bid::class)->where('is_winning', true);
    }

    // Auction status methods
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->starts_at->isPast() && 
               $this->ends_at->isFuture();
    }

    public function hasEnded()
    {
        return $this->status === 'ended' || $this->ends_at->isPast();
    }

    public function canBid()
    {
        return $this->isActive();
    }

    public function needsExtension()
    {
        if (!$this->isActive()) return false;
        
        $triggerTime = \App\Models\Setting::get('auction_extension_trigger_time', 3);
        
        // Ako je ponuda stigla u poslednje X minuta (iz admin podešavanja)
        return $this->ends_at->diffInMinutes(now()) <= $triggerTime &&
               $this->bids()->where('created_at', '>=', $this->ends_at->subMinutes($triggerTime))->exists();
    }

    public function extendAuction()
    {
        $maxExtensions = \App\Models\Setting::get('auction_max_extensions', 10);
        $extensionTime = \App\Models\Setting::get('auction_extension_time', 3);
        
        if ($this->needsExtension() && $this->extension_count < $maxExtensions) {
            $this->update([
                'ends_at' => $this->ends_at->addMinutes($extensionTime),
                'extended_at' => now(),
                'extension_count' => $this->extension_count + 1
            ]);
            
            return true;
        }
        
        return false;
    }

    public function getTimeLeftAttribute()
    {
        if ($this->hasEnded()) return null;
        
        // Ako je aukcija završena (vreme je prošlo)
        if ($this->ends_at->isPast()) return null;
        
        // Koristimo timestamp razliku za precizan izračun
        $totalSeconds = $this->ends_at->timestamp - now()->timestamp;
        
        // Ako je negativan (što ne bi trebalo), vrati null
        if ($totalSeconds <= 0) return null;
        
        $days = floor($totalSeconds / (24 * 60 * 60));
        $hours = floor(($totalSeconds % (24 * 60 * 60)) / (60 * 60));
        $minutes = floor(($totalSeconds % (60 * 60)) / 60);
        $seconds = $totalSeconds % 60;
        
        return [
            'days' => (int)$days,
            'hours' => (int)$hours,
            'minutes' => (int)$minutes,
            'seconds' => (int)$seconds,
            'total_seconds' => (int)$totalSeconds,
            'formatted' => $this->formatTimeLeft($days, $hours, $minutes, $seconds)
        ];
    }
    
    private function formatTimeLeft($days, $hours, $minutes, $seconds)
    {
        if ($days > 0) {
            return $days . 'd ' . $hours . 'h';
        } elseif ($hours > 0) {
            return $hours . ':' . sprintf('%02d', $minutes) . ':' . sprintf('%02d', $seconds);
        } else {
            return $minutes . ':' . sprintf('%02d', $seconds);
        }
    }

    public function getBidIncrementAttribute()
    {
        // Koristimo tačno admin podešavanje kao minimalni korak
        return \App\Models\Setting::get('auction_default_bid_increment', 50);
    }

    public function getMinimumBidAttribute()
    {
        return $this->current_price + $this->bid_increment;
    }
}