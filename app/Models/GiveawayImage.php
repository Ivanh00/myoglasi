<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GiveawayImage extends Model
{
    protected $fillable = [
        'giveaway_id',
        'image_path',
        'order'
    ];

    public function giveaway()
    {
        return $this->belongsTo(Giveaway::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}
