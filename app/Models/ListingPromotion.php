<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ListingPromotion extends Model
{
    protected $fillable = [
        'listing_id',
        'type',
        'starts_at',
        'expires_at',
        'price_paid',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('expires_at', '>', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function isActive()
    {
        return $this->is_active && 
               $this->starts_at <= now() && 
               $this->expires_at > now();
    }

    public function getTypeName()
    {
        return match($this->type) {
            'featured_category' => 'Top kategorije',
            'featured_homepage' => 'Top glavne strane',
            'highlighted' => 'Istaknut oglas',
            'auto_refresh' => 'Automatsko osvežavanje',
            'double_images' => 'Dupliraj broj slika',
            'extended_duration' => 'Produžena trajnost',
            default => 'Nepoznato'
        };
    }

    public static function getPromotionTypes()
    {
        return [
            'featured_category' => 'Top kategorije',
            'featured_homepage' => 'Top glavne strane',
            'highlighted' => 'Istaknut oglas',
            'auto_refresh' => 'Automatsko osvežavanje',
            'double_images' => 'Dupliraj broj slika',
            'extended_duration' => 'Produžena trajnost',
        ];
    }

    public static function getDefaultPrice($type)
    {
        $prices = [
            'featured_category' => \App\Models\Setting::get('promotion_featured_category_price', 100),
            'featured_homepage' => \App\Models\Setting::get('promotion_featured_homepage_price', 200),
            'highlighted' => \App\Models\Setting::get('promotion_highlighted_price', 50),
            'auto_refresh' => \App\Models\Setting::get('promotion_auto_refresh_price', 80),
            'double_images' => \App\Models\Setting::get('promotion_double_images_price', 30),
            'extended_duration' => \App\Models\Setting::get('promotion_extended_duration_price', 60),
        ];

        return $prices[$type] ?? 0;
    }

    public static function getDefaultDuration($type)
    {
        $durations = [
            'featured_category' => \App\Models\Setting::get('promotion_featured_category_days', 7),
            'featured_homepage' => \App\Models\Setting::get('promotion_featured_homepage_days', 3),
            'highlighted' => \App\Models\Setting::get('promotion_highlighted_days', 14),
            'auto_refresh' => \App\Models\Setting::get('promotion_auto_refresh_days', 30),
            'double_images' => \App\Models\Setting::get('promotion_double_images_days', 14),
            'extended_duration' => \App\Models\Setting::get('promotion_extended_duration_days', 30),
        ];

        return $durations[$type] ?? 7;
    }
}
