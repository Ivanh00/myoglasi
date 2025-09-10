<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'country',
        'country_code',
        'city',
        'login_at',
        'last_activity',
        'request_count',
        'is_active'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'last_activity' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Track user session activity
    public static function trackSession($sessionId, $ip, $userAgent = null, $userId = null)
    {
        $session = self::where('session_id', $sessionId)->first();

        if ($session) {
            // Update existing session
            $session->increment('request_count');
            $session->update([
                'last_activity' => now(),
                'user_id' => $userId ?? $session->user_id,
                'is_active' => true
            ]);
        } else {
            // Create new session
            $geoData = self::getGeoData($ip);
            
            $session = self::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'country' => $geoData['country'] ?? null,
                'country_code' => $geoData['country_code'] ?? null,
                'city' => $geoData['city'] ?? null,
                'login_at' => $userId ? now() : null,
                'last_activity' => now(),
                'request_count' => 1,
                'is_active' => true
            ]);
        }

        return $session;
    }

    // Clean up old sessions
    public static function cleanupOldSessions()
    {
        // Mark sessions older than 1 hour as inactive
        self::where('last_activity', '<', now()->subHour())
            ->update(['is_active' => false]);
            
        // Delete sessions older than 7 days
        self::where('last_activity', '<', now()->subDays(7))->delete();
    }

    // Get geo data from IP
    protected static function getGeoData($ip)
    {
        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return ['country' => 'Local', 'country_code' => 'LOCAL'];
        }

        try {
            // Using free ip-api.com service
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'country' => $data['country'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'city' => $data['city'] ?? null
                ];
            }
        } catch (\Exception $e) {
            // Silently fail for geo lookup
        }

        return [];
    }

    // Get country flag emoji
    public function getCountryFlagAttribute()
    {
        if (!$this->country_code) return '🌍';
        
        $flags = [
            'RS' => '🇷🇸', 'US' => '🇺🇸', 'DE' => '🇩🇪', 'FR' => '🇫🇷', 'GB' => '🇬🇧',
            'IT' => '🇮🇹', 'ES' => '🇪🇸', 'RU' => '🇷🇺', 'CN' => '🇨🇳', 'JP' => '🇯🇵',
            'LOCAL' => '🏠'
        ];

        return $flags[$this->country_code] ?? '🌍';
    }

    // Get users grouped by IP
    public static function getUsersByIp()
    {
        return self::with('user')
            ->whereNotNull('user_id')
            ->where('is_active', true)
            ->where('last_activity', '>', now()->subMinutes(30))
            ->orderBy('ip_address')
            ->orderBy('last_activity', 'desc')
            ->get()
            ->groupBy('ip_address');
    }
}