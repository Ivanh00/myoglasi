<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Giveaway extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'contact_phone',
        'user_id',
        'category_id',
        'subcategory_id',
        'condition_id',
        'status',
        'expires_at',
        'renewed_at',
        'renewal_count',
        'views'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'renewed_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function condition()
    {
        return $this->belongsTo(ListingCondition::class);
    }

    public function images()
    {
        return $this->hasMany(GiveawayImage::class)->orderBy('order');
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($giveaway) {
            if (empty($giveaway->slug)) {
                $giveaway->slug = Str::slug($giveaway->title);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isTaken()
    {
        return $this->status === 'taken';
    }
}
