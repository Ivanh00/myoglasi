<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->with('error', 'Nepodržan provider.');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Greška pri redirekciji na ' . $provider . '. Probajte obični login.');
        }
    }

    public function callback($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->with('error', 'Nepodržan provider.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Greška pri dobijanju podataka od ' . $provider . '. Probajte ponovo.');
        }

        // Find existing user or create new one
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $this->generateUniqueUsername($socialUser->getName() ?: $socialUser->getNickname() ?: $socialUser->getEmail()),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(32)), // Random password
                'email_verified_at' => now(), // Auto-verify social users
                'avatar' => $socialUser->getAvatar(), // Save avatar if available
            ]);
        } else {
            // Update avatar if not set
            if (!$user->avatar && $socialUser->getAvatar()) {
                $user->update(['avatar' => $socialUser->getAvatar()]);
            }
        }

        // Login user
        Auth::login($user, true); // Remember = true

        return redirect()->route('dashboard')->with('success', 'Uspešno ste se prijavili preko ' . ucfirst($provider) . '!');
    }

    private function generateUniqueUsername($name)
    {
        // Clean the name
        $baseUsername = Str::slug(Str::limit($name, 20), '');
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $baseUsername);
        
        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }
        
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('name', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
