<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        ];
    }

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

}
