<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicNotification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'is_active',
        'created_by',
        'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
