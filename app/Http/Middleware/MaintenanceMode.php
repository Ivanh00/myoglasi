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
            
            // Allow access to maintenance page itself and logout
            if ($request->routeIs('maintenance') || 
                $request->routeIs('logout') || 
                $request->is('logout')) {
                return $next($request);
            }
            
            // Allow API routes that might be needed for logout
            if ($request->is('api/*')) {
                return $next($request);
            }
            
            // Redirect all other users to maintenance page
            return redirect()->route('maintenance');
        }
        
        return $next($request);
    }
}