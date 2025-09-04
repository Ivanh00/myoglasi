<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
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
            'plan_expires_at' => 'datetime',
            'free_listings_reset_at' => 'datetime',
        ];
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
        
        if ($this->hasActivePlan()) {
            $expiry = $this->plan_expires_at ? $this->plan_expires_at->format('d.m.Y') : 'Neograničeno';
            return ucfirst($this->payment_plan) . ' plan (do ' . $expiry . ')';
        }
        
        return 'Plaćanje po oglasu';
    }
}