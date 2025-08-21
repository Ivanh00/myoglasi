<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Ne zahteva autentifikaciju, ali je dodeli ako postoji
        if (auth()->check()) {
            $request->merge(['user' => auth()->user()]);
        }
        
        return $next($request);
    }
}
