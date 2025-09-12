<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
            Dobrodošli, {{ auth()->user()->name }}!
            {!! auth()->user()->verified_icon !!}
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">Pregled vaših aktivnosti na MyOglasi platformi</p>
        
        <!-- Listing Limit Indicator (for users with payment disabled) -->
        @if(!auth()->user()->payment_enabled && !auth()->user()->is_admin)
            @php
                $activeLimit = \App\Models\Setting::get('monthly_listing_limit', 50);
                $currentActive = $stats['active_listings_count'];
                $percentage = ($currentActive / $activeLimit) * 100;
            @endphp
            <div class="mt-4 p-4 {{ $stats['can_create_listing'] ? 'bg-blue-50 border-blue-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i class="fas fa-chart-pie {{ $stats['can_create_listing'] ? 'text-blue-600' : 'text-red-600' }} mr-2"></i>
                        <span class="font-medium {{ $stats['can_create_listing'] ? 'text-blue-900' : 'text-red-900' }}">
                            Aktivni oglasi: {{ $currentActive }} od {{ $activeLimit }} dostupnih
                        </span>
                    </div>
                    <span class="text-sm {{ $stats['can_create_listing'] ? 'text-blue-700' : 'text-red-700' }} font-medium">
                        {{ $stats['remaining_listings'] }} slobodno
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $percentage >= 100 ? 'bg-red-500' : ($percentage >= 80 ? 'bg-yellow-500' : 'bg-blue-500') }}" 
                         style="width: {{ min($percentage, 100) }}%"></div>
                </div>
                
                <p class="text-xs {{ $stats['can_create_listing'] ? 'text-blue-700' : 'text-red-700' }} mt-2">
                    @if($stats['can_create_listing'])
                        💡 Možete postaviti još {{ $stats['remaining_listings'] }} oglasa. Kada oglas istekne ili se obriše, možete postaviti novi.
                    @else
                        ⚠️ Dostigli ste limit. Obrišite postojeći oglas ili aktivirajte plaćanje za neograničene oglase.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Listings Card -->
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Moji oglasi</p>
                    <p class="text-3xl font-bold">{{ $stats['total_listings'] }}</p>
                    <p class="text-blue-200 text-sm">{{ $stats['active_listings'] }} aktivni</p>
                </div>
                <div class="text-blue-200">
                    <i class="fas fa-list text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('listings.my') }}" class="block mt-4 text-blue-100 hover:text-white text-sm">
                <i class="fas fa-arrow-right mr-1"></i> Upravljaj oglasima
            </a>
        </div>

        <!-- Auctions Card -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Moje aukcije</p>
                    <p class="text-3xl font-bold">{{ $stats['total_auctions'] }}</p>
                    <p class="text-yellow-200 text-sm">{{ $stats['active_auctions'] }} aktivni</p>
                </div>
                <div class="text-yellow-200">
                    <i class="fas fa-gavel text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('auctions.my') }}" class="block mt-4 text-yellow-100 hover:text-white text-sm">
                <i class="fas fa-arrow-right mr-1"></i> Upravljaj aukcijama
            </a>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Balans</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['current_balance'], 0) }}</p>
                    <p class="text-green-200 text-sm">RSD</p>
                </div>
                <div class="text-green-200">
                    <i class="fas fa-wallet text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('balance.index') }}" class="block mt-4 text-green-100 hover:text-white text-sm">
                <i class="fas fa-arrow-right mr-1"></i> Upravljaj balansom
            </a>
        </div>

        <!-- Messages Card -->
        <div class="bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Poruke</p>
                    <p class="text-3xl font-bold">{{ $stats['unread_messages'] }}</p>
                    <p class="text-purple-200 text-sm">nepročitane</p>
                </div>
                <div class="text-purple-200">
                    <i class="fas fa-envelope text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('messages.inbox') }}" class="block mt-4 text-purple-100 hover:text-white text-sm">
                <i class="fas fa-arrow-right mr-1"></i> Otvori poruke
            </a>
        </div>
    </div>

    <!-- Monthly Overview -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Ovaj mesec ({{ Carbon\Carbon::now()->format('F Y') }})
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <i class="fas fa-plus text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Novi oglasi</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $monthlyStats['listings_this_month'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                        <i class="fas fa-gavel text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-yellow-900">Nove aukcije</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $monthlyStats['auctions_this_month'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg mr-3">
                        <i class="fas fa-shopping-cart text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-900">Potrošeno</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($monthlyStats['spent_this_month'], 0) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                        <i class="fas fa-comments text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-900">Poruke</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $monthlyStats['messages_this_month'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-bolt text-orange-600 mr-2"></i>
                Brze akcije
            </h3>
            
            <div class="space-y-3">
                <a href="{{ route('listings.create') }}" 
                   class="flex items-center p-3 {{ $stats['can_create_listing'] ? 'bg-green-50 border-green-200 hover:bg-green-100' : 'bg-red-50 border-red-200' }} border rounded-lg transition-colors">
                    <i class="fas fa-plus {{ $stats['can_create_listing'] ? 'text-green-600' : 'text-red-600' }} mr-3"></i>
                    <div>
                        <div class="font-medium {{ $stats['can_create_listing'] ? 'text-green-900' : 'text-red-900' }}">
                            Postavi novi oglas
                            @if(!auth()->user()->payment_enabled)
                                <span class="text-xs {{ $stats['can_create_listing'] ? 'text-green-600' : 'text-red-600' }}">
                                    ({{ $stats['remaining_listings'] }} ostalo)
                                </span>
                            @endif
                        </div>
                        <div class="text-sm {{ $stats['can_create_listing'] ? 'text-green-700' : 'text-red-700' }}">
                            @if($stats['can_create_listing'])
                                Kreiraj novi oglas za prodaju
                            @else
                                Dostigli ste mesečni limit
                            @endif
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('auctions.index') }}" 
                   class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors">
                    <i class="fas fa-search text-yellow-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-yellow-900">Pretraži aukcije</div>
                        <div class="text-sm text-yellow-700">Pronađi najbolje ponude</div>
                    </div>
                </a>
                
                <a href="{{ route('favorites.index') }}" 
                   class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                    <i class="fas fa-heart text-red-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-red-900">Omiljeni oglasi</div>
                        <div class="text-sm text-red-700">{{ $stats['favorites_count'] }} sačuvanih oglasa</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-clock text-blue-600 mr-2"></i>
                Poslednje aktivnosti
            </h3>
            
            <div class="space-y-3">
                @foreach($activity['recent_listings']->take(3) as $listing)
                    <div class="flex items-center p-2 border-l-4 border-blue-500 bg-blue-50 rounded">
                        <div class="flex-shrink-0 w-10 h-10 mr-3">
                            @if($listing->images->count() > 0)
                                <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}" 
                                     class="w-10 h-10 rounded object-cover">
                            @else
                                <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ Str::limit($listing->title, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $listing->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-sm font-bold text-blue-600">
                            {{ number_format($listing->price, 0) }} RSD
                        </div>
                    </div>
                @endforeach
                
                @if($activity['recent_listings']->count() == 0)
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-list text-gray-400 text-2xl mb-2"></i>
                        <p>Nemate oglase</p>
                    </div>
                @endif
            </div>
            
            @if($activity['recent_listings']->count() > 0)
                <a href="{{ route('listings.my') }}" class="block mt-4 text-blue-600 hover:text-blue-800 text-sm text-center">
                    Vidi sve oglase →
                </a>
            @endif
        </div>

        <!-- Account Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-user text-green-600 mr-2"></i>
                Pregled naloga
            </h3>
            
            <div class="space-y-4">
                <!-- Account Status -->
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Status naloga</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            @if(auth()->user()->isVerified())
                                <span class="text-green-600">✓ Verifikovan</span>
                            @else
                                <span class="text-gray-600 dark:text-gray-300">Standardni</span>
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-shield-alt text-green-600"></i>
                </div>

                <!-- Ratings -->
                @if($stats['total_ratings'] > 0)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">Ocene</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $stats['positive_ratings'] }}/{{ $stats['total_ratings'] }} pozitivnih</div>
                        </div>
                        <i class="fas fa-star text-yellow-500"></i>
                    </div>
                @endif

                <!-- Notifications -->
                @if($stats['unread_notifications'] > 0)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                        <div>
                            <div class="font-medium text-red-900">Obaveštenja</div>
                            <div class="text-sm text-red-700">{{ $stats['unread_notifications'] }} novo</div>
                        </div>
                        <i class="fas fa-bell text-red-600"></i>
                    </div>
                @endif

                <!-- Balance -->
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <div class="font-medium text-green-900">Balans</div>
                        <div class="text-sm text-green-700">{{ number_format($stats['current_balance'], 0) }} RSD</div>
                    </div>
                    <i class="fas fa-coins text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Auctions (if any) -->
    @if($activity['recent_auctions']->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-gavel text-yellow-600 mr-2"></i>
                    Vaše poslednje aukcije
                </h3>
                <a href="{{ route('auctions.my') }}" class="text-yellow-600 hover:text-yellow-800 text-sm">
                    Vidi sve →
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($activity['recent_auctions'] as $auction)
                    <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                        <div class="flex items-center mb-3">
                            @if($auction->listing->images->count() > 0)
                                <img src="{{ $auction->listing->images->first()->url }}" alt="{{ $auction->listing->title }}" 
                                     class="w-12 h-12 rounded object-cover mr-3">
                            @else
                                <div class="w-12 h-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-gavel text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($auction->listing->title, 25) }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $auction->total_bids }} ponuda</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-bold text-yellow-600">
                                {{ number_format($auction->current_price, 0) }} RSD
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">
                                @if($auction->isActive())
                                    Aktivna
                                @else
                                    {{ ucfirst($auction->status) }}
                                @endif
                            </span>
                        </div>
                        
                        <a href="{{ route('auction.show', $auction) }}" 
                           class="block mt-3 text-center px-3 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors text-sm">
                            Pregled aukcije
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Transaction History -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-receipt text-green-600 mr-2"></i>
                Poslednje transakcije
            </h3>
            
            @if($activity['recent_transactions']->count() > 0)
                <div class="space-y-3">
                    @foreach($activity['recent_transactions'] as $transaction)
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                    {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="fas {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'fa-plus' : 'fa-minus' }}"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($transaction->description, 30) }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? '+' : '-' }}{{ number_format(abs($transaction->amount), 0) }} RSD
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <a href="{{ route('balance.index') }}" class="block mt-4 text-green-600 hover:text-green-800 text-sm text-center">
                    Vidi sve transakcije →
                </a>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-receipt text-gray-400 text-3xl mb-2"></i>
                    <p>Nema transakcija</p>
                </div>
            @endif
        </div>

        <!-- Achievements & Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                Vaši uspesi
            </h3>
            
            <div class="space-y-4">
                <!-- Auction Wins -->
                @if($stats['won_auctions'] > 0)
                    <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <i class="fas fa-crown text-yellow-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-yellow-900">Pobede na aukcijama</div>
                            <div class="text-sm text-yellow-700">{{ $stats['won_auctions'] }} pobedničkih aukcija</div>
                        </div>
                    </div>
                @endif

                <!-- Ratings Achievement -->
                @if($stats['total_ratings'] > 0)
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <i class="fas fa-thumbs-up text-green-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-green-900">Pozitivne ocene</div>
                            <div class="text-sm text-green-700">{{ round(($stats['positive_ratings'] / $stats['total_ratings']) * 100) }}% pozitivnih ocena</div>
                        </div>
                    </div>
                @endif

                <!-- Account Age -->
                <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <i class="fas fa-calendar text-blue-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-blue-900">Član od</div>
                        <div class="text-sm text-blue-700">{{ auth()->user()->created_at->format('F Y') }}</div>
                    </div>
                </div>

                <!-- Verification Status -->
                <div class="flex items-center p-3 {{ auth()->user()->isVerified() ? 'bg-green-50 border-green-200' : 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600' }} border rounded-lg">
                    <i class="fas fa-shield-check {{ auth()->user()->isVerified() ? 'text-green-600' : 'text-gray-400' }} mr-3"></i>
                    <div>
                        <div class="font-medium {{ auth()->user()->isVerified() ? 'text-green-900' : 'text-gray-700 dark:text-gray-200' }}">Verifikacija</div>
                        <div class="text-sm {{ auth()->user()->isVerified() ? 'text-green-700' : 'text-gray-500' }}">
                            {{ auth()->user()->verification_status_text }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
