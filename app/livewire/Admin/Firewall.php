<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IpBlock;
use App\Models\VisitorLog;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Http;

class Firewall extends Component
{
    use WithPagination;

    public $activeTab = 'overview';
    public $search = '';
    public $perPage = 20;
    
    // IP Block Management
    public $newIpBlock = [
        'ip_address' => '',
        'ip_range_start' => '',
        'ip_range_end' => '',
        'type' => 'single', // single, range, whitelist
        'reason' => '',
        'duration' => 'permanent', // temporary, permanent
        'expires_at' => null
    ];
    
    // Rate Limiting Settings
    public $rateLimitSettings = [
        'enabled' => true,
        'max_requests_per_minute' => 60,
        'max_requests_per_hour' => 1000,
        'auto_block_enabled' => true,
        'auto_block_threshold' => 100,
        'auto_block_duration' => 24, // hours
        'login_attempt_limit' => 5,
        'login_block_duration' => 30 // minutes
    ];
    
    // Security Settings
    public $securitySettings = [
        'captcha_enabled' => false,
        'geo_blocking_enabled' => false,
        'blocked_countries' => [],
        'user_agent_blocking_enabled' => false,
        'blocked_user_agents' => [],
        'require_admin_whitelist' => false
    ];

    public $showAddIpModal = false;
    public $editingIpBlock = null;

    protected $listeners = ['refreshFirewall' => '$refresh'];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Rate Limiting Settings
        $this->rateLimitSettings = [
            'enabled' => Setting::get('rate_limit_enabled', true),
            'max_requests_per_minute' => Setting::get('rate_limit_per_minute', 60),
            'max_requests_per_hour' => Setting::get('rate_limit_per_hour', 1000),
            'auto_block_enabled' => Setting::get('auto_block_enabled', true),
            'auto_block_threshold' => Setting::get('auto_block_threshold', 100),
            'auto_block_duration' => Setting::get('auto_block_duration', 24),
            'login_attempt_limit' => Setting::get('login_attempt_limit', 5),
            'login_block_duration' => Setting::get('login_block_duration', 30)
        ];

        // Security Settings
        $this->securitySettings = [
            'captcha_enabled' => Setting::get('captcha_enabled', false),
            'geo_blocking_enabled' => Setting::get('geo_blocking_enabled', false),
            'blocked_countries' => json_decode(Setting::get('blocked_countries', '[]'), true),
            'user_agent_blocking_enabled' => Setting::get('user_agent_blocking_enabled', false),
            'blocked_user_agents' => json_decode(Setting::get('blocked_user_agents', '[]'), true),
            'require_admin_whitelist' => Setting::get('require_admin_whitelist', false)
        ];
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function addIpBlock()
    {
        $this->validate([
            'newIpBlock.ip_address' => 'required_if:newIpBlock.type,single|nullable|ip',
            'newIpBlock.ip_range_start' => 'required_if:newIpBlock.type,range|nullable|ip',
            'newIpBlock.ip_range_end' => 'required_if:newIpBlock.type,range|nullable|ip',
            'newIpBlock.reason' => 'required|string|max:255',
            'newIpBlock.duration' => 'required|in:temporary,permanent',
            'newIpBlock.expires_at' => 'required_if:newIpBlock.duration,temporary|nullable|date|after:now'
        ]);

        IpBlock::create([
            'ip_address' => $this->newIpBlock['ip_address'],
            'ip_range_start' => $this->newIpBlock['ip_range_start'],
            'ip_range_end' => $this->newIpBlock['ip_range_end'],
            'type' => $this->newIpBlock['type'],
            'action' => $this->newIpBlock['type'] === 'whitelist' ? 'allow' : 'block',
            'reason' => $this->newIpBlock['reason'],
            'expires_at' => $this->newIpBlock['duration'] === 'temporary' ? $this->newIpBlock['expires_at'] : null,
            'created_by' => auth()->id(),
            'is_active' => true
        ]);

        $this->newIpBlock = [
            'ip_address' => '',
            'ip_range_start' => '',
            'ip_range_end' => '',
            'type' => 'single',
            'reason' => '',
            'duration' => 'permanent',
            'expires_at' => null
        ];

        $this->showAddIpModal = false;
        $this->dispatch('notify', type: 'success', message: 'IP adresa je uspešno dodana u firewall!');
    }

