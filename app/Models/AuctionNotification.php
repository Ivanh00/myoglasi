<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionNotification extends Model
{
    protected $fillable = [
        'auction_id',
        'user_id',
        'type',
        'notified',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
