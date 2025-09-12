<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MagicLink;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MagicLoginController extends Controller
{
    public function login($token)
    {
        $magicLink = MagicLink::where('token', $token)->first();

        if (!$magicLink || !$magicLink->isValid()) {
            return redirect()->route('login')->with('error', 'Magic link je nevažeći ili je istekao. Probajte ponovo.');
        }

        // Find or create user
        $user = User::where('email', $magicLink->email)->first();
        
        if (!$user) {
            // Create new user automatically
            $user = User::create([
                'name' => $this->generateUniqueUsername($magicLink->email),
                'email' => $magicLink->email,
                'password' => bcrypt(Str::random(32)), // Random password
                'email_verified_at' => now(), // Auto-verify magic link users
            ]);
        }

        // Mark magic link as used
        $magicLink->markAsUsed();

        // Login user
        Auth::login($user, true); // Remember = true

        return redirect()->route('dashboard')->with('success', 'Uspešno ste se prijavili!');
    }

    private function generateUniqueUsername($email)
    {
        $baseUsername = Str::before($email, '@');
        $baseUsername = Str::slug($baseUsername, '');
        
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('name', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
