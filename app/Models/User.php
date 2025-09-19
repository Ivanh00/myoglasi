<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Rating;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',          
        'phone',         
        'phone_visible', 
        'avatar',
        'is_admin',
        'is_banned',
        'banned_at',
        'ban_reason',
        'seller_terms',
        'payment_plan',
        'payment_enabled',
        'plan_expires_at',
        'free_listings_used',
        'free_listings_reset_at',
        'verification_status',
        'verification_comment',
        'verification_requested_at',
        'verified_at',
        'verified_by',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_visible' => 'boolean',
            'is_admin' => 'boolean', // DODAJTE OVO
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'balance' => 'decimal:2',
            'payment_enabled' => 'boolean',
            'last_seen_at' => 'datetime',
            'plan_expires_at' => 'datetime',
            'free_listings_reset_at' => 'datetime',
            'verification_requested_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    // Verification relationships
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Verification helper methods
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function isPendingVerification()
    {
        return $this->verification_status === 'pending';
    }

    public function isVerificationRejected()
    {
        return $this->verification_status === 'rejected';
    }

    public function getVerificationBadgeAttribute()
    {
        return match($this->verification_status) {
            'verified' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Verifikovan</span>',
            'pending' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800"><i class="fas fa-clock mr-1"></i>Na čekanju</span>',
            'rejected' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Odbijena</span>',
            default => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i class="fas fa-user mr-1"></i>Neverifikovan</span>'
        };
    }

    public function getVerificationStatusTextAttribute()
    {
        return match($this->verification_status) {
            'verified' => 'Verifikovan',
            'pending' => 'Na čekanju',
            'rejected' => 'Odbijena verifikacija',
            default => 'Neverifikovan'
        };
    }

    public function requestVerification()
    {
        $this->update([
            'verification_status' => 'pending',
            'verification_requested_at' => now()
        ]);
    }

    public function approveVerification($adminId, $comment = null)
    {
        $this->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $adminId,
            'verification_comment' => $comment
        ]);
    }

    public function rejectVerification($adminId, $comment = null)
    {
        $this->update([
            'verification_status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => $adminId,
            'verification_comment' => $comment
        ]);
    }

    // Simple verified icon for inline display
    public function getVerifiedIconAttribute()
    {
        if ($this->verification_status === 'verified') {
            return '<i class="fas fa-user-check text-green-600 ml-1" title="Verifikovan korisnik"></i>';
        }
        return '';
    }

    // Concurrent listing limit methods
    public function getActiveListingsCount()
    {
        return $this->listings()
            ->where('status', 'active')
            ->count();
    }
    
    public function canCreateListing()
    {
        // Admin can always create listings
        if ($this->is_admin) {
            return true;
        }
        
        // Users with payment enabled have no limits
        if ($this->payment_enabled) {
            return true;
        }
        
        // Check concurrent active listings limit for users with payment disabled
        $activeListingLimit = Setting::get('monthly_listing_limit', 50); // Reusing same setting name
        $currentActiveListings = $this->getActiveListingsCount();
        
        return $currentActiveListings < $activeListingLimit;
    }
    
    public function getRemainingListings()
    {
        if ($this->is_admin || $this->payment_enabled) {
            return 'neograničeno';
        }
        
        $activeListingLimit = Setting::get('monthly_listing_limit', 50);
        $currentActiveListings = $this->getActiveListingsCount();
        
        return max(0, $activeListingLimit - $currentActiveListings);
    }

    // Ostale metode ostaju iste...
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
    
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function deductBalance($amount)
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }

    public function getVisiblePhoneAttribute()
    {
        return $this->phone_visible && $this->phone ? $this->phone : null;
    }
    
    public function canViewPhone(?User $viewer)
    {
        // Phone je vidljiv ako:
        // 1. Korisnik je označio da bude vidljiv
        // 2. I viewer je registrovan
        return $this->phone_visible && $this->phone && $viewer !== null;
    }
    

    public function chargeFee($amount)
    {
        $this->decrement('balance', $amount);
        
        Transaction::create([
            'user_id' => $this->id,
            'amount' => -$amount,
            'type' => 'fee',
            'description' => 'Naplaćena taxa za objavljivanje oglasa'
        ]);
    }

    public function favorites()
{
    return $this->hasMany(Favorite::class);
}

