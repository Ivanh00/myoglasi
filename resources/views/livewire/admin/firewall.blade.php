<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            <i class="fas fa-shield-alt mr-3 text-red-600"></i>
            Firewall & Bezbednost
        </h2>
        
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="switchTab('overview')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-chart-line mr-1"></i>
                    Pregled
                </button>
                <button wire:click="switchTab('ip_management')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'ip_management' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-ban mr-1"></i>
                    IP Upravljanje
                </button>
                <button wire:click="switchTab('visitor_logs')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'visitor_logs' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-users mr-1"></i>
                    Posetioci
                </button>
                <button wire:click="switchTab('security_settings')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'security_settings' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-cog mr-1"></i>
                    Podešavanja
                </button>
            </nav>
        </div>

        <!-- Overview Tab -->
        @if($activeTab === 'overview')
            <div class="space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-users text-3xl opacity-80"></i>
                            <div class="ml-4">
                                <p class="text-green-100 text-sm">Aktivni posetioci</p>
                                <p class="text-2xl font-bold">{{ $stats['active_visitors'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day text-3xl opacity-80"></i>
                            <div class="ml-4">
                                <p class="text-blue-100 text-sm">Posetioci danas</p>
                                <p class="text-2xl font-bold">{{ $stats['today_visitors'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-mouse-pointer text-3xl opacity-80"></i>
                            <div class="ml-4">
                                <p class="text-purple-100 text-sm">Zahtevi danas</p>
                                <p class="text-2xl font-bold">{{ number_format($stats['total_requests_today'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-3xl opacity-80"></i>
                            <div class="ml-4">
                                <p class="text-red-100 text-sm">Blokirano danas</p>
                                <p class="text-2xl font-bold">{{ $stats['blocked_attempts_today'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Countries -->
                @if(isset($stats['top_countries']) && $stats['top_countries']->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-globe mr-2 text-blue-600"></i>
                            Top zemlje danas
                        </h3>
                        <div class="space-y-3">
                            @foreach($stats['top_countries'] as $country)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🌍</span>
                                        <span class="font-medium">{{ $country->country }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $country->visitor_count }} posetioca</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Blocks -->
                @if(isset($recent_blocks) && $recent_blocks->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-ban mr-2 text-red-600"></i>
                            Nedavno blokirane IP adrese
                        </h3>
                        <div class="space-y-3">
                            @foreach($recent_blocks as $block)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <div>
                                        <span class="font-mono text-sm">{{ $block->ip_address }}</span>
                                        <span class="text-xs text-gray-500 ml-2">{{ $block->reason }}</span>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $block->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- IP Management Tab -->
        @if($activeTab === 'ip_management')
            <div class="space-y-6">
                <!-- Add IP Block -->
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">IP Adrese</h3>
                        <p class="text-sm text-gray-600">Upravljanje blokiranim i whitelisted IP adresama</p>
                    </div>
                    <button wire:click="$set('showAddIpModal', true)" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Dodaj IP blok
                    </button>
                </div>

                <!-- Search -->
                <div class="flex items-center space-x-4">
                    <input type="text" wire:model.live="search" placeholder="Pretraži IP adrese ili razlog..." 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="20">20 po strani</option>
                        <option value="50">50 po strani</option>
                        <option value="100">100 po strani</option>
                    </select>
                </div>

                <!-- IP Blocks Table -->
                @if(isset($ip_blocks) && $ip_blocks->count() > 0)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Adresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tip</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Razlog</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kreirao</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akcije</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($ip_blocks as $block)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">
                                                @if($block->type === 'range')
                                                    {{ $block->ip_range_start }} - {{ $block->ip_range_end }}
                                                @else
                                                    {{ $block->ip_address }}
                                                @endif
                                            </code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $block->action === 'allow' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $block->type_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ Str::limit($block->reason, 50) }}</div>
                                            @if($block->auto_generated)
                                                <div class="text-xs text-blue-600">Automatsko</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">{{ $block->status_text }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $block->creator->name ?? 'System' }}</div>
                                            <div class="text-xs text-gray-500">{{ $block->created_at->format('d.m.Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button wire:click="removeIpBlock({{ $block->id }})" 
                                                onclick="return confirm('Da li ste sigurni da želite da uklonite ovaj IP blok?')"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $ip_blocks->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <i class="fas fa-shield-alt text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema blokova</h3>
                        <p class="text-gray-600">Dodajte IP adrese koje želite da blokirate ili dozvolite.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Visitor Logs Tab -->
        @if($activeTab === 'visitor_logs')
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Istorija poseta</h3>
                        <p class="text-sm text-gray-600">Praćenje aktivnosti posetilaca</p>
                    </div>
                </div>

                <!-- Search -->
                <div class="flex items-center space-x-4">
                    <input type="text" wire:model.live="search" placeholder="Pretraži po IP, User Agent, zemlji..." 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="20">20 po strani</option>
                        <option value="50">50 po strani</option>
                        <option value="100">100 po strani</option>
                    </select>
                </div>

                <!-- Visitors Table -->
                @if(isset($visitors) && $visitors->count() > 0)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP & Lokacija</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User Agent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivnost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akcije</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($visitors as $visitor)
                                    <tr class="hover:bg-gray-50 {{ $visitor->is_suspicious ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4">
                                            <div>
                                                <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $visitor->ip_address }}</code>
                                                @if($visitor->country)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $visitor->country_flag }} {{ $visitor->country }}
                                                        @if($visitor->city), {{ $visitor->city }}@endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $visitor->user_agent }}">
                                                {{ Str::limit($visitor->user_agent, 40) }}
                                            </div>
                                            @if($visitor->is_bot)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Bot
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ number_format($visitor->request_count) }} zahteva</div>
                                            <div class="text-xs text-gray-500">
                                                Prva: {{ $visitor->first_visit->format('d.m.Y H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Poslednja: {{ $visitor->last_activity->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($visitor->is_suspicious)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Sumnjivo
                                                </span>
                                            @elseif($visitor->last_activity > now()->subMinutes(5))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Online
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="blockIp('{{ $visitor->ip_address }}')" 
                                                onclick="return confirm('Da li želite da blokirate IP {{ $visitor->ip_address }}?')"
                                                class="text-red-600 hover:text-red-900 mr-2">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $visitors->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <i class="fas fa-users text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema podataka o posetiocima</h3>
                        <p class="text-gray-600">Podaci o posetiocima će se prikazati kada se funkcija aktivira.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Security Settings Tab -->
        @if($activeTab === 'security_settings')
            <div class="space-y-6">
                <!-- Rate Limiting -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-tachometer-alt mr-2 text-orange-600"></i>
                        Rate Limiting
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" wire:model="rateLimitSettings.enabled" 
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Omogući rate limiting</span>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Guest User Limits -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-user text-gray-600 mr-2"></i>
                                    Guest korisnici (neulogovani)
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Zahteva po minutu</label>
                                        <input type="number" wire:model="rateLimitSettings.guest_per_minute" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <p class="text-xs text-gray-500 mt-1">Preporučeno: 20-50</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Zahteva po satu</label>
                                        <input type="number" wire:model="rateLimitSettings.guest_per_hour" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <p class="text-xs text-gray-500 mt-1">Preporučeno: 300-1000</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Authenticated User Limits -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-user-check text-green-600 mr-2"></i>
                                    Registrovani korisnici
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Zahteva po minutu</label>
                                        <input type="number" wire:model="rateLimitSettings.auth_per_minute" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <p class="text-xs text-gray-500 mt-1">Preporučeno: 100-200 (više zbog upload-a)</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Zahteva po satu</label>
                                        <input type="number" wire:model="rateLimitSettings.auth_per_hour" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <p class="text-xs text-gray-500 mt-1">Preporučeno: 1500-3000 (više zbog intenzivnog korišćenja)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                                <div class="text-sm text-blue-800">
                                    <strong>Napomena:</strong> Registrovani korisnici imaju veće limite jer koriste funkcije kao što su upload slika, kreiranje oglasa, i česta navigacija kroz sajt. Administratori su potpuno oslobođeni ograničenja.
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" wire:model="rateLimitSettings.auto_block_enabled" 
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Automatsko blokiranje</span>
                            </label>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prag za blokiranje</label>
                                    <input type="number" wire:model="rateLimitSettings.auto_block_threshold" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Broj zahteva pre automatskog blokiranja</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Trajanje bloka (sati)</label>
                                    <input type="number" wire:model="rateLimitSettings.auto_block_duration" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Login Protection -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Zaštita od brute force napada</h4>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Limit pokušaja prijavljivanja</label>
                                <input type="number" wire:model="rateLimitSettings.login_attempt_limit" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Trajanje bloka (minuti)</label>
                                <input type="number" wire:model="rateLimitSettings.login_block_duration" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button wire:click="saveRateLimitSettings" 
                            class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Sačuvaj rate limiting
                        </button>
                    </div>
                </div>

                <!-- Security Features -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>
                        Bezbednosne funkcije
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- CAPTCHA -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">CAPTCHA zaštita</h4>
                                <p class="text-sm text-gray-600">Omogući CAPTCHA za sumnjive korisnike</p>
                            </div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="securitySettings.captcha_enabled" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                <span class="ml-2 text-sm text-gray-700">
                                    {{ $securitySettings['captcha_enabled'] ? 'Omogućeno' : 'Onemogućeno' }}
                                </span>
                            </label>
                        </div>

                        <!-- Geo Blocking -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">Geografsko blokiranje</h4>
                                    <p class="text-sm text-gray-600">Blokiraj pristup iz određenih zemalja</p>
                                </div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="securitySettings.geo_blocking_enabled" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-700">
                                        {{ $securitySettings['geo_blocking_enabled'] ? 'Omogućeno' : 'Onemogućeno' }}
                                    </span>
                                </label>
                            </div>
                            
                            @if($securitySettings['geo_blocking_enabled'])
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Blokirane zemlje (kodovi)</label>
                                        <div class="flex gap-2">
                                            <input type="text" wire:model="blockedCountriesInput" 
                                                placeholder="CN,RU,KP (odvojeno zarezima)"
                                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <button wire:click="addBlockedCountry" 
                                                class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Unesite dvoslovne kodove zemalja (npr: CN za Kinu, RU za Rusiju)</p>
                                    </div>
                                    
                                    @if(!empty($securitySettings['blocked_countries']))
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 mb-2">Trenutno blokirane zemlje:</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($securitySettings['blocked_countries'] as $country)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                                        {{ $country }}
                                                        <button wire:click="removeBlockedCountry('{{ $country }}')" class="ml-1 text-red-600 hover:text-red-800">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- User Agent Blocking -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">Blokiranje User Agent-a</h4>
                                    <p class="text-sm text-gray-600">Blokiraj određene botove i crawlere</p>
                                </div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="securitySettings.user_agent_blocking_enabled" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-700">
                                        {{ $securitySettings['user_agent_blocking_enabled'] ? 'Omogućeno' : 'Onemogućeno' }}
                                    </span>
                                </label>
                            </div>
                            
                            @if($securitySettings['user_agent_blocking_enabled'])
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Novi User Agent za blokiranje</label>
                                        <div class="flex gap-2">
                                            <input type="text" wire:model="newUserAgent" 
                                                placeholder="Chrome/Bot/Crawler"
                                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <button wire:click="addBlockedUserAgent" 
                                                class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Deo User Agent string-a koji se traži</p>
                                    </div>
                                    
                                    @if(!empty($securitySettings['blocked_user_agents']))
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 mb-2">Blokirani User Agent-i:</p>
                                            <div class="space-y-2">
                                                @foreach($securitySettings['blocked_user_agents'] as $agent)
                                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                                        <code class="text-xs">{{ $agent }}</code>
                                                        <button wire:click="removeBlockedUserAgent('{{ $agent }}')" class="text-red-600 hover:text-red-800">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Admin Whitelist -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Admin panel whitelist</h4>
                                <p class="text-sm text-gray-600">Ograniči pristup admin panelu samo na whitelisted IP adrese</p>
                            </div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="securitySettings.require_admin_whitelist" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                <span class="ml-2 text-sm text-gray-700">
                                    {{ $securitySettings['require_admin_whitelist'] ? 'Omogućeno' : 'Onemogućeno' }}
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button wire:click="saveSecuritySettings" 
                            class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Sačuvaj bezbednosna podešavanja
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add IP Block Modal -->
    @if($showAddIpModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dodaj IP blok</h3>
                    
                    <form wire:submit.prevent="addIpBlock">
                        <div class="space-y-4">
                            <!-- Block Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tip bloka</label>
                                <select wire:model="newIpBlock.type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="single">Pojedinačna IP adresa</option>
                                    <option value="range">Opseg IP adresa</option>
                                    <option value="whitelist">Whitelist (dozvoli)</option>
                                </select>
                            </div>

                            <!-- Single IP -->
                            @if($newIpBlock['type'] === 'single' || $newIpBlock['type'] === 'whitelist')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">IP adresa</label>
                                    <input type="text" wire:model="newIpBlock.ip_address" placeholder="192.168.1.1" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    @error('newIpBlock.ip_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <!-- IP Range -->
                            @if($newIpBlock['type'] === 'range')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Od IP</label>
                                        <input type="text" wire:model="newIpBlock.ip_range_start" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        @error('newIpBlock.ip_range_start') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Do IP</label>
                                        <input type="text" wire:model="newIpBlock.ip_range_end" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        @error('newIpBlock.ip_range_end') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            <!-- Reason -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Razlog</label>
                                <input type="text" wire:model="newIpBlock.reason" placeholder="Spam, napad, itd." 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('newIpBlock.reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Duration -->
                            @if($newIpBlock['type'] !== 'whitelist')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Trajanje</label>
                                    <select wire:model="newIpBlock.duration" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="permanent">Trajno</option>
                                        <option value="temporary">Privremeno</option>
                                    </select>
                                </div>

                                @if($newIpBlock['duration'] === 'temporary')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Ističe</label>
                                        <input type="datetime-local" wire:model="newIpBlock.expires_at" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        @error('newIpBlock.expires_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="flex items-center justify-end space-x-3 mt-6">
                            <button type="button" wire:click="$set('showAddIpModal', false)"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Otkaži
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Dodaj blok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>