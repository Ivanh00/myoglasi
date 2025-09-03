<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is banned
            if ($user && $user->is_banned) {
                // Log out the user immediately
                Auth::logout();
                
                // Invalidate the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect with error message
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Vaš nalog je blokiran. Molimo kontaktirajte administratora na admin@myoglasi.com za više informacija.'
                    ]);
            }
        }

        return $next($request);
    }
}
