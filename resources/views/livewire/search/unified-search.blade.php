<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">

    <!-- Filter Summary -->
    @php
        $activeFilters = array_filter([
            'content_type' =>
                $content_type !== 'all'
                    ? [
                        'label' =>
                            'Tip: ' .
                            [
                                'listings' => 'Oglasi',
                                'auctions' => 'Aukcije',
                                'services' => 'Usluge',
                                'giveaways' => 'Pokloni',
                            ][$content_type],
                        'action' => "wire:click=\"\$set('content_type', 'all')\"",
                    ]
                    : null,
            'query' => $query
                ? [
                    'label' => "Pretraga: '{$query}'",
                    'action' => "wire:click=\"\$set('query', '')\"",
                ]
                : null,
            'city' => $city
                ? [
                    'label' => "Grad: {$city}",
                    'action' => "wire:click=\"\$set('city', '')\"",
                ]
                : null,
            'search_category' =>
                $search_category && $content_type === 'listings'
                    ? [
                        'label' => 'Kategorija: ' . ($categories->firstWhere('id', $search_category)->name ?? 'N/A'),
                        'action' => "wire:click=\"\$set('search_category', '')\"",
                    ]
                    : null,
            'search_subcategory' =>
                $search_subcategory && $content_type === 'listings'
                    ? [
                        'label' =>
                            'Podkategorija: ' . ($subcategories->firstWhere('id', $search_subcategory)->name ?? 'N/A'),
                        'action' => "wire:click=\"\$set('search_subcategory', '')\"",
                    ]
                    : null,
            'service_category' =>
                $service_category && $content_type === 'services'
                    ? [
                        'label' =>
                            'Kategorija usluga: ' .
                            ($serviceCategories->firstWhere('id', $service_category)->name ?? 'N/A'),
                        'action' => "wire:click=\"\$set('service_category', '')\"",
                    ]
                    : null,
            'service_subcategory' =>
                $service_subcategory && $content_type === 'services'
                    ? [
                        'label' =>
                            'Podkategorija usluga: ' .
                            ($serviceSubcategories->firstWhere('id', $service_subcategory)->name ?? 'N/A'),
                        'action' => "wire:click=\"\$set('service_subcategory', '')\"",
                    ]
                    : null,
            'condition_id' => $condition_id
                ? [
                    'label' => 'Stanje: ' . ($conditions->firstWhere('id', $condition_id)->name ?? 'N/A'),
                    'action' => "wire:click=\"\$set('condition_id', '')\"",
                ]
                : null,
            'auction_type' => $auction_type
                ? [
                    'label' =>
                        'Aukcije: ' .
                        [
                            'ending_soon' => 'Završavaju uskoro',
                            'newest' => 'Najnovije',
                            'highest_price' => 'Najviša cena',
                            'most_bids' => 'Najviše ponuda',
                            'scheduled' => 'Zakazane aukcije',
                        ][$auction_type],
                    'action' => "wire:click=\"\$set('auction_type', '')\"",
                ]
                : null,
            'price_min' => $price_min
                ? [
                    'label' => 'Cena od: ' . number_format($price_min, 0, ',', '.') . ' RSD',
                    'action' => "wire:click=\"\$set('price_min', '')\"",
                ]
                : null,
            'price_max' => $price_max
                ? [
                    'label' => 'Cena do: ' . number_format($price_max, 0, ',', '.') . ' RSD',
                    'action' => "wire:click=\"\$set('price_max', '')\"",
                ]
                : null,
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
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-sm font-medium text-sky-900 dark:text-sky-200">
                        Aktivni filteri:
                    </h3>
                    @foreach ($activeFilters as $filter)
                        <span
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-sky-100 dark:bg-sky-800 text-sky-800 dark:text-sky-200">
                            {{ $filter['label'] }}
                            <button {!! $filter['action'] !!}
                                class="hover:text-sky-600 dark:hover:text-sky-400 transition">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </span>
                    @endforeach
                </div>
                <button onclick="window.location.href = '{{ route('search.unified') }}'"
                    class="text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 text-sm font-medium flex-shrink-0">
                    <i class="fas fa-times mr-1"></i>
                    Poništi sve filtere
                </button>
            </div>
        </div>
    @endif

    <!-- Mobile Results Count and Category (Visible only on mobile, at top) -->
    <div class="md:hidden bg-white dark:bg-slate-700 rounded-lg shadow-md p-2 mb-4">
        <div class="text-slate-600 dark:text-slate-300 text-center mb-3">
            @if (request()->routeIs('home'))
                Ukupno: <span class="font-semibold">{{ $results->total() }}</span>
                @if ($content_type === 'all')
                    objava
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

        <!-- Mobile Content Type Selector -->
        <div class="flex items-center justify-center space-x-1 mb-3">
            <button wire:click="$set('content_type', 'all')"
                class="px-2 py-1 rounded-md text-xs font-medium transition-colors {{ $content_type === 'all' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                Sve
            </button>
            <button wire:click="$set('content_type', 'listings')"
                class="px-2 py-1 rounded-md text-xs font-medium transition-colors {{ $content_type === 'listings' ? 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                Oglasi
            </button>
            <button wire:click="$set('content_type', 'services')"
                class="px-2 py-1 rounded-md text-xs font-medium transition-colors {{ $content_type === 'services' ? 'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-200' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                <i class="fas fa-tools mr-1"></i>
                Usluge
            </button>
            <button wire:click="$set('content_type', 'giveaways')"
                class="px-2 py-1 rounded-md text-xs font-medium transition-colors {{ $content_type === 'giveaways' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                <i class="fas fa-gift mr-1"></i>
                Pokloni
            </button>
            <button wire:click="$set('content_type', 'auctions')"
                class="px-2 py-1 rounded-md text-xs font-medium transition-colors {{ $content_type === 'auctions' ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }}">
                <i class="fas fa-gavel mr-1"></i>
                Aukcije
            </button>
        </div>

        <!-- Mobile Category Dropdown -->
        @if ($content_type === 'services')
            <!-- Service Category Dropdown -->
            <div class="w-full" x-data="{ open: false }" x-init="open = false">
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
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <button @click="$wire.setServiceCategory(''); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$service_category ? 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
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

            <!-- Service Subcategory Dropdown (Mobile) -->
            @if ($service_category && count($serviceSubcategories) > 0)
                <div class="w-full mt-2" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($service_subcategory)
                                    @php $selectedSubcat = $serviceSubcategories->firstWhere('id', $service_subcategory); @endphp
                                    {{ $selectedSubcat ? $selectedSubcat->name : 'Sve podkategorije' }}
                                @else
                                    Sve podkategorije
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.set('service_subcategory', ''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$service_subcategory ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve podkategorije
                            </button>
                            @foreach ($serviceSubcategories as $subcategory)
                                <button
                                    @click="$wire.set('service_subcategory', '{{ $subcategory->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $service_subcategory == $subcategory->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    {{ $subcategory->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @elseif($content_type !== 'all')
            <!-- Regular Category Dropdown for Listings/Giveaways/Auctions -->
            <div class="w-full" x-data="{ open: false }" x-init="open = false">
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
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        @php
                            $allCategoriesClass = match ($content_type) {
                                'auctions' => !$search_category
                                    ? 'bg-amber-50 dark:bg-amber-900 text-amber-700 dark:text-amber-200'
                                    : 'text-slate-700 dark:text-slate-200',
                                'giveaways' => !$search_category
                                    ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200'
                                    : 'text-slate-700 dark:text-slate-200',
                                'services' => !$search_category
                                    ? 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200'
                                    : 'text-slate-700 dark:text-slate-200',
                                default => !$search_category
                                    ? 'bg-sky-50 dark:bg-sky-900 text-sky-700 dark:text-sky-200'
                                    : 'text-slate-700 dark:text-slate-200',
                            };
                        @endphp
                        <button @click="$wire.set('search_category', ''); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $allCategoriesClass }}">
                            Sve kategorije
                        </button>
                        @foreach ($categories as $category)
                            <button @click="$wire.set('search_category', '{{ $category->id }}'); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $search_category == $category->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                @if ($category->icon)
                                    @php
                                        $iconColor = match ($content_type) {
                                            'auctions' => 'text-amber-600 dark:text-amber-400',
                                            'giveaways' => 'text-green-600 dark:text-green-400',
                                            'services' => 'text-slate-600 dark:text-slate-400',
                                            default => 'text-sky-600 dark:text-sky-400',
                                        };
                                    @endphp
                                    <i class="{{ $category->icon }} {{ $iconColor }} mr-2"></i>
                                @endif
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Regular Subcategory Dropdown (Mobile) -->
            @if ($search_category && count($subcategories) > 0)
                <div class="w-full mt-2" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($search_subcategory)
                                    @php $selectedSubcat = $subcategories->firstWhere('id', $search_subcategory); @endphp
                                    {{ $selectedSubcat ? $selectedSubcat->name : 'Sve podkategorije' }}
                                @else
                                    Sve podkategorije
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.set('search_subcategory', ''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$search_subcategory ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve podkategorije
                            </button>
                            @foreach ($subcategories as $subcategory)
                                <button
                                    @click="$wire.set('search_subcategory', '{{ $subcategory->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $search_subcategory == $subcategory->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    {{ $subcategory->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Mobile Additional Filters -->
        <div class="md:hidden mt-4 bg-white dark:bg-slate-700 rounded-lg shadow-md p-4">
            <div class="grid grid-cols-1 gap-4">
                @include('livewire.components.search-filters')
            </div>
        </div>

        <!-- Mobile Filters i sortiranje -->
        <div class="md:hidden mt-3">
            <div class="flex gap-3">
                <!-- Mobile sorting (50% width) -->
                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($content_type === 'auctions')
                                    @switch($auction_type)
                                        @case('scheduled')
                                            Zakazane aukcije
                                        @break

                                        @case('ending_soon')
                                            Završavaju uskoro
                                        @break

                                        @case('highest_price')
                                            Najviša cena
                                        @break

                                        @case('most_bids')
                                            Najviše ponuda
                                        @break

                                        @case('newest')

                                            @default
                                                Najnovije
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

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                                @if ($content_type === 'auctions')
                                    <button @click="$wire.set('auction_type', 'newest'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                        Najnovije
                                    </button>
                                    <button @click="$wire.set('auction_type', 'ending_soon'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                        Završavaju uskoro
                                    </button>
                                    <button @click="$wire.set('auction_type', 'highest_price'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                        Najviša cena
                                    </button>
                                    <button @click="$wire.set('auction_type', 'most_bids'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                        Najviše ponuda
                                    </button>
                                    <button @click="$wire.set('auction_type', 'scheduled'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
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

                    <!-- Mobile per page (50% width) -->
                    <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                                <button @click="$wire.set('perPage', 20); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    20 po strani
                                </button>
                                <button @click="$wire.set('perPage', 50); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    50 po strani
                                </button>
                                <button @click="$wire.set('perPage', 100); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    100 po strani
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results and Controls -->
        <div class="hidden md:block bg-white dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
            <div class="flex items-center justify-between">
                <!-- Desktop Results Count (Hidden on mobile) -->
                <div class="hidden md:block text-slate-600 dark:text-slate-300">
                    @if (request()->routeIs('home'))
                        Ukupno: <span class="font-semibold">{{ $results->total() }}</span>
                        @if ($content_type === 'all')
                            objava
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

                <!-- Desktop Content Type Selector (Hidden on mobile) -->
                <div class="hidden md:flex items-center space-x-2">
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

            <!-- Desktop Additional Filters (inside controls section) -->
            <div class="hidden md:block pt-4 border-t border-slate-200 dark:border-slate-600">
                <div class="grid grid-cols-4 gap-4">
                    @include('livewire.components.search-filters')
                </div>
            </div>

            <!-- Controls -->
            <div class="hidden md:flex items-center justify-between md:pt-4">
                <!-- Left: Category Dropdowns -->
                <div class="flex items-center gap-3">
                    @if ($content_type === 'services')
                        <!-- Service Category Dropdown -->
                        <div class="w-56" x-data="{ open: false }" x-init="open = false">
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

                                <div x-show="open" x-cloak @click.away="open = false" x-transition
                                    class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    <button @click="$wire.setServiceCategory(''); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$service_category ? 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                        Sve kategorije
                                    </button>
                                    @foreach ($serviceCategories as $category)
                                        <button @click="$wire.setServiceCategory('{{ $category->id }}'); open = false"
                                            type="button"
                                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $service_category == $category->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                            @if ($category->icon)
                                                @php
                                                    $iconColor = match ($content_type) {
                                                        'auctions' => 'text-amber-600 dark:text-amber-400',
                                                        'giveaways' => 'text-green-600 dark:text-green-400',
                                                        'services' => 'text-slate-600 dark:text-slate-400',
                                                        default => 'text-sky-600 dark:text-sky-400',
                                                    };
                                                @endphp
                                                <i class="{{ $category->icon }} {{ $iconColor }} mr-2"></i>
                                            @endif
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Service Subcategory Dropdown (Desktop) -->
                        @if ($service_category && count($serviceSubcategories) > 0)
                            <div class="w-56" x-data="{ open: false }" x-init="open = false">
                                <div class="relative">
                                    <button @click="open = !open" type="button"
                                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                        <span>
                                            @if ($service_subcategory)
                                                @php $selectedSubcat = $serviceSubcategories->firstWhere('id', $service_subcategory); @endphp
                                                {{ $selectedSubcat ? $selectedSubcat->name : 'Sve podkategorije' }}
                                            @else
                                                Sve podkategorije
                                            @endif
                                        </span>
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <button @click="$wire.set('service_subcategory', ''); open = false" type="button"
                                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$service_subcategory ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                            Sve podkategorije
                                        </button>
                                        @foreach ($serviceSubcategories as $subcategory)
                                            <button
                                                @click="$wire.set('service_subcategory', '{{ $subcategory->id }}'); open = false"
                                                type="button"
                                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $service_subcategory == $subcategory->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                                {{ $subcategory->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif($content_type !== 'all')
                        <!-- Regular Category Dropdown for Listings/Giveaways/Auctions -->
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

                                <div x-show="open" x-cloak @click.away="open = false" x-transition
                                    class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    @php
                                        $allCategoriesClassDesktop = match ($content_type) {
                                            'auctions' => !$search_category
                                                ? 'bg-amber-50 dark:bg-amber-900 text-amber-700 dark:text-amber-200'
                                                : 'text-slate-700 dark:text-slate-200',
                                            'giveaways' => !$search_category
                                                ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200'
                                                : 'text-slate-700 dark:text-slate-200',
                                            'services' => !$search_category
                                                ? 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200'
                                                : 'text-slate-700 dark:text-slate-200',
                                            default => !$search_category
                                                ? 'bg-sky-50 dark:bg-sky-900 text-sky-700 dark:text-sky-200'
                                                : 'text-slate-700 dark:text-slate-200',
                                        };
                                    @endphp
                                    <button @click="$wire.set('search_category', ''); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $allCategoriesClassDesktop }}">
                                        Sve kategorije
                                    </button>
                                    @foreach ($categories as $category)
                                        <button @click="$wire.set('search_category', '{{ $category->id }}'); open = false"
                                            type="button"
                                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $search_category == $category->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                            @if ($category->icon)
                                                @php
                                                    $iconColor = match ($content_type) {
                                                        'auctions' => 'text-amber-600 dark:text-amber-400',
                                                        'giveaways' => 'text-green-600 dark:text-green-400',
                                                        'services' => 'text-slate-600 dark:text-slate-400',
                                                        default => 'text-sky-600 dark:text-sky-400',
                                                    };
                                                @endphp
                                                <i class="{{ $category->icon }} {{ $iconColor }} mr-2"></i>
                                            @endif
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Regular Subcategory Dropdown (Desktop) -->
                        @if ($search_category && count($subcategories) > 0)
                            <div class="w-56" x-data="{ open: false }" x-init="open = false">
                                <div class="relative">
                                    <button @click="open = !open" type="button"
                                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                        <span>
                                            @if ($search_subcategory)
                                                @php $selectedSubcat = $subcategories->firstWhere('id', $search_subcategory); @endphp
                                                {{ $selectedSubcat ? $selectedSubcat->name : 'Sve podkategorije' }}
                                            @else
                                                Sve podkategorije
                                            @endif
                                        </span>
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <button @click="$wire.set('search_subcategory', ''); open = false" type="button"
                                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$search_subcategory ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                            Sve podkategorije
                                        </button>
                                        @foreach ($subcategories as $subcategory)
                                            <button
                                                @click="$wire.set('search_subcategory', '{{ $subcategory->id }}'); open = false"
                                                type="button"
                                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $search_subcategory == $subcategory->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                                {{ $subcategory->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Center: Sort and Per Page -->
                <div class="flex items-center gap-3 md:px-4">
                    <!-- Sort Options -->
                    <div class="w-40" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($content_type === 'auctions')
                                        @switch($auction_type)
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

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                                @if ($content_type === 'auctions')
                                    <button @click="$wire.set('auction_type', 'newest'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                        Najnovije
                                    </button>
                                    <button @click="$wire.set('auction_type', 'ending_soon'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                        Završavaju uskoro
                                    </button>
                                    <button @click="$wire.set('auction_type', 'highest_price'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                        Najviša cena
                                    </button>
                                    <button @click="$wire.set('auction_type', 'most_bids'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                        Najviše ponuda
                                    </button>
                                    <button @click="$wire.set('auction_type', 'scheduled'); open = false" type="button"
                                        class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
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

                    <!-- Per Page -->
                    <div class="w-40" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                                <button @click="$wire.set('perPage', 20); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    20 po strani
                                </button>
                                <button @click="$wire.set('perPage', 50); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    50 po strani
                                </button>
                                <button @click="$wire.set('perPage', 100); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    100 po strani
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: View Mode -->
                <div class="flex items-center gap-3">
                    <!-- View Mode Toggle -->
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
        </div>

        <!-- Results -->
        @if ($results->count() > 0)
            @if ($viewMode === 'list')
                <!-- List View -->
                <div class="space-y-4">
                    @foreach ($results as $listing)
                        @if (isset($listing->is_auction))
                            @include('livewire.components.view-auction-component', [
                                'auction' => $listing->auction_data,
                                'viewMode' => 'list',
                            ])
                        @elseif($listing instanceof \App\Models\Service)
                            @include('livewire.components.view-service-component', [
                                'service' => $listing,
                                'viewMode' => 'list',
                            ])
                        @else
                            @include('livewire.components.view-listing-component', [
                                'listing' => $listing,
                                'viewMode' => 'list',
                            ])
                        @endif
                    @endforeach
                </div>
            @endif

            @if ($viewMode === 'grid')
                <!-- Grid View -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($results as $listing)
                        @if (isset($listing->is_auction))
                            @include('livewire.components.view-auction-component', [
                                'auction' => $listing->auction_data,
                                'viewMode' => 'grid',
                            ])
                        @elseif($listing instanceof \App\Models\Service)
                            @include('livewire.components.view-service-component', [
                                'service' => $listing,
                                'viewMode' => 'grid',
                            ])
                        @else
                            @include('livewire.components.view-listing-component', [
                                'listing' => $listing,
                                'viewMode' => 'grid',
                            ])
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            @if ($results->hasPages())
                <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                    {{ $results->links() }}
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-search text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Nema rezultata</h3>
                <p class="text-slate-600 dark:text-slate-300 mb-4">
                    @if ($query)
                        Nema rezultata za pretragu "{{ $query }}"
                    @else
                        Pokušajte da promenite kriterijume pretrage
                    @endif
                </p>
                <button onclick="window.location.href = '{{ route('search.unified') }}'"
                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                    Poništi sve filtere
                </button>
            </div>
        @endif

        @if ($content_type === 'auctions' && !isset($skipScheduledAuctions) && !empty($scheduledAuctions))
            <!-- Scheduled Auctions Section -->
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        @foreach ($scheduledAuctions as $auction)
                            <div
                                class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-amber-700">
                                <!-- Image -->
                                <div class="relative h-48">
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
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                        {{ $auction->listing->title }}
                                    </h3>

                                    {{-- Prodavac info --}}
                                    @auth
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                            Prodavac: {{ $auction->listing->user->name ?? 'Nepoznat korisnik' }}
                                            @if ($auction->listing->user)
                                                {!! $auction->listing->user->verified_icon !!}
                                            @endif
                                        </p>
                                    @endauth

                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $auction->listing->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-folder mr-1"></i>
                                        <span>{{ $auction->listing->category->name }}</span>
                                    </div>

                                    <p class="text-slate-700 dark:text-slate-200 mb-3 text-sm"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($auction->listing->description), 80) }}
                                    </p>

                                    <div class="mb-3">
                                        <div class="text-sm text-slate-500 dark:text-slate-300">Početna cena:</div>
                                        <div class="text-lg font-bold text-sky-600 dark:text-sky-400">
                                            {{ number_format($auction->starting_price, 0, ',', '.') }} RSD
                                        </div>
                                    </div>

                                    @if ($auction->buy_now_price)
                                        <div class="mb-3">
                                            <div class="text-sm text-slate-500 dark:text-slate-300">Kupi odmah:</div>
                                            <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                                {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bottom section with time and button -->
                                <div
                                    class="p-4 border-t border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-slate-700">
                                    <div class="text-center mb-3">
                                        <div class="text-sm font-bold text-amber-700 dark:text-amber-400">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Počinje za:
                                        </div>
                                        <div class="text-lg font-bold text-amber-700 dark:text-amber-300">
                                            {{ \App\Helpers\DateHelper::diffForHumansSr($auction->starts_at) }}
                                        </div>
                                        <div class="text-xs text-amber-700 dark:text-amber-400 mt-1">
                                            {{ $auction->starts_at->format('d.m.Y \\u H:i') }}
                                        </div>
                                    </div>

                                    @auth
                                        @if (auth()->id() === $auction->user_id)
                                            <a href="{{ route('listings.edit', $auction->listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm font-medium">
                                                <i class="fas fa-gavel mr-2"></i> Uredi
                                            </a>
                                        @else
                                            <a href="{{ route('auction.show', $auction) }}"
                                                class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm font-medium">
                                                <i class="fas fa-eye mr-2"></i> Detalji
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('auction.show', $auction) }}"
                                            class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm font-medium">
                                            <i class="fas fa-eye mr-2"></i> Detalji
                                        </a>
                                    @endauth
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

                                                {{-- Prodavac info --}}
                                                @auth
                                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                                        Prodavac: {{ $auction->listing->user->name ?? 'Nepoznat korisnik' }}
                                                        @if ($auction->listing->user)
                                                            {!! $auction->listing->user->verified_icon !!}
                                                        @endif
                                                        @if ($auction->listing->user && $auction->listing->user->is_banned)
                                                            <span
                                                                class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                                        @endif
                                                        @if ($auction->listing->user && $auction->listing->user->shouldShowLastSeen())
                                                            <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                                                @if ($auction->listing->user->is_online)
                                                                    <span class="inline-flex items-center">
                                                                        <span
                                                                            class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                                        {{ $auction->listing->user->last_seen }}
                                                                    </span>
                                                                @else
                                                                    {{ $auction->listing->user->last_seen }}
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </p>
                                                @endauth

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
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-sm text-slate-500 dark:text-slate-300">Početna cena:
                                                    </div>
                                                    <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                                        {{ number_format($auction->starting_price, 0, ',', '.') }} RSD
                                                    </div>
                                                </div>

                                                @if ($auction->buy_now_price)
                                                    <div class="text-right">
                                                        <div class="text-sm text-slate-500 dark:text-slate-300">Kupi odmah:
                                                        </div>
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
                                        class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-slate-700">
                                        <div class="flex flex-col h-full justify-between">
                                            <div class="text-center mb-4">
                                                <div class="text-sm font-bold text-amber-700 dark:text-amber-400">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    Počinje za:
                                                </div>
                                                <div class="text-lg font-bold text-amber-700 dark:text-amber-300">
                                                    {{ \App\Helpers\DateHelper::diffForHumansSr($auction->starts_at) }}
                                                </div>
                                                <div class="text-xs text-amber-700 dark:text-amber-400 mt-1">
                                                    {{ $auction->starts_at->format('d.m.Y \\u H:i') }}
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                @auth
                                                    @if (auth()->id() === $auction->user_id)
                                                        <a href="{{ route('listings.edit', $auction->listing) }}"
                                                            class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm">
                                                            <i class="fas fa-gavel mr-2"></i> Uredi aukciju
                                                        </a>
                                                    @else
                                                        <a href="{{ route('auction.show', $auction) }}"
                                                            class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm">
                                                            <i class="fas fa-eye mr-2"></i> Pogledaj detalje
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('auction.show', $auction) }}"
                                                        class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm">
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

        @if ($content_type === 'auctions' && $endedAuctions->count() > 0)
            <!-- Ended Auctions Section -->
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Završene aukcije</h2>
                        <p class="text-slate-600 dark:text-slate-400">Poslednje završene aukcije</p>
                    </div>
                    <span
                        class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full text-sm">
                        {{ $endedAuctions->count() }} završenih
                    </span>
                </div>

                @if ($viewMode === 'grid')
                    <!-- Grid View -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        @foreach ($endedAuctions as $auction)
                            <div
                                class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden border-l-4 border-amber-700">
                                <!-- Image -->
                                <div class="relative h-48">
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
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                        {{ $auction->listing->title }}
                                    </h3>

                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $auction->listing->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-folder mr-1"></i>
                                        <span>{{ $auction->listing->category->name }}</span>
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

                                    <div class="mt-3">
                                        <div class="text-lg font-bold text-slate-600 dark:text-slate-400">
                                            {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $auction->total_bids }} ponuda
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom section -->
                                <div
                                    class="p-4 border-t border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-slate-700">
                                    <div class="text-center mb-3">
                                        <div class="text-sm font-bold text-amber-700 dark:text-amber-400">
                                            <i class="fas fa-flag-checkered mr-1"></i>
                                            Završeno
                                        </div>
                                        <div class="text-xs text-amber-700 dark:text-amber-400">
                                            {{ $auction->ends_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <a href="{{ route('auction.show', $auction) }}"
                                        class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm font-medium">
                                        <i class="fas fa-eye mr-2"></i> Rezultati
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

                                                {{-- Prodavac info --}}
                                                @auth
                                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                                        Prodavac: {{ $auction->seller->name ?? 'Nepoznat korisnik' }}
                                                        @if ($auction->seller)
                                                            {!! $auction->seller->verified_icon !!}
                                                        @endif
                                                        @if ($auction->seller && $auction->seller->is_banned)
                                                            <span
                                                                class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                                        @endif
                                                        @if ($auction->seller && $auction->seller->shouldShowLastSeen())
                                                            <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                                                @if ($auction->seller->is_online)
                                                                    <span class="inline-flex items-center">
                                                                        <span
                                                                            class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                                        {{ $auction->seller->last_seen }}
                                                                    </span>
                                                                @else
                                                                    {{ $auction->seller->last_seen }}
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </p>
                                                @endauth

                                                <div
                                                    class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    <span>{{ $auction->listing->location }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-folder mr-1"></i>
                                                    <span>{{ $auction->listing->category->name }}</span>
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
                                                    <div class="text-sm text-slate-500 dark:text-slate-300">Završeno:</div>
                                                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400">
                                                        {{ $auction->ends_at->format('d.m.Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sidebar -->
                                    <div
                                        class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-amber-50 dark:bg-slate-700">
                                        <div class="flex flex-col h-full justify-between">
                                            <div class="text-center mb-4">
                                                <div class="text-lg font-bold text-amber-700 dark:text-amber-400">
                                                    <i class="fas fa-flag-checkered mr-1"></i>
                                                    Završeno
                                                </div>
                                                <div class="text-xs text-amber-700 dark:text-amber-400">
                                                    {{ \App\Helpers\DateHelper::diffForHumansSr($auction->ends_at) }}
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <a href="{{ route('auction.show', $auction) }}"
                                                    class="block w-full text-center px-3 py-2 bg-amber-600 dark:bg-amber-700 text-white rounded-lg hover:bg-amber-700 dark:hover:bg-amber-800 transition-colors text-sm">
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
