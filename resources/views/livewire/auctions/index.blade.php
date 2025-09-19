<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Filters -->
    <div class="bg-amber-50 dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <div class="text-slate-600 dark:text-slate-300 mb-4">
                Aktivnih aukcija: <span class="font-semibold">{{ $auctions->total() }}</span>
            </div>

            <div class="flex items-center justify-between gap-4">
                <!-- Sort Options -->
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-amber-500 transition-colors flex items-center justify-between">
                            <span>
                                @switch($sortBy)
                                    @case('ending_soon')
                                        Završavaju uskoro
                                    @break

                                    @case('newest')
                                        Najnovije
                                    @break

                                    @case('highest_price')
                                        Najviša cena
                                    @break

                                    @case('most_bids')
                                        Najviše ponuda
                                    @break
                                @endswitch
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('sortBy', 'ending_soon'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                Završavaju uskoro
                            </button>
                            <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                Najnovije
                            </button>
                            <button @click="$wire.set('sortBy', 'highest_price'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                Najviša cena
                            </button>
                            <button @click="$wire.set('sortBy', 'most_bids'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                Najviše ponuda
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Per page -->
                <div class="w-32" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-amber-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                20
                            </button>
                            <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                50
                            </button>
                        </div>
                    </div>
                </div>

                <!-- View Toggle -->
                <div class="flex bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm">
                    <button wire:click="setViewMode('list')"
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-l-lg transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                    <button wire:click="setViewMode('grid')"
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-r-lg transition-colors">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Layout -->
        <div class="md:hidden">
            <div class="flex gap-3">
                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-amber-500 transition-colors flex items-center justify-between">
                            <span>
                                @switch($sortBy)
                                    @case('ending_soon')
                                        Završavaju uskoro
                                    @break

                                    @case('newest')
                                        Najnovije
                                    @break

                                    @case('highest_price')
                                        Najviša cena
                                    @break

                                    @case('most_bids')
                                        Najviše ponuda
                                    @break
                                @endswitch
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('sortBy', 'ending_soon'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                Završavaju uskoro
                            </button>
                            <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                Najnovije
                            </button>
                            <button @click="$wire.set('sortBy', 'highest_price'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                Najviša cena
                            </button>
                            <button @click="$wire.set('sortBy', 'most_bids'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                Najviše ponuda
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-amber-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }} po strani</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                20 po strani
                            </button>
                            <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                50 po strani
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auction Listings -->
    @if ($auctions->count() > 0)
        @if ($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($auctions as $auction)
                    <div
                        class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-500">
                        <!-- Image with overlay -->
                        <div class="relative">
                            <div class="w-full h-48">
                                @if ($auction->listing->images->count() > 0)
                                    <img src="{{ $auction->listing->images->first()->url }}"
                                        alt="{{ $auction->listing->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Time badge -->
                            <div class="absolute top-2 right-2">
                                <span
                                    class="px-2 py-1 bg-green-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                    @if ($auction->time_left)
                                        {{ $auction->time_left['formatted'] }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                {{ Str::limit($auction->listing->title, 40) }}</h3>

                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        {{ $auction->total_bids }} ponuda
                                    </div>
                                </div>
                                @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                    <div class="text-right">
                                        <div class="text-sm text-slate-500">Kupi odmah:</div>
                                        <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                            {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <p class="text-slate-700 dark:text-slate-200 text-sm mb-3"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit(strip_tags($auction->listing->description), 100) }}
                            </p>

                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                                Prodavac: {{ $auction->seller->name }}
                                {!! $auction->seller->verified_icon !!}
                            </div>

                            <div class="space-y-2">
                                @auth
                                    @if (auth()->id() === $auction->user_id)
                                        <!-- Owner buttons -->
                                        <a href="{{ route('listings.edit', $auction->listing) }}"
                                            class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                            <i class="fas fa-gavel mr-2"></i> Uredi aukciju
                                        </a>
                                    @else
                                        <!-- Buyer buttons -->
                                        <a href="{{ route('auction.show', $auction) }}"
                                            class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                            <i class="fas fa-gavel mr-2"></i> Licitiraj
                                        </a>

                                        @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                            <a href="{{ route('auction.show', $auction) }}"
                                                class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                <i class="fas fa-shopping-cart mr-2"></i> Kupi odmah
                                            </a>
                                        @endif
                                    @endif
                                @else
                                    <!-- Guest user buttons -->
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Prijavite se
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4 mb-8">
                @foreach ($auctions as $auction)
                    <div
                        class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-500">
                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="w-full md:w-48 md:min-w-48 h-48 relative">
                                @if ($auction->listing->images->count() > 0)
                                    <img src="{{ $auction->listing->images->first()->url }}"
                                        alt="{{ $auction->listing->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-3xl"></i>
                                    </div>
                                @endif

                                <!-- Time overlay -->
                                <div class="absolute top-2 right-2">
                                    <span
                                        class="px-2 py-1 bg-green-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                        @if ($auction->time_left)
                                            {{ $auction->time_left['formatted'] }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                            {{ $auction->listing->title }}</h3>

                                        <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $auction->listing->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-folder mr-1"></i>
                                            <span>{{ $auction->listing->category->name }}</span>
                                        </div>

                                        <p class="text-slate-700 dark:text-slate-200 mb-3"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($auction->listing->description), 120) }}
                                        </p>

                                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                                            Prodavac: <span class="font-medium">{{ $auction->seller->name }}</span>
                                            {!! $auction->seller->verified_icon !!}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                                {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                            </div>
                                            <div class="text-sm text-slate-500">{{ $auction->total_bids }} ponuda</div>
                                        </div>

                                        @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                            <div class="text-right">
                                                <div class="text-sm text-slate-500">Kupi odmah:</div>
                                                <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                                    {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div
                                class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-amber-900">
                                <div class="flex flex-col h-full justify-between">
                                    <div class="text-center mb-4">
                                        <div class="text-lg font-bold text-amber-700 dark:text-amber-300">
                                            @if ($auction->time_left)
                                                {{ $auction->time_left['formatted'] }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-amber-600 dark:text-amber-400">vremena ostalo</div>
                                    </div>

                                    <div class="space-y-2">
                                        @auth
                                            @if (auth()->id() === $auction->user_id)
                                                <!-- Owner button -->
                                                <a href="{{ route('listings.edit', $auction->listing) }}"
                                                    class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                    <i class="fas fa-gavel mr-2"></i> Uredi aukciju
                                                </a>
                                            @else
                                                <!-- Buyer buttons -->
                                                <a href="{{ route('auction.show', $auction) }}"
                                                    class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                    <i class="fas fa-gavel mr-2"></i> Licitiraj
                                                </a>

                                                @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                                    <a href="{{ route('auction.show', $auction) }}"
                                                        class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                        <i class="fas fa-shopping-cart mr-2"></i> Kupi odmah
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                            <!-- Guest user button -->
                                            <a href="{{ route('login') }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                <i class="fas fa-sign-in-alt mr-2"></i> Prijavite se
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @if($auctions->hasPages())
            <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                {{ $auctions->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gavel text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Nema aktivnih aukcija</h3>
            <p class="text-slate-600 dark:text-slate-300 mb-4">Trenutno nema aukcija na kojima možete licitirati.</p>
            <a href="{{ route('listings.index') }}"
                class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                Pregledaj oglase
            </a>
        </div>
    @endif

    <!-- Scheduled Auctions Section -->
    @if ($scheduledAuctions->count() > 0)
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Zakazane aukcije</h2>
                    <p class="text-slate-600 dark:text-slate-400">Aukcije koje će uskoro početi</p>
                </div>
                <span
                    class="px-3 py-1 bg-amber-100 dark:bg-amber-700 text-amber-600 dark:text-amber-300 rounded-full text-sm">
                    {{ $scheduledAuctions->count() }} zakazanih
                </span>
            </div>

            @if ($viewMode === 'grid')
                <!-- Grid View -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($scheduledAuctions as $auction)
                        <div
                            class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-700">
                            <!-- Image with overlay -->
                            <div class="relative">
                                <div class="w-full h-48">
                                    @if ($auction->listing->images->count() > 0)
                                        <img src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-clock text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Scheduled badge -->
                                <div class="absolute top-2 right-2">
                                    <span
                                        class="px-2 py-1 bg-amber-700 bg-opacity-90 text-white text-xs font-medium rounded">
                                        ZAKAZANO
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                    {{ Str::limit($auction->listing->title, 40) }}</h3>

                                <div class="mb-3">
                                    <div class="text-sm text-slate-500 dark:text-slate-400">Početna cena:</div>
                                    <div class="text-xl font-bold text-sky-600 dark:text-sky-400">
                                        {{ number_format($auction->starting_price, 0, ',', '.') }} RSD
                                    </div>
                                    @if ($auction->buy_now_price)
                                        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kupi odmah:</div>
                                        <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                            {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                        </div>
                                    @endif
                                </div>

                                <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                                    Prodavac: {{ $auction->listing->user->name }}
                                    {!! $auction->listing->user->verified_icon ?? '' !!}
                                </div>

                                <div class="p-3 bg-amber-50 dark:bg-amber-900 rounded-lg mb-3">
                                    <div class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Počinje: {{ $auction->starts_at->format('d.m.Y \\u H:i') }}
                                    </div>
                                    <div class="text-xs text-amber-600 dark:text-amber-300 mt-1">
                                        ({{ \App\Helpers\DateHelper::diffForHumansSr($auction->starts_at) }})
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @auth
                                        @if(auth()->id() === $auction->user_id)
                                            <a href="{{ route('listings.edit', $auction->listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                <i class="fas fa-gavel mr-2"></i> Uredi aukciju
                                            </a>
                                        @else
                                            <a href="{{ route('auction.show', $auction) }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                <i class="fas fa-eye mr-2"></i> Pogledaj detalje
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('auction.show', $auction) }}"
                                            class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                            <i class="fas fa-eye mr-2"></i> Pogledaj detalje
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($viewMode === 'list')
                <!-- List View -->
                <div class="space-y-4">
                    @foreach ($scheduledAuctions as $auction)
                        <div
                            class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-700">
                            <div class="flex flex-col md:flex-row">
                                <!-- Image -->
                                <div class="w-full md:w-48 md:min-w-48 h-48 relative">
                                    @if ($auction->listing->images->count() > 0)
                                        <img src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-clock text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif

                                    <!-- Scheduled overlay -->
                                    <div class="absolute top-2 right-2">
                                        <span
                                            class="px-2 py-1 bg-amber-700 bg-opacity-90 text-white text-xs font-medium rounded">
                                            ZAKAZANO
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 p-4 md:p-6">
                                    <div class="flex flex-col h-full">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                                {{ $auction->listing->title }}</h3>

                                            <div
                                                class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span>{{ $auction->listing->location }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-folder mr-1"></i>
                                                <span>{{ $auction->listing->category->name }}</span>
                                            </div>

                                            <p class="text-slate-700 dark:text-slate-200 mb-3"
                                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ Str::limit(strip_tags($auction->listing->description), 120) }}
                                            </p>

                                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                                                Prodavac: <span
                                                    class="font-medium">{{ $auction->listing->user->name }}</span>
                                                {!! $auction->listing->user->verified_icon ?? '' !!}
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-sm text-slate-500">Početna cena:</div>
                                                <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                                    {{ number_format($auction->starting_price, 0, ',', '.') }} RSD
                                                </div>
                                            </div>

                                            @if ($auction->buy_now_price)
                                                <div class="text-right">
                                                    <div class="text-sm text-slate-500">Kupi odmah:</div>
                                                    <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                                        {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Sidebar -->
                                <div
                                    class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-amber-900">
                                    <div class="flex flex-col h-full justify-between">
                                        <div class="text-center mb-4">
                                            <div class="text-sm font-bold text-amber-600 dark:text-amber-300">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Počinje za:
                                            </div>
                                            <div class="text-lg font-bold text-amber-700 dark:text-amber-200">
                                                {{ \App\Helpers\DateHelper::diffForHumansSr($auction->starts_at) }}
                                            </div>
                                            <div class="text-xs text-amber-700 dark:text-amber-400 mt-1">
                                                {{ $auction->starts_at->format('d.m.Y \\u H:i') }}
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            @auth
                                                @if(auth()->id() === $auction->user_id)
                                                    <a href="{{ route('listings.edit', $auction->listing) }}"
                                                        class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                        <i class="fas fa-gavel mr-2"></i> Uredi aukciju
                                                    </a>
                                                @else
                                                    <a href="{{ route('auction.show', $auction) }}"
                                                        class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                        <i class="fas fa-eye mr-2"></i> Pogledaj detalje
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('auction.show', $auction) }}"
                                                    class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                    <i class="fas fa-eye mr-2"></i> Pogledaj detalje
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Ended Auctions Section -->
    @if ($endedAuctions->count() > 0)
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Završene aukcije</h2>
                    <p class="text-slate-600 dark:text-slate-400">Poslednjih 5 završenih aukcija</p>
                </div>
                <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full text-sm">
                    {{ $endedAuctions->count() }} završenih
                </span>
            </div>

            @if ($viewMode === 'grid')
                <!-- Grid View -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($endedAuctions as $auction)
                        <div
                            class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-700">
                            <!-- Image with overlay -->
                            <div class="relative">
                                <div class="w-full h-48">
                                    @if ($auction->listing->images->count() > 0)
                                        <img src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}"
                                            class="w-full h-full object-cover opacity-75">
                                    @else
                                        <div
                                            class="w-full h-full bg-slate-200 flex items-center justify-center opacity-75">
                                            <i class="fas fa-gavel text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Ended badge -->
                                <div class="absolute top-2 right-2">
                                    <span
                                        class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                        ZAVRŠENO
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                    {{ Str::limit($auction->listing->title, 40) }}</h3>

                                <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ Str::limit($auction->listing->location, 20) }}</span>
                                </div>

                                <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                                    Prodavac: {{ $auction->listing->user->name }}
                                    {!! $auction->listing->user->verified_icon ?? '' !!}
                                </div>

                                @if ($auction->winner)
                                    <div
                                        class="mb-3 p-2 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded">
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-crown text-amber-500 mr-2"></i>
                                            <span class="text-green-800 dark:text-green-200 font-medium">
                                                Pobednik: {{ Str::limit($auction->winner->name, 15) }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <div class="text-2xl font-bold text-slate-600 dark:text-slate-400">
                                        {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                    </div>
                                    <div class="text-sm text-slate-500">{{ $auction->total_bids }} ponuda</div>
                                </div>

                                <div class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                                    Završeno: {{ $auction->ends_at->format('d.m.Y H:i') }}
                                </div>

                                <a href="{{ route('auction.show', $auction) }}"
                                    class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                    <i class="fas fa-eye mr-2"></i> Pregled rezultata
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($viewMode === 'list')
                <!-- List View -->
                <div class="space-y-4">
                    @foreach ($endedAuctions as $auction)
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden border-l-4 border-amber-700">
                            <div class="flex flex-col md:flex-row">
                                <!-- Image -->
                                <div class="w-full md:w-48 md:min-w-48 h-48 relative">
                                    @if ($auction->listing->images->count() > 0)
                                        <img src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}"
                                            class="w-full h-full object-cover opacity-75">
                                    @else
                                        <div
                                            class="w-full h-full bg-slate-200 dark:bg-slate-600 flex items-center justify-center opacity-75">
                                            <i class="fas fa-gavel text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif

                                    <!-- Ended badge -->
                                    <div class="absolute top-2 right-2">
                                        <span
                                            class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                            ZAVRŠENO
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 p-4 md:p-6">
                                    <div class="flex flex-col h-full">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                                {{ $auction->listing->title }}</h3>

                                            <div
                                                class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span>{{ $auction->listing->location }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-folder mr-1"></i>
                                                <span>{{ $auction->listing->category->name }}</span>
                                            </div>

                                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                                                Prodavac: <span
                                                    class="font-medium">{{ $auction->seller->name }}</span>
                                                {!! $auction->seller->verified_icon !!}
                                            </div>

                                            @if ($auction->winner)
                                                <div
                                                    class="mt-2 p-2 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded">
                                                    <div class="flex items-center text-sm">
                                                        <i class="fas fa-crown text-amber-500 mr-2"></i>
                                                        <span class="text-green-800 dark:text-green-200 font-medium">
                                                            Pobednik: {{ $auction->winner->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-center justify-between mt-4">
                                            <div>
                                                <div class="text-2xl font-bold text-slate-600 dark:text-slate-400">
                                                    {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $auction->total_bids }} ponuda</div>
                                            </div>

                                            <div class="text-right">
                                                <div class="text-sm text-slate-500 dark:text-slate-400">Završeno:</div>
                                                <div class="text-sm font-medium text-slate-600 dark:text-slate-400">
                                                    {{ $auction->ends_at->format('d.m.Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sidebar -->
                                <div
                                    class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-amber-900">
                                    <div class="flex flex-col h-full justify-between">
                                        <div class="text-center mb-4">
                                            <div class="text-lg font-bold text-amber-700 dark:text-amber-300">
                                                <i class="fas fa-flag-checkered mr-1"></i>
                                                Završeno
                                            </div>
                                            <div class="text-xs text-amber-600 dark:text-amber-400">
                                                {{ \App\Helpers\DateHelper::diffForHumansSr($auction->ends_at) }}
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <a href="{{ route('auction.show', $auction) }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                <i class="fas fa-eye mr-2"></i> Pregled rezultata
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
