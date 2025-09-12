<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <div class="text-gray-600 mb-4">
                Aktivnih aukcija: <span class="font-semibold">{{ $auctions->total() }}</span>
            </div>
            
            <div class="flex items-center justify-between gap-4">
                <!-- Sort Options -->
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-yellow-500 transition-colors flex items-center justify-between">
                            <span>
                                @switch($sortBy)
                                    @case('ending_soon') Završavaju uskoro @break
                                    @case('newest') Najnovije @break  
                                    @case('highest_price') Najviša cena @break
                                    @case('most_bids') Najviše ponuda @break
                                @endswitch
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('sortBy', 'ending_soon'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                Završavaju uskoro
                            </button>
                            <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                Najnovije
                            </button>
                            <button @click="$wire.set('sortBy', 'highest_price'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                Najviša cena
                            </button>
                            <button @click="$wire.set('sortBy', 'most_bids'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg">
                                Najviše ponuda
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Per page -->
                <div class="w-32" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-yellow-500 transition-colors flex items-center justify-between">
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
                        </div>
                    </div>
                </div>

                <!-- View Toggle -->
                <div class="flex bg-white border border-gray-300 rounded-lg shadow-sm">
                    <button wire:click="setViewMode('list')" 
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-l-lg transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                    <button wire:click="setViewMode('grid')" 
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-r-lg transition-colors">
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
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-yellow-500 transition-colors flex items-center justify-between">
                            <span>
                                @switch($sortBy)
                                    @case('ending_soon') Završavaju uskoro @break
                                    @case('newest') Najnovije @break  
                                    @case('highest_price') Najviša cena @break
                                    @case('most_bids') Najviše ponuda @break
                                @endswitch
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('sortBy', 'ending_soon'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                Završavaju uskoro
                            </button>
                            <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                Najnovije
                            </button>
                            <button @click="$wire.set('sortBy', 'highest_price'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                Najviša cena
                            </button>
                            <button @click="$wire.set('sortBy', 'most_bids'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg">
                                Najviše ponuda
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-yellow-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }} po strani</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                20 po strani
                            </button>
                            <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                50 po strani
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auction Listings -->
    @if($auctions->count() > 0)
        @if($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($auctions as $auction)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-yellow-500">
                        <!-- Image with overlay -->
                        <div class="relative">
                            <div class="w-full h-48">
                                @if($auction->listing->images->count() > 0)
                                    <img src="{{ $auction->listing->images->first()->url }}" alt="{{ $auction->listing->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Time badge -->
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                    @if($auction->time_left)
                                        {{ $auction->time_left['formatted'] }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ Str::limit($auction->listing->title, 40) }}</h3>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <div class="text-2xl font-bold text-red-600">
                                        {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $auction->total_bids }} ponuda
                                    </div>
                                </div>
                                @if($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Kupi odmah:</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <p class="text-gray-700 text-sm mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit(strip_tags($auction->listing->description), 100) }}
                            </p>

                            <div class="text-sm text-gray-600 mb-3">
                                Prodavac: {{ $auction->seller->name }}
                                {!! $auction->seller->verified_icon !!}
                            </div>

                            <div class="space-y-2">
                                <a href="{{ route('auction.show', $auction) }}"
                                    class="block w-full text-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                    <i class="fas fa-gavel mr-2"></i> Licitiraj
                                </a>
                                
                                @if($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                    <a href="{{ route('auction.show', $auction) }}"
                                        class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                        <i class="fas fa-shopping-cart mr-2"></i> Kupi odmah
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4 mb-8">
                @foreach($auctions as $auction)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-yellow-500">
                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="w-full md:w-48 md:min-w-48 h-48 relative">
                                @if($auction->listing->images->count() > 0)
                                    <img src="{{ $auction->listing->images->first()->url }}" alt="{{ $auction->listing->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Time overlay -->
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-1 bg-red-600 bg-opacity-90 text-white text-xs font-medium rounded">
                                        @if($auction->time_left)
                                            {{ $auction->time_left['formatted'] }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $auction->listing->title }}</h3>
                                        
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $auction->listing->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-folder mr-1"></i>
                                            <span>{{ $auction->listing->category->name }}</span>
                                        </div>

                                        <p class="text-gray-700 mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($auction->listing->description), 120) }}
                                        </p>

                                        <div class="text-sm text-gray-600 mb-2">
                                            Prodavac: <span class="font-medium">{{ $auction->seller->name }}</span>
                                            {!! $auction->seller->verified_icon !!}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-2xl font-bold text-red-600">
                                                {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $auction->total_bids }} ponuda</div>
                                        </div>
                                        
                                        @if($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                            <div class="text-right">
                                                <div class="text-sm text-gray-500">Kupi odmah:</div>
                                                <div class="text-lg font-bold text-green-600">
                                                    {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-gray-200 bg-yellow-50">
                                <div class="flex flex-col h-full justify-between">
                                    <div class="text-center mb-4">
                                        <div class="text-lg font-bold text-yellow-600">
                                            @if($auction->time_left)
                                                {{ $auction->time_left['formatted'] }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">vremena ostalo</div>
                                    </div>

                                    <div class="space-y-2">
                                        <a href="{{ route('auction.show', $auction) }}"
                                            class="block w-full text-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                            <i class="fas fa-gavel mr-2"></i> Licitiraj
                                        </a>
                                        
                                        @if($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                            <a href="{{ route('auction.show', $auction) }}"
                                                class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                <i class="fas fa-shopping-cart mr-2"></i> Kupi odmah
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

        <!-- Pagination -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-4">
            {{ $auctions->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gavel text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema aktivnih aukcija</h3>
            <p class="text-gray-600 mb-4">Trenutno nema aukcija na kojima možete licitirati.</p>
            <a href="{{ route('listings.index') }}"
                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                Pregledaj oglase
            </a>
        </div>
    @endif
</div>