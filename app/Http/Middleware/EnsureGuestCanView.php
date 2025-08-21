<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestCanView
{
    public function handle($request, Closure $next)
    {
        // Dozvoli pristup listing detail svima
        if ($request->routeIs('listings.show') || 
            $request->routeIs('home') || 
            $request->routeIs('category.show') ||
            $request->routeIs('search')) {
            return $next($request);
        }
        
        // Zahtevaj autentifikaciju za ostale rute
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Potrebna je registracija za ovu funkcionalnost.');
        }
        
        return $next($request);
    }
}
