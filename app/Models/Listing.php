<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'price', 'location', 'contact_phone',
        'user_id', 'category_id', 'subcategory_id', 'condition_id', 'status', 
        'listing_type', 'expires_at', 'renewed_at', 'renewal_count'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'renewed_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relacija za subkategoriju
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
        return $this->hasMany(ListingImage::class)->orderBy('order');
    }

    public function promotions()
    {
        return $this->hasMany(ListingPromotion::class);
    }

    public function activePromotions()
    {
        return $this->hasMany(ListingPromotion::class)->active();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function primaryImage()
    {
        return $this->hasOne(ListingImage::class)->where('is_primary', true);
    }

    public function favorites()
{
    return $this->hasMany(Favorite::class);
}

public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites')
                ->withTimestamps();
}

public function getFavoritesCountAttribute()
{
    return $this->favorites()->count();
}

public function isFavoritedBy($user)
{
    if (!$user) {
        return false;
    }
    
    return $this->favorites()
                ->where('user_id', $user->id)
                ->exists();
}

public function isExpired()
{
    return $this->expires_at && $this->expires_at->isPast();
}

public function isActive()
{
    return $this->status === 'active' && !$this->isExpired();
}

public function canBeRenewed()
{
    return $this->status === 'expired' || $this->isExpired();
}

public function renewListing()
{
    $user = $this->user;
    
    // Check if user can renew (payment check)
    if (!$user->chargeForListing()) {
        return false;
    }
    
    // Renew the listing
    $expiryDays = Setting::get('listing_auto_expire_days', 60);
    
    $this->update([
        'status' => 'active',
        'expires_at' => now()->addDays($expiryDays),
        'renewed_at' => now(),
    ]);
    
    $this->increment('renewal_count');
    
    return true;
}

public function getDaysUntilExpiryAttribute()
{
    if (!$this->expires_at) {
        return null;
    }
    
    return max(0, $this->expires_at->diffInDays(now(), false));
}

public function isReportedBy($userId)
{
    return ListingReport::where('user_id', $userId)
        ->where('listing_id', $this->id)
        ->exists();
}

public function getReportByUser($userId)
{
    return ListingReport::where('user_id', $userId)
        ->where('listing_id', $this->id)
        ->first();
}

public function auction()
{
    return $this->hasOne(Auction::class);
}

public function hasActiveAuction()
{
    return $this->auction && $this->auction->isActive();
}

// Promotion methods
public function hasActivePromotion($type = null)
{
    $query = $this->activePromotions();
    
    if ($type) {
        $query->where('type', $type);
    }
    
    return $query->exists();
}

public function getActivePromotion($type)
{
    return $this->activePromotions()->where('type', $type)->first();
}

public function isFeaturedInCategory()
{
    return $this->hasActivePromotion('featured_category');
}

public function isFeaturedOnHomepage()
{
    return $this->hasActivePromotion('featured_homepage');
}

public function isHighlighted()
{
    return $this->hasActivePromotion('highlighted');
}

public function hasAutoRefresh()
{
    return $this->hasActivePromotion('auto_refresh');
}

public function hasDoubleImages()
{
    return $this->hasActivePromotion('double_images');
}

public function getMaxImagesCount()
{
    $baseMax = \App\Models\Setting::get('max_images_per_listing', 10);
    
    if ($this->hasDoubleImages()) {
        return $baseMax * 2;
    }
    
    return $baseMax;
}

public function hasExtendedDuration()
{
    return $this->hasActivePromotion('extended_duration');
}

public function getPromotionBadges()
{
    $badges = [];
    
    if ($this->isFeaturedOnHomepage()) {
        $badges[] = ['text' => 'TOP', 'class' => 'bg-red-500 text-white'];
    }
    
    if ($this->isFeaturedInCategory()) {
        $badges[] = ['text' => 'VRH', 'class' => 'bg-blue-500 text-white'];
    }
    
    if ($this->isHighlighted()) {
        $badges[] = ['text' => 'ISTAKNUT', 'class' => 'bg-yellow-500 text-black'];
    }
    
    return $badges;
}

// Listing type helper methods
public function isService()
{
    return $this->listing_type === 'service';
}

public function isGiveaway()
{
    return $this->listing_type === 'giveaway';
}

public function isRegularListing()
{
    return $this->listing_type === 'listing' || $this->listing_type === null;
}

public function getTypeDisplayName()
{
    return match($this->listing_type) {
        'service' => 'Usluga',
        'giveaway' => 'Poklon',
        'listing' => 'Oglas',
        default => 'Oglas'
    };
}

public function getTypeBadge()
{
    return match($this->listing_type) {
        'service' => ['text' => 'USLUGA', 'class' => 'bg-gray-500 text-white'],
        'giveaway' => ['text' => 'POKLON', 'class' => 'bg-green-500 text-white'],
        default => null
    };
}
}