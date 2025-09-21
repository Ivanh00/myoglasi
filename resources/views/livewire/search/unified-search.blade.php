<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">

    <!-- Filter Summary -->
    @php
        $activeFilters = array_filter([
            $content_type !== 'all'
                ? 'Tip: ' .
                    [
                        'listings' => 'Oglasi',
                        'auctions' => 'Aukcije',
                        'services' => 'Usluge',
                        'giveaways' => 'Pokloni',
                    ][$content_type]
                : null,
            $query ? "Pretraga: '{$query}'" : null,
            $city ? "Grad: {$city}" : null,
            $search_category && $content_type === 'listings'
                ? 'Kategorija: ' . ($categories->firstWhere('id', $search_category)->name ?? 'N/A')
                : null,
            $search_subcategory && $content_type === 'listings'
                ? 'Podkategorija: ' . ($subcategories->firstWhere('id', $search_subcategory)->name ?? 'N/A')
                : null,
            $service_category && $content_type === 'services'
                ? 'Kategorija usluga: ' . ($serviceCategories->firstWhere('id', $service_category)->name ?? 'N/A')
                : null,
            $service_subcategory && $content_type === 'services'
                ? 'Podkategorija usluga: ' .
                    ($serviceSubcategories->firstWhere('id', $service_subcategory)->name ?? 'N/A')
                : null,
            $condition_id ? 'Stanje: ' . ($conditions->firstWhere('id', $condition_id)->name ?? 'N/A') : null,
            $auction_type
                ? 'Aukcije: ' .
                    [
                        'ending_soon' => 'Završavaju uskoro',
                        'newest' => 'Najnovije',
                        'highest_price' => 'Najviša cena',
                        'most_bids' => 'Najviše ponuda',
                        'scheduled' => 'Zakazane aukcije',
                    ][$auction_type]
                : null,
            $price_min ? 'Cena od: ' . number_format($price_min, 0, ',', '.') . ' RSD' : null,
            $price_max ? 'Cena do: ' . number_format($price_max, 0, ',', '.') . ' RSD' : null,
        ]);

        // Check if we have any filters (including content type)
        $hasFilters =
            !empty($activeFilters) ||
            $query ||
            $city ||
            $search_category ||
            $service_category ||
            $condition_id ||
            $auction_type ||
            $price_min ||
            $price_max ||
            $content_type !== 'all';
    @endphp

    @if ($hasFilters && !empty($activeFilters))
        <div class="bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-sky-900 dark:text-sky-200 mb-2">
                        Aktivni filteri:
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($activeFilters as $filter)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-sky-100 dark:bg-sky-800 text-sky-800 dark:text-sky-200">
                                {{ $filter }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <button onclick="window.location.href = '{{ route('search.unified') }}'"
                    class="text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>
                    Poništi sve filtere
                </button>
            </div>
        </div>
    @endif

    <!-- Results and Controls -->
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-600 dark:text-slate-300">
                @if (request()->routeIs('home'))
                    Ukupno: <span class="font-semibold">{{ $results->total() }}</span>
                    @if ($content_type === 'all')
                        objava (oglasi, aukcije, usluge, pokloni)
                    @elseif($content_type === 'auctions')
                        aukcija
                    @elseif($content_type === 'services')
                        usluga
                    @elseif($content_type === 'giveaways')
                        poklona
                    @elseif($content_type === 'listings')
                        oglasa
                    @else
                        objava
                    @endif
                @else
                    Pronađeno: <span class="font-semibold">{{ $results->total() }}</span>
                    @if ($content_type === 'all')
                        rezultata
                    @elseif($content_type === 'auctions')
                        aukcija
                    @elseif($content_type === 'services')
                        usluga
                    @elseif($content_type === 'giveaways')
                        poklona
                    @elseif($content_type === 'listings')
                        oglasa
                    @else
                        rezultata
                    @endif
                @endif
            </div>

            <!-- Content Type Selector -->
            <div class="flex items-center space-x-2">
                <button wire:click="$set('content_type', 'all')"
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'all' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                    Sve
                </button>
                <button wire:click="$set('content_type', 'listings')"
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'listings' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                    Oglasi
                </button>
                <button wire:click="$set('content_type', 'services')"
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'services' ? 'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-200' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                    <i class="fas fa-tools mr-1"></i>
                    Usluge
                </button>
                <button wire:click="$set('content_type', 'giveaways')"
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'giveaways' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                    <i class="fas fa-gift mr-1"></i>
                    Pokloni
                </button>
                <button wire:click="$set('content_type', 'auctions')"
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'auctions' ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                    <i class="fas fa-gavel mr-1"></i>
                    Aukcije
                </button>
            </div>
        </div>

        <!-- Controls -->
        <div class="flex items-center justify-between">
            <!-- Left: Sort Options -->
            <div class="flex items-center space-x-3">
                <div class="w-40" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($content_type === 'auctions')
                                    @switch($auction_type ?: 'ending_soon')
                                        @case('scheduled')
                                            Zakazane aukcije
                                        @break

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

                                        @default
                                            Završavaju uskoro
                                    @endswitch
                                @else
                                    @switch($sortBy)
                                        @case('newest')
                                            Najnovije
                                        @break

                                        @case('price_asc')
                                            Cena ↑
                                        @break

                                        @case('price_desc')
                                            Cena ↓
                                        @break

                                        @default
                                            Najnovije
                                    @endswitch
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            @if ($content_type === 'auctions')
                                <button @click="$wire.set('auction_type', 'ending_soon'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    <i class="fas fa-clock text-red-500 mr-2"></i>
                                    Završavaju uskoro
                                </button>
                                <button @click="$wire.set('auction_type', 'newest'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    <i class="fas fa-plus text-green-500 mr-2"></i>
                                    Najnovije
                                </button>
                                <button @click="$wire.set('auction_type', 'highest_price'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    <i class="fas fa-dollar-sign text-green-500 mr-2"></i>
                                    Najviša cena
                                </button>
                                <button @click="$wire.set('auction_type', 'most_bids'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    <i class="fas fa-gavel text-orange-500 mr-2"></i>
                                    Najviše ponuda
                                </button>
                                <button @click="$wire.set('auction_type', 'scheduled'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    <i class="fas fa-calendar text-amber-500 mr-2"></i>
                                    Zakazane aukcije
                                </button>
                            @else
                                <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    Najnovije
                                </button>
                                <button @click="$wire.set('sortBy', 'price_asc'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    Cena ↑
                                </button>
                                <button @click="$wire.set('sortBy', 'price_desc'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    Cena ↓
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Category Dropdown -->
                @if ($content_type === 'services')
                    <!-- Service Category Dropdown -->
                    <div class="w-60" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($service_category)
                                        @php $selectedCat = $serviceCategories->firstWhere('id', $service_category); @endphp
                                        {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                                    @else
                                        Sve kategorije
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <button @click="$wire.setServiceCategory(''); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$service_category ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    Sve kategorije
                                </button>
                                @foreach ($serviceCategories as $category)
                                    <button @click="$wire.setServiceCategory('{{ $category->id }}'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $service_category == $category->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                        @if ($category->icon)
                                            <i class="{{ $category->icon }} text-sky-600 dark:text-sky-400 mr-2"></i>
                                        @endif
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @elseif($content_type !== 'auctions')
                    <!-- Regular Category Dropdown for Listings/Giveaways -->
                    <div class="w-56" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($search_category)
                                        @php $selectedCat = $categories->firstWhere('id', $search_category); @endphp
                                        {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                                    @else
                                        Sve kategorije
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <button @click="$wire.set('search_category', ''); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$search_category ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    Sve kategorije
                                </button>
                                @foreach ($categories as $category)
                                    <button @click="$wire.set('search_category', '{{ $category->id }}'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $search_category == $category->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                        @if ($category->icon)
                                            <i
                                                class="{{ $category->icon }} text-slate-600 dark:text-slate-400 mr-2"></i>
                                        @endif
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Per Page -->
                <div class="w-32" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', 20); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                20
                            </button>
                            <button @click="$wire.set('perPage', 50); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                50
                            </button>
                            <button @click="$wire.set('perPage', 100); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                100
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: View Mode Toggle -->
            <div class="flex bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm">
                <button wire:click="setViewMode('list')"
                    class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-l-lg transition-colors">
                    <i class="fas fa-list"></i>
                </button>
                <button wire:click="setViewMode('grid')"
                    class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-r-lg transition-colors">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Results -->
    @if ($results->count() > 0)
        @if ($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4">
                @foreach ($results as $listing)
                    <div
                        class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 
                        @if (isset($listing->is_auction)) border-l-4 border-amber-500
                        @elseif($listing instanceof \App\Models\Service)
                            border-l-4 border-slate-500
                        @elseif($listing instanceof \App\Models\Listing && $listing->listing_type === 'giveaway')
                            border-l-4 border-green-500
                        @else
                            border-l-4 border-sky-500 @endif">
                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="w-full md:w-48 md:min-w-48 h-48 relative">
                                <a
                                    href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : ($listing instanceof \App\Models\Service ? route('services.show', $listing) : route('listings.show', $listing)) }}">
                                    @if ($listing->images->count() > 0)
                                        <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </a>

                                <!-- Auction Badge -->
                                @if (isset($listing->is_auction))
                                    <div class="absolute top-2 left-2">
                                        <span
                                            class="inline-flex items-center px-2 py-1 bg-amber-50 dark:bg-slate-6000 bg-opacity-90 text-white text-xs font-medium rounded">
                                            <i class="fas fa-gavel mr-1"></i>
                                            Aukcija
                                        </span>
                                    </div>

                                    @if ($listing->auction_data->time_left)
                                        <div class="absolute top-2 right-2">
                                            <span
                                                class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                                {{ $listing->auction_data->time_left['formatted'] }}
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-2">
                                            <a href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : ($listing instanceof \App\Models\Service ? route('services.show', $listing) : route('listings.show', $listing)) }}"
                                                class="flex-1">
                                                <h3
                                                    class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-sky-600 transition-colors">
                                                    {{ $listing->title }}
                                                </h3>
                                            </a>

                                            <!-- Promotion Badges -->
                                            @if (
                                                ($listing instanceof \App\Models\Listing || $listing instanceof \App\Models\Service) &&
                                                    $listing->hasActivePromotion())
                                                <div class="flex flex-wrap gap-1 ml-2">
                                                    @foreach ($listing->getPromotionBadges() as $badge)
                                                        <span
                                                            class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                            {{ $badge['text'] }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $listing->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-folder mr-1"></i>
                                            <span>{{ $listing->category->name }}</span>
                                        </div>

                                        <p class="text-slate-700 dark:text-slate-200 mb-3"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($listing->description), 120) }}
                                        </p>

                                        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                                            Prodavac: <span class="font-medium">{{ $listing->user->name }}</span>
                                            {!! $listing->user->verified_icon !!}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if (isset($listing->is_auction))
                                                <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                                    {{ number_format($listing->auction_data->current_price, 0, ',', '.') }}
                                                    RSD
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-300">
                                                    {{ $listing->auction_data->total_bids }} ponuda</div>
                                            @elseif($listing instanceof \App\Models\Listing && $listing->listing_type === 'giveaway')
                                                <div class="text-xl font-bold text-green-600">BESPLATNO</div>
                                            @else
                                                <div class="text-xl font-bold text-sky-600 dark:text-sky-400">
                                                    {{ number_format($listing->price, 2, ',', '.') }} RSD
                                                </div>
                                            @endif
                                        </div>

                                        @if (isset($listing->is_auction) &&
                                                $listing->auction_data->buy_now_price &&
                                                $listing->auction_data->current_price < $listing->auction_data->buy_now_price)
                                            <div class="text-right">
                                                <div class="text-sm text-slate-500 dark:text-slate-300">Kupi odmah:
                                                </div>
                                                <div class="text-lg font-bold text-green-600">
                                                    {{ number_format($listing->auction_data->buy_now_price, 0, ',', '.') }}
                                                    RSD
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                @if ($listing instanceof \App\Models\Listing && $listing->getTypeBadge())
                                                    <span
                                                        class="px-2 py-1 text-xs font-bold rounded-full {{ $listing->getTypeBadge()['class'] }}">
                                                        {{ $listing->getTypeBadge()['text'] }}
                                                    </span>
                                                @endif

                                                @if ($listing->condition)
                                                    <span
                                                        class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-medium rounded-full">
                                                        {{ $listing->condition->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div
                                class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600
                                @if (isset($listing->is_auction)) bg-amber-50 dark:bg-amber-900
                                @elseif($listing instanceof \App\Models\Listing && $listing->listing_type === 'giveaway')
                                    bg-green-50 dark:bg-slate-600
                                @elseif($listing instanceof \App\Models\Service)
                                    bg-slate-50 dark:bg-slate-600
                                @else
                                    bg-sky-50 dark:bg-slate-600 @endif">
                                <div class="flex flex-col h-full justify-between">
                                    @if (isset($listing->is_auction))
                                        <div class="text-center mb-4">
                                            <div class="text-lg font-bold text-amber-700 dark:text-amber-200">
                                                @if ($listing->auction_data->time_left)
                                                    {{ $listing->auction_data->time_left['formatted'] }}
                                                @endif
                                            </div>
                                            <div class="text-xs text-amber-600 dark:text-amber-400">vremena ostalo
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300 mb-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span>{{ $listing->views ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span>❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                            </div>
                                        </div>

                                        <div class="text-xs text-slate-500 dark:text-slate-300 mb-4">
                                            <i class="fas fa-clock mr-1"></i>
                                            Objavljeno {{ $listing->created_at->diffForHumans() }}
                                        </div>
                                    @endif

                                    <div class="space-y-2">
                                        @if (isset($listing->is_auction))
                                            @auth
                                                @if (auth()->id() === $listing->auction_data->user_id)
                                                    <!-- Owner buttons -->
                                                    <a href="{{ route('listings.edit', $listing) }}"
                                                        class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                        <i class="fas fa-edit mr-2"></i> Uredi aukciju
                                                    </a>
                                                @else
                                                    <!-- Buyer buttons -->
                                                    <a href="{{ route('auction.show', $listing->auction_data) }}"
                                                        class="block w-full text-center px-3 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm">
                                                        <i class="fas fa-gavel mr-2"></i> Licitiraj
                                                    </a>

                                                    @if (
                                                        $listing->auction_data->buy_now_price &&
                                                            $listing->auction_data->current_price < $listing->auction_data->buy_now_price)
                                                        <a href="{{ route('auction.show', $listing->auction_data) }}"
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
                                        @else
                                            @auth
                                                @if ($listing instanceof \App\Models\Listing && auth()->id() === $listing->user_id)
                                                    <!-- Owner buttons for listings -->
                                                    @if ($listing->listing_type === 'listing' && !$listing->auction)
                                                        <a href="{{ route('auction.setup', $listing) }}"
                                                            class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                            <i class="fas fa-gavel mr-2"></i> Prodaj na aukciji
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('listings.edit', $listing) }}"
                                                        class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                        <i class="fas fa-edit mr-2"></i> Uredi oglas
                                                    </a>
                                                @else
                                                    <!-- Regular view button -->
                                                    <a href="{{ $listing instanceof \App\Models\Service ? route('services.show', $listing) : route('listings.show', $listing) }}"
                                                        class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                        <i class="fas fa-eye mr-2"></i> Pregled
                                                    </a>
                                                @endif
                                            @else
                                                <!-- Guest user buttons -->
                                                <a href="{{ $listing instanceof \App\Models\Service ? route('services.show', $listing) : route('listings.show', $listing) }}"
                                                    class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                    <i class="fas fa-eye mr-2"></i> Pregled
                                                </a>
                                            @endauth
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($results as $listing)
                    <div
                        class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full
                        @if (isset($listing->is_auction)) border-l-4 border-amber-500
                        @elseif($listing instanceof \App\Models\Service)
                            border-l-4 border-slate-500
                        @elseif($listing instanceof \App\Models\Listing && $listing->listing_type === 'giveaway')
                            border-l-4 border-green-500
                        @else
                            border-l-4 border-sky-500 @endif">
                        <!-- Image -->
                        <div class="w-full h-48 relative">
                            <a
                                href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : route('listings.show', $listing) }}">
                                @if ($listing->images->count() > 0)
                                    <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>

                            <!-- Auction Badge -->
                            @if (isset($listing->is_auction))
                                <div class="absolute top-2 left-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-amber-50 dark:bg-slate-6000 bg-opacity-90 text-white text-xs font-medium rounded">
                                        <i class="fas fa-gavel mr-1"></i>
                                        Aukcija
                                    </span>
                                </div>

                                @if ($listing->auction_data->time_left)
                                    <div class="absolute top-2 right-2">
                                        <span
                                            class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                            {{ $listing->auction_data->time_left['formatted'] }}
                                        </span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-1">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <a href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : route('listings.show', $listing) }}"
                                        class="flex-1">
                                        <h3
                                            class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-sky-600 transition-colors">
                                            {{ Str::limit($listing->title, 40) }}
                                        </h3>
                                    </a>

                                    <!-- Promotion Badges -->
                                    @if (
                                        ($listing instanceof \App\Models\Listing || $listing instanceof \App\Models\Service) &&
                                            $listing->hasActivePromotion())
                                        <div class="flex flex-wrap gap-1 ml-2">
                                            @foreach ($listing->getPromotionBadges() as $badge)
                                                <span
                                                    class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                    {{ $badge['text'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $listing->location }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-folder mr-1"></i>
                                    <span>{{ $listing->category->name }}</span>
                                </div>

                                <p class="text-slate-700 dark:text-slate-200 text-sm mb-3"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($listing->description), 100) }}
                                </p>

                                <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                                    Prodavac: {{ $listing->user->name }}
                                    {!! $listing->user->verified_icon !!}
                                </div>

                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        @if (isset($listing->is_auction))
                                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                                {{ number_format($listing->auction_data->current_price, 0, ',', '.') }} RSD
                                            </div>
                                            <div class="text-sm text-slate-500 dark:text-slate-300">
                                                {{ $listing->auction_data->total_bids }}
                                                ponuda</div>
                                        @elseif($listing instanceof \App\Models\Listing && $listing->listing_type === 'giveaway')
                                            <div class="text-2xl font-bold text-green-600">BESPLATNO</div>
                                        @else
                                            <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                                {{ number_format($listing->price, 2, ',', '.') }} RSD
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($listing instanceof \App\Models\Listing && $listing->getTypeBadge())
                                            <span
                                                class="px-2 py-1 text-xs font-bold rounded-full {{ $listing->getTypeBadge()['class'] }}">
                                                {{ $listing->getTypeBadge()['text'] }}
                                            </span>
                                        @endif

                                        @if ($listing->condition)
                                            <span
                                                class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-medium rounded-full">
                                                {{ $listing->condition->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Stats -->
                                @if (!isset($listing->is_auction))
                                    <div
                                        class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-300 mb-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span>{{ $listing->views ?? 0 }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span>❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-xs text-slate-500 dark:text-slate-300 mb-3">
                                    <i class="fas fa-clock mr-1"></i>
                                    Objavljeno {{ $listing->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="space-y-2 mt-auto">
                                @if (isset($listing->is_auction))
                                    @auth
                                        @if (auth()->id() === $listing->auction_data->user_id)
                                            <!-- Owner buttons -->
                                            <a href="{{ route('listings.edit', $listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                <i class="fas fa-edit mr-2"></i> Uredi aukciju
                                            </a>
                                        @else
                                            <!-- Buyer buttons -->
                                            <a href="{{ route('auction.show', $listing->auction_data) }}"
                                                class="block w-full text-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                                <i class="fas fa-gavel mr-2"></i> Licitiraj
                                            </a>

                                            @if (
                                                $listing->auction_data->buy_now_price &&
                                                    $listing->auction_data->current_price < $listing->auction_data->buy_now_price)
                                                <a href="{{ route('auction.show', $listing->auction_data) }}"
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
                                @else
                                    @auth
                                        @if ($listing instanceof \App\Models\Listing && auth()->id() === $listing->user_id)
                                            <!-- Owner buttons for listings -->
                                            @if ($listing->listing_type === 'listing' && !$listing->auction)
                                                <a href="{{ route('auction.setup', $listing) }}"
                                                    class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                                    <i class="fas fa-gavel mr-2"></i> Prodaj na aukciji
                                                </a>
                                            @endif

                                            <a href="{{ route('listings.edit', $listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                <i class="fas fa-edit mr-2"></i> Uredi oglas
                                            </a>
                                        @else
                                            <!-- Regular view button -->
                                            <a href="{{ $listing instanceof \App\Models\Service ? route('services.show', $listing) : route('listings.show', $listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                <i class="fas fa-eye mr-2"></i> Pregled
                                            </a>
                                        @endif
                                    @else
                                        <!-- Guest user buttons -->
                                        <a href="{{ $listing instanceof \App\Models\Service ? route('services.show', $listing) : route('listings.show', $listing) }}"
                                            class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                            <i class="fas fa-eye mr-2"></i> Pregled
                                        </a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{ $results->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-search text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema rezultata</h3>
            <p class="text-slate-600 dark:text-slate-300 mb-4">
                Pokušajte sa drugačijim filterima ili ključnim rečima.
            </p>
            <button onclick="window.location.href = '{{ route('search.unified') }}'"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                Poništi filtere
            </button>
        </div>
    @endif
</div>
