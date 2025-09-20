<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
            Dobrodošli, {{ auth()->user()->name }}!
            {!! auth()->user()->verified_icon !!}
        </h1>

        <!-- Listing Limit Indicator (for users with payment disabled) -->
        @if (!auth()->user()->payment_enabled && !auth()->user()->is_admin)
            @php
                $activeLimit = \App\Models\Setting::get('monthly_listing_limit', 50);
                $currentActive = $stats['active_listings_count'];
                $percentage = ($currentActive / $activeLimit) * 100;
            @endphp
            <div
                class="mt-4 p-4 {{ $stats['can_create_listing'] ? 'bg-sky-100 dark:bg-sky-900 border-sky-300 dark:border-sky-700' : 'bg-red-100 dark:bg-red-900 border-red-300 dark:border-red-700' }} border rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i
                            class="fas fa-chart-pie {{ $stats['can_create_listing'] ? 'text-sky-600 dark:text-sky-400 ' : 'text-red-600 dark:text-red-400 ' }} mr-2"></i>
                        <span
                            class="font-medium {{ $stats['can_create_listing'] ? 'text-sky-900 dark:text-sky-200' : 'text-red-900 dark:text-red-200' }}">
                            Aktivni oglasi: {{ $currentActive }} od {{ $activeLimit }} dostupnih
                        </span>
                    </div>
                    <span
                        class="text-sm {{ $stats['can_create_listing'] ? 'text-sky-700 dark:text-sky-200' : 'text-red-700 dark:text-red-200' }} font-medium">
                        {{ $stats['remaining_listings'] }} slobodno
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-slate-200 dark:bg-slate-600 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $percentage >= 100 ? 'bg-red-600' : ($percentage >= 80 ? 'bg-amber-500' : 'bg-sky-600') }}"
                        style="width: {{ min($percentage, 100) }}%"></div>
                </div>

                <p
                    class="text-xs {{ $stats['can_create_listing'] ? 'text-sky-700 dark:text-sky-200' : 'text-red-700 dark:text-red-200' }} mt-2">
                    @if ($stats['can_create_listing'])
                        💡 Možete postaviti još {{ $stats['remaining_listings'] }} oglasa. Kada oglas istekne ili se
                        obriše, možete postaviti novi.
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
        <div class="bg-gradient-to-r from-sky-400 to-sky-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sky-100 text-sm">Moji oglasi</p>
                    <p class="text-3xl font-bold">{{ $stats['total_listings'] }}</p>
                    <p class="text-sky-200 text-sm">{{ $stats['active_listings'] }} aktivni</p>
                </div>
                <div class="text-sky-200">
                    <i class="fas fa-list text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('listings.my') }}" class="block mt-4 text-sky-100 hover:text-white text-sm">
                <i class="fas fa-arrow-right mr-1"></i> Upravljaj oglasima
            </a>
        </div>

        <!-- Auctions Card -->
        <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm">Moje aukcije</p>
                    <p class="text-3xl font-bold">{{ $stats['total_auctions'] }}</p>
                    <p class="text-amber-200 text-sm">{{ $stats['active_auctions'] }} aktivni</p>
                </div>
                <div class="text-amber-200">
                    <i class="fas fa-gavel text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('auctions.my') }}" class="block mt-4 text-amber-100 hover:text-white text-sm">
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
                    <p class="text-3xl font-bold text-white">{{ $stats['unread_messages'] }}</p>
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
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">
            <i class="fas fa-chart-line text-sky-600 dark:text-sky-400 mr-2"></i>
            Ovaj mesec ({{ Carbon\Carbon::now()->format('F Y') }})
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-sky-100 dark:bg-sky-900 p-4 rounded-lg border border-sky-200 dark:border-sky-200">
                <div class="flex items-center">
                    <div class="p-2 bg-sky-100 dark:bg-sky-700 rounded-lg mr-3">
                        <i class="fas fa-plus text-sky-600 dark:text-sky-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-sky-900 dark:text-sky-200">Novi oglasi</p>
                        <p class="text-2xl font-bold text-sky-700 dark:text-sky-200">
                            {{ $monthlyStats['listings_this_month'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-100 dark:bg-amber-900 p-4 rounded-lg border border-amber-200 dark:border-amber-200">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 dark:bg-amber-700 rounded-lg mr-3">
                        <i class="fas fa-gavel text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-amber-900 dark:text-amber-200">Nove aukcije</p>
                        <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">
                            {{ $monthlyStats['auctions_this_month'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg border border-red-200 dark:border-red-200">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-700 rounded-lg mr-3">
                        <i class="fas fa-shopping-cart text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-900 dark:text-red-200">Potrošeno</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-200">
                            {{ number_format($monthlyStats['spent_this_month'], 0) }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg border border-purple-200 dark:border-purple-200">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-700 rounded-lg mr-3">
                        <i class="fas fa-comments text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-900 dark:text-purple-200">Poruke</p>
                        <p class="text-2xl font-bold text-purple-800 dark:text-purple-200">
                            {{ $monthlyStats['messages_this_month'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-bolt text-orange-600 mr-2"></i>
                Brze akcije
            </h3>

            <div class="space-y-3">
                <a href="{{ route('listings.create') }}"
                    class="flex items-center p-3 {{ $stats['can_create_listing'] ? 'bg-green-100 dark:bg-green-900 border-green-300 dark:border-green-700 hover:bg-green-100' : 'bg-red-100 dark:bg-red-900 border-red-300 dark:border-red-700' }} border rounded-lg transition-colors">
                    <i
                        class="fas fa-plus {{ $stats['can_create_listing'] ? 'text-green-600 dark:text-green-400 ' : 'text-red-600 dark:text-red-400 ' }} mr-3"></i>
                    <div>
                        <div
                            class="font-medium {{ $stats['can_create_listing'] ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-200' }}">
                            Postavi novi oglas
                            @if (!auth()->user()->payment_enabled)
                                <span
                                    class="text-xs {{ $stats['can_create_listing'] ? 'text-green-600 dark:text-green-400 ' : 'text-red-600 dark:text-red-400' }}">
                                    ({{ $stats['remaining_listings'] }} ostalo)
                                </span>
                            @endif
                        </div>
                        <div
                            class="text-sm {{ $stats['can_create_listing'] ? 'text-green-700 dark:text-green-200' : 'text-red-700 dark:text-red-200' }}">
                            @if ($stats['can_create_listing'])
                                Kreiraj novi oglas za prodaju
                            @else
                                Dostigli ste mesečni limit
                            @endif
                        </div>
                    </div>
                </a>

                <a href="{{ route('auctions.index') }}"
                    class="flex items-center p-3 bg-amber-100 dark:bg-amber-900 border border-amber-300 dark:border-amber-700 rounded-lg hover:bg-amber-100 transition-colors">
                    <i class="fas fa-search text-amber-600 dark:text-amber-400 mr-3"></i>
                    <div>
                        <div class="font-medium text-amber-900 dark:text-amber-100">Pretraži aukcije</div>
                        <div class="text-sm text-amber-700 dark:text-amber-200">Pronađi najbolje ponude</div>
                    </div>
                </a>

                <a href="{{ route('favorites.index') }}"
                    class="flex items-center p-3 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-lg hover:bg-red-100 transition-colors">
                    <i class="fas fa-heart text-red-600 dark:text-red-400  mr-3"></i>
                    <div>
                        <div class="font-medium text-red-900 dark:text-red-200">Omiljeni oglasi</div>
                        <div class="text-sm text-red-700 dark:text-red-200">{{ $stats['favorites_count'] }} sačuvanih
                            oglasa</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-clock text-sky-600 dark:text-sky-400 mr-2"></i>
                Poslednje aktivnosti
            </h3>

            <div class="space-y-3">
                @foreach ($activity['recent_listings']->take(3) as $listing)
                    <div class="flex items-center p-2 border-l-4 border-sky-500 bg-sky-100 dark:bg-sky-900 rounded">
                        <div class="flex-shrink-0 w-10 h-10 mr-3">
                            @if ($listing->images->count() > 0)
                                <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                    class="w-10 h-10 rounded object-cover">
                            @else
                                <div class="w-10 h-10 rounded bg-slate-200 flex items-center justify-center">
                                    <i class="fas fa-image text-slate-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">
                                {{ Str::limit($listing->title, 30) }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-300">
                                {{ $listing->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-sm font-bold text-sky-600 dark:text-sky-400">
                            {{ number_format($listing->price, 0) }} RSD
                        </div>
                    </div>
                @endforeach

                @if ($activity['recent_listings']->count() == 0)
                    <div class="text-center py-4 text-slate-500 dark:text-slate-300">
                        <i class="fas fa-list text-slate-400 text-2xl mb-2"></i>
                        <p>Nemate oglase</p>
                    </div>
                @endif
            </div>

            @if ($activity['recent_listings']->count() > 0)
                <a href="{{ route('listings.my') }}"
                    class="block mt-4 text-sky-600 hover:text-sky-800 text-sm text-center">
                    Vidi sve oglase →
                </a>
            @endif
        </div>

        <!-- Account Overview -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-user text-green-600 mr-2"></i>
                Pregled naloga
            </h3>

            <div class="space-y-4">
                <!-- Account Status -->
                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
                    <div>
                        <div class="font-medium text-slate-900 dark:text-slate-100">Status naloga</div>
                        <div class="text-sm text-slate-600 dark:text-slate-300">
                            @if (auth()->user()->isVerified())
                                <span class="text-green-600">✓ Verifikovan</span>
                            @else
                                <span class="text-slate-600 dark:text-slate-300">Standardni</span>
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-shield-alt text-green-600"></i>
                </div>

                <!-- Ratings -->
                @if ($stats['total_ratings'] > 0)
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <div>
                            <div class="font-medium text-slate-900 dark:text-slate-100">Ocene</div>
                            <div class="text-sm text-slate-600 dark:text-slate-300">
                                {{ $stats['positive_ratings'] }}/{{ $stats['total_ratings'] }} pozitivnih</div>
                        </div>
                        <i class="fas fa-star text-amber-500"></i>
                    </div>
                @endif

                <!-- Notifications -->
                @if ($stats['unread_notifications'] > 0)
                    <div
                        class="flex items-center justify-between p-3 bg-red-100 dark:bg-red-900 rounded-lg border border-red-300 dark:border-red-700">
                        <div>
                            <div class="font-medium text-red-900 dark:text-red-200">Obaveštenja</div>
                            <div class="text-sm text-red-700 dark:text-red-200">{{ $stats['unread_notifications'] }}
                                novo</div>
                        </div>
                        <i class="fas fa-bell text-red-600 dark:text-red-400 "></i>
                    </div>
                @endif

                <!-- Balance -->
                <div
                    class="flex items-center justify-between p-3 bg-green-100 dark:bg-green-900 rounded-lg border border-green-300 dark:border-green-700">
                    <div>
                        <div class="font-medium text-green-900 dark:text-green-200">Balans</div>
                        <div class="text-sm text-green-700 dark:text-green-200">
                            {{ number_format($stats['current_balance'], 0) }} RSD</div>
                    </div>
                    <i class="fas fa-coins text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Auctions (if any) -->
    @if ($activity['recent_auctions']->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    <i class="fas fa-gavel text-amber-600 dark:text-amber-400 mr-2"></i>
                    Vaše poslednje aukcije
                </h3>
                <a href="{{ route('auctions.my') }}" class="text-amber-600 hover:text-amber-800 text-sm">
                    Vidi sve →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($activity['recent_auctions'] as $auction)
                    <div
                        class="border border-amber-300 dark:border-amber-700 rounded-lg p-4 bg-amber-100 dark:bg-amber-900">
                        <div class="flex items-center mb-3">
                            @if ($auction->listing->images->count() > 0)
                                <img src="{{ $auction->listing->images->first()->url }}"
                                    alt="{{ $auction->listing->title }}" class="w-12 h-12 rounded object-cover mr-3">
                            @else
                                <div class="w-12 h-12 rounded bg-slate-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-gavel text-slate-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-slate-900 dark:text-slate-100">
                                    {{ Str::limit($auction->listing->title, 25) }}</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $auction->total_bids }}
                                    ponuda
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-lg font-bold text-amber-600 dark:text-amber-400">
                                {{ number_format($auction->current_price, 0) }} RSD
                            </div>
                            <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded">
                                @if ($auction->isActive())
                                    Aktivna
                                @else
                                    {{ ucfirst($auction->status) }}
                                @endif
                            </span>
                        </div>

                        <a href="{{ route('auction.show', $auction) }}"
                            class="block mt-3 text-center px-3 py-2 bg-amber-600 text-white rounded hover:bg-amber-700 transition-colors text-sm">
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
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-receipt text-green-600 mr-2"></i>
                Poslednje transakcije
            </h3>

            @if ($activity['recent_transactions']->count() > 0)
                <div class="space-y-3">
                    @foreach ($activity['recent_transactions'] as $transaction)
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                    {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-300' : 'bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-300' }}">
                                    <i
                                        class="fas {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'fa-plus' : 'fa-minus' }}"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-slate-900 dark:text-slate-100">
                                        {{ Str::limit($transaction->description, 30) }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300">
                                        {{ $transaction->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div
                                    class="font-bold {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? '+' : '-' }}{{ number_format(abs($transaction->amount), 0) }}
                                    RSD
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('balance.index') }}"
                    class="block mt-4 text-green-600 hover:text-green-800 text-sm text-center">
                    Vidi sve transakcije →
                </a>
            @else
                <div class="text-center py-8 text-slate-500 dark:text-slate-300">
                    <i class="fas fa-receipt text-slate-400 text-3xl mb-2"></i>
                    <p>Nema transakcija</p>
                </div>
            @endif
        </div>

        <!-- Achievements & Stats -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-trophy text-amber-600 dark:text-amber-400 mr-2"></i>
                Vaši uspesi
            </h3>

            <div class="space-y-4">
                <!-- Auction Wins -->
                @if ($stats['won_auctions'] > 0)
                    <div
                        class="flex items-center p-3 bg-amber-100 dark:bg-amber-900 border border-amber-300 dark:border-amber-700 rounded-lg">
                        <i class="fas fa-crown text-amber-600 dark:text-amber-400 mr-3"></i>
                        <div>
                            <div class="font-medium text-amber-900 dark:text-amber-200">Pobede na aukcijama</div>
                            <div class="text-sm text-amber-700 dark:text-amber-300">{{ $stats['won_auctions'] }}
                                pobedničkih aukcija</div>
                        </div>
                    </div>
                @endif

                <!-- Ratings Achievement -->
                @if ($stats['total_ratings'] > 0)
                    <div
                        class="flex items-center p-3 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg">
                        <i class="fas fa-thumbs-up text-green-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-green-900 dark:text-green-200">Pozitivne ocene</div>
                            <div class="text-sm text-green-700 dark:text-green-200">
                                {{ round(($stats['positive_ratings'] / $stats['total_ratings']) * 100) }}% pozitivnih
                                ocena</div>
                        </div>
                    </div>
                @endif

                <!-- Account Age -->
                <div
                    class="flex items-center p-3 bg-sky-100 dark:bg-sky-900 border border-sky-300 dark:border-sky-700 rounded-lg">
                    <i class="fas fa-calendar text-sky-600 dark:text-sky-400 mr-3"></i>
                    <div>
                        <div class="font-medium text-sky-900 dark:text-sky-200">Član od</div>
                        <div class="text-sm text-sky-700 dark:text-sky-200">
                            {{ auth()->user()->created_at->format('F Y') }}</div>
                    </div>
                </div>

                <!-- Verification Status -->
                <div
                    class="flex items-center p-3 {{ auth()->user()->isVerified() ? 'bg-green-100 dark:bg-green-900 border-green-300 dark:border-green-700' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600' }} border rounded-lg">
                    <i
                        class="fas fa-shield-check {{ auth()->user()->isVerified() ? 'text-green-600' : 'text-slate-400' }} mr-3"></i>
                    <div>
                        <div
                            class="font-medium {{ auth()->user()->isVerified() ? 'text-green-900 dark:text-green-100' : 'text-slate-700 dark:text-slate-200' }}">
                            Verifikacija</div>
                        <div
                            class="text-sm {{ auth()->user()->isVerified() ? 'text-green-500 dark:text-green-300' : 'text-slate-500 dark:text-slate-300' }}">
                            {{ auth()->user()->verification_status_text }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
