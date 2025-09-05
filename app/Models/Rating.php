<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rater_id',
        'rated_user_id', 
        'listing_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function getRatingIconAttribute()
    {
        return match($this->rating) {
            'positive' => '😊',
            'neutral' => '😐', 
            'negative' => '😞',
            default => '😐'
        };
    }

    public function getRatingColorAttribute()
    {
        return match($this->rating) {
            'positive' => 'text-green-600',
            'neutral' => 'text-yellow-600',
            'negative' => 'text-red-600', 
            default => 'text-gray-600'
        };
    }

    public function getRatingBackgroundAttribute()
    {
        return match($this->rating) {
            'positive' => 'bg-green-100',
            'neutral' => 'bg-yellow-100', 
            'negative' => 'bg-red-100',
            default => 'bg-gray-100'
        };
    }
}