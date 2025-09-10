<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\IpBlock;
use App\Models\VisitorLog;
use App\Models\Setting;

class FirewallMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        
        // Log visitor activity
        if (Setting::get('visitor_logging_enabled', true)) {
            VisitorLog::logVisitor($ip, $userAgent, auth()->id());
        }
        
        // Check if IP is blocked
        if (IpBlock::isBlocked($ip)) {
            abort(403, 'Vaš pristup je blokiran zbog narušavanja pravila korišćenja.');
        }
        
        // Check admin whitelist if required
        if ($request->routeIs('admin.*') && Setting::get('require_admin_whitelist', false)) {
            if (!IpBlock::isWhitelisted($ip)) {
                abort(403, 'Pristup admin panelu je ograničen na odobrene IP adrese.');
            }
        }
        
        // Check rate limiting
        if (Setting::get('rate_limit_enabled', true)) {
            $this->checkRateLimit($ip);
        }
        
        // Check blocked countries
        if (Setting::get('geo_blocking_enabled', false)) {
            $this->checkGeoBlocking($ip);
        }
        
        // Check blocked user agents
        if (Setting::get('user_agent_blocking_enabled', false)) {
            $this->checkUserAgent($userAgent);
        }

        return $next($request);
    }
    
    protected function checkRateLimit($ip)
    {
        $visitor = VisitorLog::where('ip_address', $ip)->first();
        
        if (!$visitor) return;
        
        $maxPerMinute = Setting::get('rate_limit_per_minute', 60);
        $maxPerHour = Setting::get('rate_limit_per_hour', 1000);
        
        // Check requests in last minute
        if ($visitor->request_count > $maxPerMinute && $visitor->last_activity > now()->subMinute()) {
            abort(429, 'Previše zahteva. Molimo sačekajte pre ponovnog pristupa.');
        }
        
        // Check requests in last hour
        if ($visitor->request_count > $maxPerHour && $visitor->last_activity > now()->subHour()) {
            abort(429, 'Dnevni limit zahteva je dostignut. Molimo pokušajte sutra.');
        }
    }
    
    protected function checkGeoBlocking($ip)
    {
        $visitor = VisitorLog::where('ip_address', $ip)->first();
        
        if (!$visitor || !$visitor->country_code) return;
        
        $blockedCountries = json_decode(Setting::get('blocked_countries', '[]'), true);
        
        if (in_array($visitor->country_code, $blockedCountries)) {
            abort(403, 'Pristup sa vaše lokacije je blokiran.');
        }
    }
    
    protected function checkUserAgent($userAgent)
    {
        if (!$userAgent) return;
        
        $blockedAgents = json_decode(Setting::get('blocked_user_agents', '[]'), true);
        
        foreach ($blockedAgents as $blockedAgent) {
            if (stripos($userAgent, $blockedAgent) !== false) {
                abort(403, 'Vaš browser/aplikacija je blokirana.');
            }
        }
    }
}