public function favoriteListings()
{
    return $this->belongsToMany(Listing::class, 'favorites')
                ->withTimestamps();
}

public function hasFavorited(Listing $listing)
{
    return $this->favorites()
                ->where('listing_id', $listing->id)
                ->exists();
}

public function addToFavorites(Listing $listing)
{
    if (!$this->hasFavorited($listing)) {
        return $this->favorites()->create([
            'listing_id' => $listing->id
        ]);
    }
    
    return false;
}

public function removeFromFavorites(Listing $listing)
{
    return $this->favorites()
                ->where('listing_id', $listing->id)
                ->delete();
}

    public function unreadMessages()
{
    return $this->hasMany(Message::class, 'receiver_id')->where('is_read', false);
}

public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    // U User modelu dodajte ovu metodu:
public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        return Storage::url($this->avatar);
    }
    
    // Vraća URL za inicijale ako nema avatara
    return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
}

    // Payment Plan Methods
    public function hasActivePlan(): bool
    {
        return $this->payment_plan !== 'per_listing' && 
               ($this->plan_expires_at === null || $this->plan_expires_at->isFuture());
    }
    
    public function canCreateListingForFree(): bool
    {
        if (!$this->payment_enabled) {
            return true; // Admin disabled payments for this user
        }
        
        if ($this->hasActivePlan()) {
            return true; // User has active monthly/yearly plan
        }
        
        // Check free listings quota
        $freeListingsLimit = Setting::get('free_listings_per_month', 0);
        if ($freeListingsLimit == 0) {
            return false; // No free listings allowed
        }
        
        // Reset counter if needed
        $this->resetFreeListingsIfNeeded();
        
        return $this->free_listings_used < $freeListingsLimit;
    }
    
    public function chargeForListing(): bool
    {
        if (!$this->payment_enabled) {
            return true; // Admin disabled payments
        }
        
        if ($this->hasActivePlan()) {
            return true; // Already paid with plan
        }
        
        // Try free listings first
        if ($this->canCreateListingForFree()) {
            $this->incrementFreeListingsUsed();
            return true;
        }
        
        // Charge per listing
        if (!Setting::get('listing_fee_enabled', true)) {
            return true; // Fees disabled globally
        }
        
        $fee = Setting::get('listing_fee_amount', 10);
        
        if ($this->balance < $fee) {
            return false; // Insufficient funds
        }
        
        $this->deductBalance($fee);
        
        // Create transaction record
        Transaction::create([
            'user_id' => $this->id,
            'type' => 'listing_fee',
            'amount' => $fee,
            'status' => 'completed',
            'description' => 'Naplaćen oglas - ' . $fee . ' RSD',
        ]);
        
        return true;
    }
    
    private function resetFreeListingsIfNeeded(): void
    {
        $now = now();
        
        if ($this->free_listings_reset_at === null || $this->free_listings_reset_at->isPast()) {
            $this->update([
                'free_listings_used' => 0,
                'free_listings_reset_at' => $now->addMonth()
            ]);
        }
    }
    
    private function incrementFreeListingsUsed(): void
    {
        $this->increment('free_listings_used');
    }
    
    public function purchaseMonthlyPlan(): bool
    {
        $price = Setting::get('monthly_plan_price', 500);
        
        if ($this->balance < $price) {
            return false;
        }
        
        $this->deductBalance($price);
        
        $this->update([
            'payment_plan' => 'monthly',
            'plan_expires_at' => now()->addMonth()
        ]);
        
        Transaction::create([
            'user_id' => $this->id,
            'type' => 'plan_purchase',
            'amount' => $price,
            'status' => 'completed',
            'description' => 'Kupljen mesečni plan - ' . $price . ' RSD',
        ]);
        
        return true;
    }
    
    public function purchaseYearlyPlan(): bool
    {
        $price = Setting::get('yearly_plan_price', 5000);
        
        if ($this->balance < $price) {
            return false;
        }
        
        $this->deductBalance($price);
        
        $this->update([
            'payment_plan' => 'yearly',
            'plan_expires_at' => now()->addYear()
        ]);
        
        Transaction::create([
            'user_id' => $this->id,
            'type' => 'plan_purchase',
            'amount' => $price,
            'status' => 'completed',
            'description' => 'Kupljen godišnji plan - ' . $price . ' RSD',
        ]);
        
        return true;
    }
    
    public function getPlanStatusAttribute(): string
    {
        if (!$this->payment_enabled) {
            return 'Isključeno plaćanje';
        }
        
        if ($this->payment_plan === 'free') {
            return 'Besplatan plan';
        }
        
        if ($this->payment_plan === 'per_listing') {
            return 'Plaćanje po oglasu';
        }
        
        if ($this->hasActivePlan()) {
            $expiry = $this->plan_expires_at ? $this->plan_expires_at->format('d.m.Y') : 'Neograničeno';
            return ucfirst($this->payment_plan) . ' plan (do ' . $expiry . ')';
        }
        
        return 'Plaćanje po oglasu';
    }

    // Rating relationships
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    // Rating statistics
    public function getPositiveRatingsCountAttribute()
    {
        return $this->ratingsReceived()->where('rating', 'positive')->count();
    }

    public function getNeutralRatingsCountAttribute()
    {
        return $this->ratingsReceived()->where('rating', 'neutral')->count();
    }

    public function getNegativeRatingsCountAttribute()
    {
        return $this->ratingsReceived()->where('rating', 'negative')->count();
    }

    public function getTotalRatingsCountAttribute()
    {
        return $this->ratingsReceived()->count();
    }

    public function getRatingPercentageAttribute()
    {
        $total = $this->total_ratings_count;
        if ($total == 0) return null;
        
        return [
            'positive' => round(($this->positive_ratings_count / $total) * 100),
            'neutral' => round(($this->neutral_ratings_count / $total) * 100),
            'negative' => round(($this->negative_ratings_count / $total) * 100)
        ];
    }

    public function getRatingBadgeAttribute()
    {
        $total = $this->total_ratings_count;
        if ($total == 0) return null;
        
        $positive = $this->positive_ratings_count;
        $negative = $this->negative_ratings_count;
        
        $positivePercent = ($positive / $total) * 100;
        
        if ($positivePercent >= 90) return '⭐⭐⭐'; // Excellent
        if ($positivePercent >= 75) return '⭐⭐'; // Good
        if ($positivePercent >= 50) return '⭐'; // OK
        return null; // Poor
    }

    public function canBeRatedBy($userId, $listingId)
    {
        // Check if user has already rated this user for this listing
        return !$this->ratingsReceived()
            ->where('rater_id', $userId)
            ->where('listing_id', $listingId)
            ->exists();
    }

    // Last seen functionality
    public function updateLastSeen()
    {
        $this->update(['last_seen_at' => now()]);
    }

    public function getLastSeenAttribute()
    {
        if (!$this->last_seen_at) {
            return 'Nikad';
        }

        $diffInMinutes = $this->last_seen_at->diffInMinutes(now());
        
        if ($diffInMinutes < 5) {
            return 'Online';
        } elseif ($diffInMinutes < 60) {
            return 'Pre ' . floor($diffInMinutes) . ' min';
        } elseif ($diffInMinutes < 1440) { // 24 hours
            $hours = floor($diffInMinutes / 60);
            return 'Pre ' . $hours . ' ' . ($hours == 1 ? 'sat' : ($hours < 5 ? 'sata' : 'sati'));
        } elseif ($diffInMinutes < 10080) { // 7 days
            $days = floor($diffInMinutes / 1440);
            return 'Pre ' . $days . ' ' . ($days == 1 ? 'dan' : 'dana');
        } else {
            return $this->last_seen_at->format('d.m.Y');
        }
    }

    public function getIsOnlineAttribute()
    {
        if (!$this->last_seen_at) {
            return false;
        }
        
        return $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    public function shouldShowLastSeen()
    {
        return Setting::get('show_last_seen', true);
    }
}