    public function removeIpBlock($id)
    {
        $ipBlock = IpBlock::find($id);
        if ($ipBlock) {
            $ipBlock->update(['is_active' => false]);
            $this->dispatch('notify', type: 'success', message: 'IP blok je uspešno uklonjen!');
        }
    }

    public function saveRateLimitSettings()
    {
        $this->validate([
            'rateLimitSettings.max_requests_per_minute' => 'required|integer|min:1|max:1000',
            'rateLimitSettings.max_requests_per_hour' => 'required|integer|min:100|max:10000',
            'rateLimitSettings.auto_block_threshold' => 'required|integer|min:10|max:1000',
            'rateLimitSettings.auto_block_duration' => 'required|integer|min:1|max:168',
            'rateLimitSettings.login_attempt_limit' => 'required|integer|min:3|max:20',
            'rateLimitSettings.login_block_duration' => 'required|integer|min:5|max:1440'
        ]);

        foreach ($this->rateLimitSettings as $key => $value) {
            Setting::set('rate_limit_' . str_replace('_', '_', $key), $value, 
                is_bool($value) ? 'boolean' : (is_array($value) ? 'json' : 'string'), 'firewall');
        }

        session()->flash('success', 'Rate limiting podešavanja su uspešno sačuvana.');
    }

    public function saveSecuritySettings()
    {
        foreach ($this->securitySettings as $key => $value) {
            Setting::set($key, 
                is_array($value) ? json_encode($value) : $value,
                is_bool($value) ? 'boolean' : (is_array($value) ? 'json' : 'string'), 'security');
        }

        session()->flash('success', 'Bezbednosna podešavanja su uspešno sačuvana.');
    }

    public function getVisitorStats()
    {
        return [
            'active_visitors' => VisitorLog::where('last_activity', '>', now()->subMinutes(5))->distinct('ip_address')->count(),
            'today_visitors' => VisitorLog::whereDate('first_visit', today())->distinct('ip_address')->count(),
            'total_requests_today' => VisitorLog::whereDate('last_activity', today())->sum('request_count'),
            'blocked_attempts_today' => IpBlock::where('created_at', '>', today())->where('action', 'block')->count(),
            'top_countries' => VisitorLog::select('country', \DB::raw('COUNT(DISTINCT ip_address) as visitor_count'))
                ->whereDate('last_activity', today())
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('visitor_count', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    public function render()
    {
        $data = [];

        switch ($this->activeTab) {
            case 'overview':
                $data['stats'] = $this->getVisitorStats();
                $data['recent_blocks'] = IpBlock::with('creator')
                    ->where('is_active', true)
                    ->latest()
                    ->limit(10)
                    ->get();
                break;

            case 'ip_management':
                $data['ip_blocks'] = IpBlock::with('creator')
                    ->when($this->search, function ($query) {
                        $query->where('ip_address', 'like', '%' . $this->search . '%')
                              ->orWhere('reason', 'like', '%' . $this->search . '%');
                    })
                    ->where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->paginate($this->perPage);
                break;

            case 'visitor_logs':
                $data['visitors'] = VisitorLog::when($this->search, function ($query) {
                        $query->where('ip_address', 'like', '%' . $this->search . '%')
                              ->orWhere('user_agent', 'like', '%' . $this->search . '%')
                              ->orWhere('country', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('last_activity', 'desc')
                    ->paginate($this->perPage);
                break;

            case 'security_settings':
                // Settings already loaded in mount
                break;
        }

        return view('livewire.admin.firewall', $data)
            ->layout('layouts.admin');
    }
}