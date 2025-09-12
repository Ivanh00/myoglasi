<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Pretraga</h1>
        <p class="text-gray-600">
            @if($content_type === 'auctions')
                Rezultati pretrage aukcija
            @elseif($content_type === 'listings')
                Rezultati pretrage oglasa
            @else
                Rezultati pretrage oglasa i aukcija
            @endif
        </p>
    </div>

    <!-- Filter Summary -->
    @php
        $activeFilters = array_filter([
            $query ? "'{$query}'" : null,
            $city ? "Grad: {$city}" : null,
            $search_category ? "Kategorija: " . ($categories->firstWhere('id', $search_category)->name ?? 'N/A') : null,
            $condition_id ? "Stanje: " . ($conditions->firstWhere('id', $condition_id)->name ?? 'N/A') : null,
            $auction_type ? "Aukcije: " . ['ending_soon' => 'Završavaju uskoro', 'newest' => 'Najnovije', 'highest_price' => 'Najviša cena', 'most_bids' => 'Najviše ponuda'][$auction_type] : null,
            $price_min ? "Cena od: " . number_format($price_min, 0, ',', '.') . " RSD" : null,
            $price_max ? "Cena do: " . number_format($price_max, 0, ',', '.') . " RSD" : null
        ]);
    @endphp

    @if(!empty($activeFilters))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-blue-900 mb-2">Aktivni filteri:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($activeFilters as $filter)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $filter }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <button onclick="window.location.href = '{{ route('search.unified') }}'"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>
                    Poništi sve filtere
                </button>
            </div>
        </div>
    @endif

    <!-- Results and Controls -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-gray-600">
                Pronađeno: <span class="font-semibold">{{ $results->total() }}</span>
                @if($content_type === 'all')
                    rezultata (oglasi i aukcije)
                @elseif($content_type === 'auctions')
                    aukcija
                @else
                    oglasa
                @endif
            </div>
            
            <!-- Content Type Selector -->
            <div class="flex items-center space-x-2">
                <button wire:click="$set('content_type', 'all')" 
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'all' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Sve
                </button>
                <button wire:click="$set('content_type', 'listings')" 
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'listings' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Oglasi
                </button>
                <button wire:click="$set('content_type', 'services')" 
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'services' ? 'bg-gray-100 text-gray-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-tools mr-1"></i>
                    Usluge
                </button>
                <button wire:click="$set('content_type', 'giveaways')" 
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'giveaways' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-gift mr-1"></i>
                    Pokloni
                </button>
                <button wire:click="$set('content_type', 'auctions')" 
                    class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ $content_type === 'auctions' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }}">
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
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                            <span>
                                @switch($sortBy)
                                    @case('newest') Najnovije @break
                                    @case('price_asc') Cena ↑ @break
                                    @case('price_desc') Cena ↓ @break
                                    @default Najnovije
                                @endswitch
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                Najnovije
                            </button>
                            <button @click="$wire.set('sortBy', 'price_asc'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                Cena ↑
                            </button>
                            <button @click="$wire.set('sortBy', 'price_desc'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg">
                                Cena ↓
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Per Page -->
                <div class="w-32" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                20
                            </button>
                            <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                50
                            </button>
                            <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg">
                                100
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: View Mode Toggle -->
            <div class="flex bg-white border border-gray-300 rounded-lg shadow-sm">
                <button wire:click="setViewMode('list')" 
                    class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-l-lg transition-colors">
                    <i class="fas fa-list"></i>
                </button>
                <button wire:click="setViewMode('grid')" 
                    class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-r-lg transition-colors">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Results -->
    @if($results->count() > 0)
        @if($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4">
                @foreach($results as $listing)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 
                        @if(isset($listing->is_auction))
                            border-l-4 border-yellow-500
                        @elseif($listing->isGiveaway())
                            border-l-4 border-green-500
                        @elseif($listing->isService())
                            border-l-4 border-gray-500
                        @else
                            border-l-4 border-blue-500
                        @endif">
                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="w-full md:w-48 md:min-w-48 h-48 relative">
                                <a href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : route('listings.show', $listing) }}">
                                    @if($listing->images->count() > 0)
                                        <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </a>
                                
                                <!-- Auction Badge -->
                                @if(isset($listing->is_auction))
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex items-center px-2 py-1 bg-yellow-500 bg-opacity-90 text-white text-xs font-medium rounded">
                                            <i class="fas fa-gavel mr-1"></i>
                                            Aukcija
                                        </span>
                                    </div>
                                    
                                    @if($listing->auction_data->time_left)
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
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
                                        <a href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : route('listings.show', $listing) }}">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                                {{ $listing->title }}
                                            </h3>
                                        </a>

                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $listing->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-folder mr-1"></i>
                                            <span>{{ $listing->category->name }}</span>
                                        </div>

                                        <p class="text-gray-700 mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($listing->description), 120) }}
                                        </p>

                                        <div class="text-sm text-gray-600 mb-2">
                                            Prodavac: <span class="font-medium">{{ $listing->user->name }}</span>
                                            {!! $listing->user->verified_icon !!}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if(isset($listing->is_auction))
                                                <div class="text-xl font-bold text-red-600">
                                                    {{ number_format($listing->auction_data->current_price, 0, ',', '.') }} RSD
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $listing->auction_data->total_bids }} ponuda</div>
                                            @elseif($listing->isGiveaway())
                                                <div class="text-xl font-bold text-green-600">BESPLATNO</div>
                                            @else
                                                <div class="text-xl font-bold text-blue-600">
                                                    {{ number_format($listing->price, 2, ',', '.') }} RSD
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if($listing->getTypeBadge())
                                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $listing->getTypeBadge()['class'] }}">
                                                    {{ $listing->getTypeBadge()['text'] }}
                                                </span>
                                            @endif
                                            
                                            @if($listing->condition)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                                    {{ $listing->condition->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-gray-200 
                                @if(isset($listing->is_auction))
                                    bg-yellow-50
                                @elseif($listing->isGiveaway())
                                    bg-green-50
                                @elseif($listing->isService())
                                    bg-gray-50
                                @else
                                    bg-blue-50
                                @endif">
                                <div class="flex flex-col h-full justify-between">
                                    @if(isset($listing->is_auction))
                                        <div class="text-center mb-4">
                                            <div class="text-lg font-bold text-yellow-600">
                                                @if($listing->auction_data->time_left)
                                                    {{ $listing->auction_data->time_left['formatted'] }}
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">vremena ostalo</div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span>{{ $listing->views ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span>❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500 mb-4">
                                        <i class="fas fa-clock mr-1"></i>
                                        Objavljeno {{ $listing->created_at->diffForHumans() }}
                                    </div>

                                    <div class="space-y-2">
                                        @if(isset($listing->is_auction))
                                            <a href="{{ route('auction.show', $listing->auction_data) }}"
                                                class="block w-full text-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                                <i class="fas fa-gavel mr-2"></i> Licitiraj
                                            </a>
                                            
                                            @if($listing->auction_data->buy_now_price && $listing->auction_data->current_price < $listing->auction_data->buy_now_price)
                                                <a href="{{ route('auction.show', $listing->auction_data) }}"
                                                    class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                    <i class="fas fa-shopping-cart mr-2"></i> Kupi odmah
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('listings.show', $listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                <i class="fas fa-eye mr-2"></i> Pregled
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($results as $listing)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 
                        @if(isset($listing->is_auction))
                            border-l-4 border-yellow-500
                        @elseif($listing->isGiveaway())
                            border-l-4 border-green-500
                        @elseif($listing->isService())
                            border-l-4 border-gray-500
                        @else
                            border-l-4 border-blue-500
                        @endif">
                        <!-- Image -->
                        <div class="w-full h-48 relative">
                            <a href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : route('listings.show', $listing) }}">
                                @if($listing->images->count() > 0)
                                    <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                            
                            <!-- Auction Badge -->
                            @if(isset($listing->is_auction))
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-500 bg-opacity-90 text-white text-xs font-medium rounded">
                                        <i class="fas fa-gavel mr-1"></i>
                                        Aukcija
                                    </span>
                                </div>
                                
                                @if($listing->auction_data->time_left)
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                            {{ $listing->auction_data->time_left['formatted'] }}
                                        </span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <a href="{{ isset($listing->is_auction) ? route('auction.show', $listing->auction_data) : route('listings.show', $listing) }}">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                    {{ Str::limit($listing->title, 40) }}
                                </h3>
                            </a>

                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>{{ $listing->location }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-folder mr-1"></i>
                                <span>{{ $listing->category->name }}</span>
                            </div>

                            <p class="text-gray-700 text-sm mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit(strip_tags($listing->description), 100) }}
                            </p>

                            <div class="text-sm text-gray-600 mb-3">
                                Prodavac: {{ $listing->user->name }}
                                {!! $listing->user->verified_icon !!}
                            </div>

                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    @if(isset($listing->is_auction))
                                        <div class="text-2xl font-bold text-red-600">
                                            {{ number_format($listing->auction_data->current_price, 0, ',', '.') }} RSD
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $listing->auction_data->total_bids }} ponuda</div>
                                    @elseif($listing->isGiveaway())
                                        <div class="text-2xl font-bold text-green-600">BESPLATNO</div>
                                    @else
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ number_format($listing->price, 2, ',', '.') }} RSD
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($listing->getTypeBadge())
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $listing->getTypeBadge()['class'] }}">
                                            {{ $listing->getTypeBadge()['text'] }}
                                        </span>
                                    @endif
                                    
                                    @if($listing->condition)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                            {{ $listing->condition->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Stats -->
                            @if(!isset($listing->is_auction))
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $listing->views ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span>❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="text-xs text-gray-500 mb-3">
                                <i class="fas fa-clock mr-1"></i>
                                Objavljeno {{ $listing->created_at->diffForHumans() }}
                            </div>

                            <!-- Action Button -->
                            @if(isset($listing->is_auction))
                                <div class="space-y-2">
                                    <a href="{{ route('auction.show', $listing->auction_data) }}"
                                        class="block w-full text-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                        <i class="fas fa-gavel mr-2"></i> Licitiraj
                                    </a>
                                    
                                    @if($listing->auction_data->buy_now_price && $listing->auction_data->current_price < $listing->auction_data->buy_now_price)
                                        <a href="{{ route('auction.show', $listing->auction_data) }}"
                                            class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                            <i class="fas fa-shopping-cart mr-2"></i> Kupi odmah
                                        </a>
                                    @endif
                                </div>
                            @else
                                <a href="{{ route('listings.show', $listing) }}"
                                    class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    <i class="fas fa-eye mr-2"></i> Pregled
                                </a>
                            @endif
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
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema rezultata</h3>
            <p class="text-gray-600 mb-4">
                Pokušajte sa drugačijim filterima ili ključnim rečima.
            </p>
            <button onclick="window.location.href = '{{ route('search.unified') }}'"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Poništi filtere
            </button>
        </div>
    @endif
</div>