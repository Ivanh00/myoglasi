<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ListingPromotion extends Model
{
    protected $fillable = [
        'listing_id',
        'service_id',
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

    public function service()
    {
        return $this->belongsTo(Service::class);
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

    public function getBadgeDetails()
    {
        $badges = [
            'featured_category' => [
                'text' => 'TOP',
                'class' => 'bg-sky-500 dark:bg-sky-900 text-white dark:text-sky-200'
            ],
            'featured_homepage' => [
                'text' => 'ISTAKNUT',
                'class' => 'bg-red-500 dark:bg-red-800 text-white dark:text-red-200'
            ],
            'highlighted' => [
                'text' => 'OZNAČEN',
                'class' => 'bg-amber-500 dark:bg-amber-800 text-white dark:text-amber-200'
            ],
            'auto_refresh' => [
                'text' => 'AUTO',
                'class' => 'bg-green-500 dark:bg-green-800 text-white dark:text-green-200'
            ],
            'double_images' => [
                'text' => '2X SLIKE',
                'class' => 'bg-purple-500 dark:bg-purple-800 text-white dark:text-purple-200'
            ],
            'extended_duration' => [
                'text' => 'PRODUŽEN',
                'class' => 'bg-orange-500 dark:bg-orange-800 text-white dark:text-orange-200'
            ]
        ];

        return $badges[$this->type] ?? [];
    }
}
