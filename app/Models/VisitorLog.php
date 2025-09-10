<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'country',
        'country_code',
        'city',
        'region',
        'isp',
        'is_bot',
        'is_suspicious',
        'request_count',
        'first_visit',
        'last_activity',
        'user_id'
    ];

    protected $casts = [
        'first_visit' => 'datetime',
        'last_activity' => 'datetime',
        'is_bot' => 'boolean',
        'is_suspicious' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Log visitor activity
    public static function logVisitor($ip, $userAgent = null, $userId = null)
    {
        $visitor = self::where('ip_address', $ip)->first();

        if ($visitor) {
            // Update existing visitor
            $visitor->increment('request_count');
            $visitor->update([
                'last_activity' => now(),
                'user_agent' => $userAgent ?? $visitor->user_agent,
                'user_id' => $userId ?? $visitor->user_id
            ]);
        } else {
            // Create new visitor log
            $geoData = self::getGeoData($ip);
            
            $visitor = self::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'country' => $geoData['country'] ?? null,
                'country_code' => $geoData['country_code'] ?? null,
                'city' => $geoData['city'] ?? null,
                'region' => $geoData['region'] ?? null,
                'isp' => $geoData['isp'] ?? null,
                'is_bot' => self::isBot($userAgent),
                'is_suspicious' => false,
                'request_count' => 1,
                'first_visit' => now(),
                'last_activity' => now(),
                'user_id' => $userId
            ]);
        }

        // Check for suspicious activity
        if ($visitor->request_count > 100 && !$visitor->is_suspicious) {
            $visitor->update(['is_suspicious' => true]);
            
            // Auto-block if enabled
            if (Setting::get('auto_block_enabled', true)) {
                IpBlock::autoBlock($ip, 'Automatsko blokiranje - sumnjiva aktivnost: ' . $visitor->request_count . ' zahteva');
                
                // Notify admin
                self::notifyAdminOfAutoBlock($ip, $visitor);
            }
        }

        return $visitor;
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
                    'city' => $data['city'] ?? null,
                    'region' => $data['regionName'] ?? null,
                    'isp' => $data['isp'] ?? null
                ];
            }
        } catch (\Exception $e) {
            // Silently fail for geo lookup
        }

        return [];
    }

    // Detect if user agent is a bot
    protected static function isBot($userAgent)
    {
        if (!$userAgent) return false;

        $botPatterns = [
            'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget', 'python',
            'googlebot', 'bingbot', 'facebookexternalhit', 'twitterbot'
        ];

        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    // Notify admin of auto-block
    protected static function notifyAdminOfAutoBlock($ip, $visitor)
    {
        // Send system message to admin
        Message::create([
            'sender_id' => 1, // System
            'receiver_id' => 1, // Admin (assuming admin has ID 1)
            'listing_id' => null,
            'message' => "Firewall je automatski blokirao IP adresu {$ip} zbog sumnjive aktivnosti.\n\n" .
                        "Detalji:\n" .
                        "- Ukupno zahteva: {$visitor->request_count}\n" .
                        "- Zemlja: {$visitor->country}\n" .
                        "- User Agent: {$visitor->user_agent}\n" .
                        "- Prva poseta: {$visitor->first_visit->format('d.m.Y H:i')}\n" .
                        "- Poslednja aktivnost: {$visitor->last_activity->format('d.m.Y H:i')}",
            'subject' => 'Firewall - Automatsko blokiranje IP adrese',
            'is_system_message' => true,
            'is_read' => false
        ]);
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
}