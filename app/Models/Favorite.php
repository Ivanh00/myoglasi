<?php
// app/Models/Favorite.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacija sa User modelom
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacija sa Listing modelom
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Scope za filtriranje po korisniku
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope za filtriranje po oglasu
     */
    public function scopeForListing($query, $listingId)
    {
        return $query->where('listing_id', $listingId);
    }
}