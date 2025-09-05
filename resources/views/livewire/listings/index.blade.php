<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Mobile specific adjustments -->
    <style>
        @media (max-width: 768px) {
            .listing-card {
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100%;
                overflow: hidden;
            }
            .scrollbar-hide {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
        }
    </style>
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Svi oglasi</h1>
        <p class="text-gray-600">Pronađite najbolje ponude iz svih kategorija</p>
    </div>

    <!-- Desktop kategorije dropdown će biti u donjoj sekciji -->

    <!-- Mobile kategorija dropdown -->
    <div class="md:hidden mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategorija</label>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                    <span>
                        @if($selectedCategory)
                            @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                            {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                        @else
                            Sve kategorije
                        @endif
                    </span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                        class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg {{ !$selectedCategory ? 'bg-blue-50 text-blue-700' : '' }}">
                        Sve kategorije
                    </button>
                    @foreach ($categories as $category)
                        <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center {{ $selectedCategory == $category->id ? 'bg-blue-50 text-blue-700' : '' }}">
                            @if($category->icon)
                                <i class="{{ $category->icon }} text-blue-600 mr-2"></i>
                            @endif
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Filteri i sortiranje -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <!-- Results Info (Desktop/Mobile) -->
        <div class="text-center text-gray-600 mb-4">
            Pronađeno oglasa: <span class="font-semibold">{{ $listings->total() }}</span>
            @if ($selectedCategory)
                @if ($currentCategory)
                    u kategoriji: <span class="font-semibold">
                        @if ($currentCategory->parent)
                            {{ $currentCategory->parent->name }} / {{ $currentCategory->name }}
                        @else
                            {{ $currentCategory->name }}
                        @endif
                    </span>
                @endif
            @endif
        </div>
        
        <!-- Desktop Layout -->
        <div class="hidden md:flex md:items-center md:justify-between gap-4">
            <!-- Left: Category dropdown -->
            <div class="w-60" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                        <span>
                            @if($selectedCategory)
                                @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                                {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                            @else
                                Sve kategorije
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg {{ !$selectedCategory ? 'bg-blue-50 text-blue-700' : '' }}">
                            Sve kategorije
                        </button>
                        @foreach ($categories as $category)
                            <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center {{ $selectedCategory == $category->id ? 'bg-blue-50 text-blue-700' : '' }}">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} text-blue-600 mr-2"></i>
                                @endif
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Center: Filters -->
            <div class="flex items-center gap-3">
                <!-- Sortiranje -->
                <div class="w-40" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                            <span>
                                @if($sortBy === 'newest') Najnovije
                                @elseif($sortBy === 'price_asc') Cena ↑
                                @elseif($sortBy === 'price_desc') Cena ↓
                                @endif
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

                <!-- Per page -->
                <div class="w-32" x-data="{ open: false }">
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

        <!-- Mobile Layout -->
        <div class="md:hidden">
            <div class="flex gap-3">
                <!-- Mobile filters (50/50 split) -->
                <div class="flex-1" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                            <span>
                                @if($sortBy === 'newest') Najnovije
                                @elseif($sortBy === 'price_asc') Cena ↑
                                @elseif($sortBy === 'price_desc') Cena ↓
                                @endif
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

                <!-- Mobile per page -->
                <div class="flex-1" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
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
                            <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg">
                                100 po strani
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista oglasa -->
    @if ($listings->count() > 0)
        @if($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($listings as $listing)
                    <div class="listing-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Image -->
                        <div class="w-full h-48">
                            <a href="{{ route('listings.show', $listing) }}">
                                @if ($listing->images->count() > 0)
                                    <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <a href="{{ route('listings.show', $listing) }}">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                    {{ Str::limit($listing->title, 40) }}
                                </h3>
                            </a>

                            {{-- Seller info --}}
                            @auth
                                <p class="text-sm font-bold text-gray-700 mb-1">
                                    Prodavac: {{ $listing->user->name ?? 'Nepoznat korisnik' }}
                                    @if ($listing->user && $listing->user->is_banned)
                                        <span class="text-red-600 font-bold ml-1">BLOKIRAN</span>
                                    @endif
                                </p>
                                
                                {{-- User ratings --}}
                                @if($listing->user && $listing->user->total_ratings_count > 0)
                                    <a href="{{ route('user.ratings', $listing->user->id) }}" class="inline-flex items-center text-xs text-gray-600 mb-2 hover:text-blue-600 transition-colors">
                                        <span class="text-green-600 mr-1">😊 {{ $listing->user->positive_ratings_count }}</span>
                                        <span class="text-yellow-600 mr-1">😐 {{ $listing->user->neutral_ratings_count }}</span>
                                        <span class="text-red-600 mr-1">😞 {{ $listing->user->negative_ratings_count }}</span>
                                        @if($listing->user->rating_badge)
                                            <span class="ml-1">{{ $listing->user->rating_badge }}</span>
                                        @endif
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                @endif
                            @endauth

                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>{{ $listing->location }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-folder mr-1"></i>
                                <span>{{ $listing->category->name }}</span>
                            </div>

                            <p class="text-gray-700 text-sm mb-3"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit(strip_tags($listing->description), 100) }}
                            </p>

                            <div class="flex items-center justify-between mb-3">
                                <div class="text-blue-600 font-bold text-lg">
                                    {{ number_format($listing->price, 2) }} RSD
                                </div>

                                @if ($listing->condition)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                        {{ $listing->condition->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    <span>{{ $listing->views ?? 0 }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span>❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                </div>
                            </div>

                            <div class="text-xs text-gray-500 mb-3">
                                <i class="fas fa-clock mr-1"></i>
                                Postavljeno pre {{ floor($listing->created_at->diffInDays()) }} dana
                            </div>

                            <a href="{{ route('listings.show', $listing) }}"
                                class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-eye mr-2"></i> Pregled
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($viewMode === 'list')
            <!-- List View (Desktop) -->
            <div class="space-y-4 mb-8">
                @foreach ($listings as $listing)
                    <div class="listing-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="flex flex-col md:flex-row">
                        <!-- Slika oglasa - responsive -->
                        <div class="w-full md:w-48 md:min-w-48 h-48"> <!-- Full width na mobile -->
                            <a href="{{ route('listings.show', $listing) }}">
                                @if ($listing->images->count() > 0)
                                    <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover">
                                    <!-- object-cover osigurava da slika popuni kontejner -->
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Informacije o oglasu -->
                        <div class="flex-1 p-4 md:p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <a href="{{ route('listings.show', $listing) }}">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                            {{ $listing->title }}
                                        </h3>
                                    </a>

                                    {{-- Korisničko ime kreatora --}}
                                    @auth
                                        <p class="text-sm font-bold text-gray-700 mb-1">
                                            Prodavac: {{ $listing->user->name ?? 'Nepoznat korisnik' }}
                                            @if ($listing->user && $listing->user->is_banned)
                                                <span class="text-red-600 font-bold ml-2">BLOKIRAN</span>
                                            @endif
                                        </p>
                                        
                                        {{-- User ratings --}}
                                        @if($listing->user && $listing->user->total_ratings_count > 0)
                                            <a href="{{ route('user.ratings', $listing->user->id) }}" class="inline-flex items-center text-xs text-gray-600 mb-2 hover:text-blue-600 transition-colors">
                                                <span class="text-green-600 mr-1">😊 {{ $listing->user->positive_ratings_count }}</span>
                                                <span class="text-yellow-600 mr-1">😐 {{ $listing->user->neutral_ratings_count }}</span>
                                                <span class="text-red-600 mr-1">😞 {{ $listing->user->negative_ratings_count }}</span>
                                                @if($listing->user->rating_badge)
                                                    <span class="ml-1">{{ $listing->user->rating_badge }}</span>
                                                @endif
                                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                            </a>
                                        @endif
                                    @endauth

                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $listing->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-folder mr-1"></i>
                                        <span>{{ $listing->category->name }}</span>
                                    </div>

                                    <p class="text-gray-700 mb-3"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($listing->description), 120) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-blue-600 font-bold text-xl">
                                        {{ number_format($listing->price, 2) }} RSD
                                    </div>

                                    @if ($listing->condition)
                                        <span
                                            class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                            {{ $listing->condition->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Desna strana - akcije i dodatne informacije -->
                        <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-gray-200">
                            <div class="flex flex-col h-full justify-between">
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 4.5C7.5 4.5 3.5 8.5 2 12c1.5 3.5 5.5 7.5 10 7.5s8.5-4 10-7.5c-1.5-3.5-5.5-7.5-10-7.5zm0 12c-2.5 0-4.5-2-4.5-4.5S9.5 8.5 12 8.5 16.5 10.5 16.5 12 14.5 16.5 12 16.5zm0-7c-1.5 0-2.5 1-2.5 2.5S10.5 14.5 12 14.5 14.5 13.5 14.5 12 13.5 9.5 12 9.5z" />
                                        </svg>
                                        <span class="text-gray-700">{{ $listing->views ?? 0 }}</span>
                                    </div>
                                    <!-- Dodajte ovaj div za prikaz broja pratilaca -->
                                    <div class="flex items-center">
                                        <i class=""></i>
                                        <span class="text-gray-700">❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-700 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    Postavljeno pre {{ floor($listing->created_at->diffInDays()) }} dana
                                </div>

                                <div class="space-y-2">
                                    <a href="{{ route('listings.show', $listing) }}"
                                        class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        <i class="fas fa-eye mr-2"></i> Pregled
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @endif

        <!-- Paginacija -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-4">
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema oglasa</h3>
            <p class="text-gray-600 mb-4">
                @if ($selectedCategory)
                    Trenutno nema aktivnih oglasa u ovoj kategoriji.
                @else
                    Trenutno nema aktivnih oglasa.
                @endif
            </p>
            <a href="{{ route('listings.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Dodaj prvi oglas
            </a>
        </div>
    @endif
</div>
