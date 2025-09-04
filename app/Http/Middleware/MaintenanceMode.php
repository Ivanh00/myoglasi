<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = Setting::get('maintenance_mode', false);
        
        if ($maintenanceMode) {
            // Allow access for admins
            if (auth()->check() && auth()->user()->is_admin) {
                return $next($request);
            }
            
            // Allow logout functionality
            if ($request->routeIs('logout') || 
                $request->is('logout') ||
                $request->method() === 'POST' && $request->is('logout')) {
                return $next($request);
            }
            
            // Allow API routes that might be needed
            if ($request->is('api/*') || $request->is('livewire/*')) {
                return $next($request);
            }
            
            // Return maintenance view without redirecting (preserves URL)
            return response()->view('maintenance', [], 503);
        }
        
        return $next($request);
    }
}