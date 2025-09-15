@php
$urlParams = request()->all();
$hasFilters = !empty(array_filter([
    $urlParams['city'] ?? '',
    $urlParams['search_category'] ?? $urlParams['category'] ?? '',
    $urlParams['condition_id'] ?? $urlParams['condition'] ?? '',
    $urlParams['price_min'] ?? '',
    $urlParams['price_max'] ?? '',
    $urlParams['auction_type'] ?? '',
    $urlParams['content_type'] ?? ''
]));

// Check if filters should be open (only when explicitly requested)
$shouldShowFilters = !empty($urlParams['show_filters']) || !empty($urlParams['filters_open']);

// Don't auto-open filters on search results page
if (request()->routeIs('search.unified')) {
    $shouldShowFilters = false;
}

// Get category and condition names for display (check both possible parameter names)
$selectedCategoryName = '';
$categoryId = $urlParams['search_category'] ?? $urlParams['category'] ?? null;
if (!empty($categoryId)) {
    $selectedCat = \App\Models\Category::find($categoryId);
    $selectedCategoryName = $selectedCat ? $selectedCat->name : '';
}

$selectedConditionName = '';
$conditionId = $urlParams['condition_id'] ?? $urlParams['condition'] ?? null;
if (!empty($conditionId)) {
    $selectedCond = \App\Models\ListingCondition::find($conditionId);
    $selectedConditionName = $selectedCond ? $selectedCond->name : '';
}

// Get auction type name for display
$selectedAuctionTypeName = '';
$auctionType = $urlParams['auction_type'] ?? null;
if (!empty($auctionType)) {
    $auctionTypes = [
        'ending_soon' => 'Završavaju uskoro',
        'newest' => 'Najnovije aukcije',
        'highest_price' => 'Najviša cena',
        'most_bids' => 'Najviše ponuda'
    ];
    $selectedAuctionTypeName = $auctionTypes[$auctionType] ?? '';
}

@endphp

