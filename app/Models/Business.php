<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'slogan',
        'location',
        'address_1',
        'address_2',
        'contact_phone',
        'contact_email',
        'contact_phone_2',
        'contact_name_2',
        'contact_phone_3',
        'contact_name_3',
        'website_url',
        'facebook_url',
        'youtube_url',
        'instagram_url',
        'logo',
        'established_year',
        'user_id',
        'business_category_id',
        'subcategory_id',
        'status',
        'expires_at',
        'renewed_at',
        'renewal_count',
        'views',
        'is_from_business_plan',
        'paid_until',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'renewed_at' => 'datetime',
        'paid_until' => 'datetime',
        'established_year' => 'integer',
        'is_from_business_plan' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'subcategory_id');
    }

    public function images()
    {
        return $this->hasMany(BusinessImage::class)->orderBy('order');
    }

    public function promotions()
    {
        // TODO: Create BusinessPromotion model and table
        // For now, return empty relation
        return $this->hasMany(BusinessImage::class)->whereRaw('1 = 0');
    }

    public function favorites()
    {
        return $this->hasMany(BusinessFavorite::class);
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->name);
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

    // Promotion-related methods
    public function hasActivePromotion($type = null)
    {
        // TODO: Implement business promotions
        return false;
    }

    public function getActivePromotions()
    {
        // TODO: Implement business promotions
        return collect();
    }

    public function getPromotionBadges()
    {
        // TODO: Implement business promotions
        return [];
    }
}
