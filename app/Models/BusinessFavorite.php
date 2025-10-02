<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacija sa User modelom
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacija sa Business modelom
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Scope za filtriranje po korisniku
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope za filtriranje po biznisu
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}
