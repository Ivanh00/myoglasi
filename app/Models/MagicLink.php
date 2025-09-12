<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MagicLink extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime'
    ];

    public static function createForEmail($email)
    {
        // Invalidate existing links for this email
        self::where('email', $email)->delete();

        return self::create([
            'email' => $email,
            'token' => Str::random(64),
            'expires_at' => now()->addMinutes(15), // 15 minutes validity
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function isValid()
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }

    public function getUrl()
    {
        return route('auth.magic-login', ['token' => $this->token]);
    }
}