<div class="relative flex-1 max-w-4xl mx-4" x-data="{
    showFilters: {{ $shouldShowFilters ? 'true' : 'false' }},
    query: '{{ $urlParams['query'] ?? '' }}',
    city: '{{ $urlParams['city'] ?? '' }}',
    category: '{{ $categoryId ?? '' }}',
    categoryName: '{{ $selectedCategoryName }}',
    condition: '{{ $conditionId ?? '' }}',
    conditionName: '{{ $selectedConditionName }}',
    auction_type: '{{ $auctionType ?? '' }}',
    auctionTypeName: '{{ $selectedAuctionTypeName }}',
    content_type: '{{ $urlParams['content_type'] ?? 'all' }}',
    price_min: '{{ $urlParams['price_min'] ?? '' }}',
    price_max: '{{ $urlParams['price_max'] ?? '' }}',
    citySearch: '',
    
    get filteredCities() {
        const normalize = (str) => {
            const map = {'š': 's', 'ć': 'c', 'č': 'c', 'ž': 'z', 'đ': 'dj'};
            return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
        };
        return @js(config('cities', [])).filter(c =>
            normalize(c).includes(normalize(this.citySearch || ''))
        );
    },
    
    toggleFilters() {
        this.showFilters = !this.showFilters;
    },
    
    resetFilters() {
        this.query = '';
        this.city = '';
        this.category = '';
        this.categoryName = '';
        this.condition = '';
        this.conditionName = '';
        this.auction_type = '';
        this.auctionTypeName = '';
        this.content_type = 'all';
        this.price_min = '';
        this.price_max = '';
        this.citySearch = '';
        this.submitSearch();
    },
    
    // Update state when URL changes (after form submission)  
    syncFromUrl() {
        // Read from URL and update names
        const urlParams = new URLSearchParams(window.location.search);
        
        this.query = urlParams.get('query') || '';
        this.city = urlParams.get('city') || '';
        this.category = urlParams.get('search_category') || urlParams.get('category') || '';
        this.condition = urlParams.get('condition_id') || urlParams.get('condition') || '';
        this.auction_type = urlParams.get('auction_type') || '';
        this.content_type = urlParams.get('content_type') || 'all';
        this.price_min = urlParams.get('price_min') || '';
        this.price_max = urlParams.get('price_max') || '';
        
        // Get the mapping data
        const categoryMap = @js(\App\Models\Category::whereNull('parent_id')->where('is_active', true)->get()->keyBy('id')->map(fn($c) => $c->name)->toArray());
        const conditionMap = @js(\App\Models\ListingCondition::where('is_active', true)->get()->keyBy('id')->map(fn($c) => $c->name)->toArray());
        
        // Update category name
        if (this.category) {
            this.categoryName = categoryMap[this.category] || '';
        } else {
            this.categoryName = '';
        }
        
        // Update condition name
        if (this.condition) {
            this.conditionName = conditionMap[this.condition] || '';
        } else {
            this.conditionName = '';
        }
        
        // Update auction type name
        const auctionTypeMap = {
            'ending_soon': 'Završavaju uskoro',
            'newest': 'Najnovije aukcije', 
            'highest_price': 'Najviša cena',
            'most_bids': 'Najviše ponuda'
        };
        if (this.auction_type) {
            this.auctionTypeName = auctionTypeMap[this.auction_type] || '';
        } else {
            this.auctionTypeName = '';
        }
    },
    
    selectCategory(id, name) {
        this.category = id;
        this.categoryName = name;
    },
    
    selectCondition(id, name) {
        this.condition = id;
        this.conditionName = name;
    },
    
    selectAuctionType(type, name) {
        this.auction_type = type;
        this.auctionTypeName = name;
    },
    
    quickSearch() {
        // Quick search with only query parameter, no other filters
        const params = new URLSearchParams();
        if (this.query) {
            params.set('query', this.query);
            params.set('content_type', 'all'); // Search both listings and auctions
        }
        
        const url = '{{ route('search.unified') }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    },
    
    submitSearch() {
        // Build URL with parameters using correct parameter names
        const params = new URLSearchParams();
        if (this.query) params.set('query', this.query);
        if (this.city) params.set('city', this.city);
        if (this.category) params.set('search_category', this.category); // Changed to match backend
        if (this.condition) params.set('condition_id', this.condition); // Changed to match backend  
        if (this.auction_type) params.set('auction_type', this.auction_type);
        if (this.content_type && this.content_type !== 'all') params.set('content_type', this.content_type);
        if (this.price_min) params.set('price_min', this.price_min);
        if (this.price_max) params.set('price_max', this.price_max);
        
        // Always keep filter panel open after search if any filters are set
        if (this.hasActiveFilters()) {
            params.set('show_filters', '1');
        }
        
        const url = '{{ route('search.unified') }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    },
    
    hasActiveFilters() {
        return this.city || this.category || this.condition || this.auction_type || this.content_type !== 'all' || this.price_min || this.price_max;
    }
}" 
x-init="syncFromUrl()">
    <!-- Main Search Bar -->
    <div class="bg-white dark:bg-gray-700 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-600 dark:border-gray-600 overflow-hidden">
        <div class="flex">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <form @submit.prevent="quickSearch()">
                    <input type="text" x-model="query" 
                        class="block w-full pl-10 pr-28 py-3 border-0 bg-transparent text-gray-900 dark:text-gray-100 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-0 text-sm"
                        placeholder="Pretraži oglase...">
                </form>
                
                <!-- Integrated Detaljno Button (inside search bar) -->
                <div class="absolute top-1 bottom-1 right-0 flex items-center pr-1">
                    <button type="button" @click="toggleFilters()" 
                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded text-sm font-medium transition-colors focus:outline-none hover:bg-blue-700 mr-1"
                        :class="showFilters ? 'bg-blue-700' : 'bg-blue-600'">
                        <span>Detaljno</span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span x-show="hasActiveFilters()" class="ml-2 px-1.5 py-0.5 bg-white dark:bg-gray-200 text-blue-600 dark:text-blue-700 rounded-full text-xs font-bold min-w-[20px] h-5 flex items-center justify-center"
                            x-text="(city ? 1 : 0) + (category ? 1 : 0) + (condition ? 1 : 0) + (auction_type ? 1 : 0) + (content_type !== 'all' ? 1 : 0) + (price_min ? 1 : 0) + (price_max ? 1 : 0)"></span>
                    </button>
                </div>
            </div>
            
            <button type="button" @click="quickSearch()"
                class="inline-flex items-center px-4 py-3 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none transition-colors"
                title="Pretraži">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Expanded Filters (KupujemProdajem style) -->
    <div x-show="showFilters" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" 
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-150" 
        x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95"
        @click.away="showFilters = false"
        class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-700 dark:bg-gray-800 rounded-lg shadow-lg border border-gray-300 dark:border-gray-600 dark:border-gray-600 z-50 p-6"
        style="display: none;">
        
        <!-- Content Type Selector -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Pretražuj u:</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="all" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-500 dark:checked:bg-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sve</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="listings" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-500 dark:checked:bg-blue-500">
                    <span class="ml-2 text-sm text-blue-600 dark:text-blue-400">
                        <i class="fas fa-list mr-1"></i>
                        Oglasi
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="auctions" 
                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-500 dark:checked:bg-yellow-500">
                    <span class="ml-2 text-sm text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-gavel mr-1"></i>
                        Aukcije
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="services" 
                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-500 dark:checked:bg-gray-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-tools mr-1"></i>
                        Usluge
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="giveaways" 
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-500 dark:checked:bg-green-500">
                    <span class="ml-2 text-sm text-green-700 dark:text-green-400">
                        <i class="fas fa-gift mr-1"></i>
                        Poklanjam
                    </span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Location, Category & Condition -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wide">Lokacija i kategorija</h4>
                
                <!-- City (full width) -->
                <div x-data="{ cityOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Grad/Mesto</label>
                    <button type="button" @click="cityOpen = !cityOpen"
                        class="w-full flex justify-between items-center border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span x-text="city || 'Odaberi grad'" :class="city ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500'"></span>
                        <svg class="w-4 h-4 transition-transform" :class="cityOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="cityOpen" x-transition @click.away="cityOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-2 border-b">
                            <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="p-1">
                            <template x-for="cityOption in filteredCities" :key="cityOption">
                                <button type="button" @click="city = cityOption; cityOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                                    :class="city === cityOption ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                    <span x-text="cityOption"></span>
                                </button>
                            </template>
                            <div x-show="filteredCities.length === 0" class="text-center text-gray-500 py-3 text-sm">
                                Nema rezultata
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category (full width) -->
                <div x-data="{ categoryOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kategorija</label>
                    <button type="button" @click="categoryOpen = !categoryOpen"
                        class="w-full flex justify-between items-center border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span :class="category ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500'">
                            @if(!empty($selectedCategoryName))
                                {{ $selectedCategoryName }}
                            @else
                                <span x-text="categoryName || 'Sve kategorije'"></span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="categoryOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="categoryOpen" x-transition @click.away="categoryOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectCategory('', ''); categoryOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition flex items-center"
                                :class="!category ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <span>Sve kategorije</span>
                            </button>
                            @foreach(\App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get() as $cat)
                                <button type="button" @click="selectCategory('{{ $cat->id }}', '{{ $cat->name }}'); categoryOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition flex items-center"
                                    :class="category === '{{ $cat->id }}' ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                    @if($cat->icon)
                                        <i class="{{ $cat->icon }} text-blue-600 mr-2"></i>
                                    @else
                                        <i class="fas fa-folder text-blue-600 mr-2"></i>
                                    @endif
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Middle Column: Price & Condition -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wide">Cena i stanje</h4>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cena od</label>
                        <input type="number" x-model="price_min" placeholder="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cena do</label>
                        <input type="number" x-model="price_max" placeholder="∞"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <!-- Condition (full width) -->
                <div x-data="{ conditionOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Stanje</label>
                    <button type="button" @click="conditionOpen = !conditionOpen"
                        class="w-full flex justify-between items-center border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span :class="condition ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500'">
                            @if(!empty($selectedConditionName))
                                {{ $selectedConditionName }}
                            @else
                                <span x-text="conditionName || 'Sva stanja'"></span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="conditionOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="conditionOpen" x-transition @click.away="conditionOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectCondition('', ''); conditionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                                :class="!condition ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <span>Sva stanja</span>
                            </button>
                            @foreach(\App\Models\ListingCondition::where('is_active', true)->orderBy('name')->get() as $cond)
                                <button type="button" @click="selectCondition('{{ $cond->id }}', '{{ $cond->name }}'); conditionOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                                    :class="condition === '{{ $cond->id }}' ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                    {{ $cond->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Column: Auctions (Yellow Section) -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-yellow-600 dark:text-yellow-400 uppercase tracking-wide">AUKCIJE</h4>
                
                <!-- Auction Sort Type -->
                <div x-data="{ auctionOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-yellow-600 dark:text-yellow-400 mb-1">Sortiranje aukcija</label>
                    <button type="button" @click="auctionOpen = !auctionOpen"
                        class="w-full flex justify-between items-center border border-yellow-300 dark:border-yellow-600 bg-yellow-100 dark:bg-yellow-800 dark:bg-yellow-900 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <span :class="auction_type ? 'text-yellow-900 dark:text-yellow-200' : 'text-yellow-600 dark:text-yellow-300'">
                            @if(!empty($selectedAuctionTypeName))
                                {{ $selectedAuctionTypeName }}
                            @else
                                <span x-text="auctionTypeName || 'Sve aukcije'"></span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform text-yellow-600" :class="auctionOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="auctionOpen" x-transition @click.away="auctionOpen = false"
                        class="absolute z-10 mt-1 w-full bg-yellow-100 dark:bg-yellow-800 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectAuctionType('', ''); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-yellow-100 dark:hover:bg-yellow-800 transition flex items-center"
                                :class="!auction_type ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-600 dark:text-yellow-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <i class="fas fa-list text-gray-600 mr-2"></i>
                                <span>Sve aukcije</span>
                            </button>
                            <button type="button" @click="selectAuctionType('ending_soon', 'Završavaju uskoro'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-yellow-100 dark:hover:bg-yellow-800 transition flex items-center"
                                :class="auction_type === 'ending_soon' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-600 dark:text-yellow-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <i class="fas fa-clock text-red-600 mr-2"></i>
                                <span>Završavaju uskoro</span>
                            </button>
                            <button type="button" @click="selectAuctionType('newest', 'Najnovije aukcije'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-yellow-100 dark:hover:bg-yellow-800 transition flex items-center"
                                :class="auction_type === 'newest' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-600 dark:text-yellow-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <i class="fas fa-plus text-green-600 mr-2"></i>
                                <span>Najnovije aukcije</span>
                            </button>
                            <button type="button" @click="selectAuctionType('highest_price', 'Najviša cena'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-yellow-100 dark:hover:bg-yellow-800 transition flex items-center"
                                :class="auction_type === 'highest_price' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-600 dark:text-yellow-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <i class="fas fa-money-bill text-green-600 mr-2"></i>
                                <span>Najviša cena</span>
                            </button>
                            <button type="button" @click="selectAuctionType('most_bids', 'Najviše ponuda'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-yellow-100 dark:hover:bg-yellow-800 transition flex items-center"
                                :class="auction_type === 'most_bids' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-600 dark:text-yellow-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                <i class="fas fa-gavel text-orange-600 mr-2"></i>
                                <span>Najviše ponuda</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="text-xs text-yellow-800 dark:text-yellow-200 p-2 bg-yellow-100 dark:bg-yellow-800 border border-yellow-200 dark:border-yellow-600 rounded">
                    <i class="fas fa-info-circle mr-1"></i>
                    Ostavi prazno za prikaz svih oglasa, izaberi opciju za filtriranje samo aukcija
                </div>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <button type="button" @click="resetFilters()" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Resetuj filtere
            </button>
            
            <div class="flex space-x-3">
                <button type="button" @click="showFilters = false" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    Sakrij filtere
                </button>
                <button type="button" @click="submitSearch()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    <span>Primeni filtere</span>
                    <span x-show="hasActiveFilters()" class="ml-1 px-1.5 py-0.5 bg-white dark:bg-gray-200 text-blue-600 dark:text-blue-700 rounded-full text-xs font-bold min-w-[20px] h-5 flex items-center justify-center"
                        x-text="(city ? 1 : 0) + (category ? 1 : 0) + (condition ? 1 : 0) + (auction_type ? 1 : 0) + (content_type !== 'all' ? 1 : 0) + (price_min ? 1 : 0) + (price_max ? 1 : 0)"></span>
                </button>
            </div>
        </div>
    </div>
</div>