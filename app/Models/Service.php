<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description', 
        'price',
        'location',
        'contact_phone',
        'user_id',
        'service_category_id',
        'subcategory_id',
        'status',
        'expires_at',
        'renewed_at',
        'renewal_count',
        'views'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'renewed_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'subcategory_id');
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class)->orderBy('order');
    }

    public function promotions()
    {
        return $this->hasMany(ListingPromotion::class);
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
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
        $query = $this->promotions()
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now());

        if ($type) {
            $query->where('type', $type);
        }

        return $query->exists();
    }

    public function getActivePromotions()
    {
        return $this->promotions()
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now())
            ->get();
    }

    public function getPromotionBadges()
    {
        $badges = [];
        $activePromotions = $this->getActivePromotions();

        foreach ($activePromotions as $promotion) {
            $badges[] = $promotion->getBadgeDetails();
        }

        return $badges;
    }
}
