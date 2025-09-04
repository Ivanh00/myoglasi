<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckUserCanCreateListing
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        // If user is banned, redirect them
        if ($user->is_banned) {
            return redirect()->route('home')->withErrors(['error' => 'Ne možete postavljati oglase jer je vaš nalog blokiran.']);
        }
        
        // If payment is disabled or user has active plan, allow
        if (!$user->payment_enabled || $user->hasActivePlan()) {
            return $next($request);
        }
        
        // Check if user can create listing for free
        if ($user->canCreateListingForFree()) {
            return $next($request);
        }
        
        // Check if user has enough balance for per-listing payment
        if ($user->payment_plan === 'per_listing') {
            $fee = Setting::get('listing_fee_amount', 10);
            if ($user->balance < $fee) {
                session()->flash('error', 'Nemate dovoljno kredita za postavljanje oglasa. Potrebno: ' . number_format($fee, 0, ',', '.') . ' RSD, a imate: ' . number_format($user->balance, 0, ',', '.') . ' RSD');
                return redirect()->route('balance.payment-options');
            }
        }
        
        return $next($request);
    }
}