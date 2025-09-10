<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'ip_range_start', 
        'ip_range_end',
        'type', // single, range, whitelist
        'action', // block, allow
        'reason',
        'expires_at',
        'created_by',
        'is_active',
        'auto_generated'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'auto_generated' => 'boolean'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Check if IP is blocked
    public static function isBlocked($ip)
    {
        return self::where('is_active', true)
            ->where('action', 'block')
            ->where(function ($query) use ($ip) {
                $query->where('ip_address', $ip)
                    ->orWhere(function ($q) use ($ip) {
                        $q->where('type', 'range')
                          ->where('ip_range_start', '<=', $ip)
                          ->where('ip_range_end', '>=', $ip);
                    });
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    // Check if IP is whitelisted
    public static function isWhitelisted($ip)
    {
        return self::where('is_active', true)
            ->where('action', 'allow')
            ->where('type', 'whitelist')
            ->where('ip_address', $ip)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    // Auto-block suspicious IP
    public static function autoBlock($ip, $reason, $duration = 24)
    {
        return self::create([
            'ip_address' => $ip,
            'type' => 'single',
            'action' => 'block',
            'reason' => $reason,
            'expires_at' => now()->addHours($duration),
            'created_by' => 1, // System
            'is_active' => true,
            'auto_generated' => true
        ]);
    }

    // Get blocked status text
    public function getStatusTextAttribute()
    {
        if (!$this->is_active) return 'Deaktiviran';
        if ($this->expires_at && $this->expires_at->isPast()) return 'Istekao';
        if ($this->expires_at) return 'Privremeno do ' . $this->expires_at->format('d.m.Y H:i');
        return 'Trajno';
    }

    // Get type text
    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'single' => 'Pojedinačna IP',
            'range' => 'Opseg IP adresa',
            'whitelist' => 'Whitelist',
            default => 'Nepoznato'
        };
    }
}