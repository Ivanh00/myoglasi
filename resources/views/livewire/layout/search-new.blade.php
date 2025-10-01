@php
    $urlParams = request()->all();
    $hasFilters = !empty(
        array_filter([
            $urlParams['city'] ?? '',
            $urlParams['search_category'] ?? ($urlParams['category'] ?? ''),
            $urlParams['condition_id'] ?? ($urlParams['condition'] ?? ''),
            $urlParams['price_min'] ?? '',
            $urlParams['price_max'] ?? '',
            $urlParams['auction_type'] ?? '',
            $urlParams['content_type'] ?? '',
        ])
    );

    // Check if filters should be open (only when explicitly requested)
    $shouldShowFilters = !empty($urlParams['show_filters']) || !empty($urlParams['filters_open']);

    // Don't auto-open filters on search results page
if (request()->routeIs('search.unified')) {
    $shouldShowFilters = false;
}

// Get category and condition names for display (check both possible parameter names)
$selectedCategoryName = '';
$categoryId = $urlParams['search_category'] ?? ($urlParams['category'] ?? null);
if (!empty($categoryId)) {
    $selectedCat = \App\Models\Category::find($categoryId);
    $selectedCategoryName = $selectedCat ? $selectedCat->name : '';
}

$selectedConditionName = '';
$conditionId = $urlParams['condition_id'] ?? ($urlParams['condition'] ?? null);
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
        'most_bids' => 'Najviše ponuda',
        'scheduled' => 'Zakazane aukcije',
    ];
    $selectedAuctionTypeName = $auctionTypes[$auctionType] ?? '';
    }

@endphp

<div class="relative w-full" x-data="{
    showFilters: {{ $shouldShowFilters ? 'true' : 'false' }},
    query: '{{ $urlParams['query'] ?? '' }}',
    city: '{{ $urlParams['city'] ?? '' }}',
    category: '{{ $categoryId ?? '' }}',
    categoryName: '{{ $selectedCategoryName }}',
    subcategory: '{{ $urlParams['search_subcategory'] ?? '' }}',
    subcategoryName: '',
    subcategories: [],
    loadingSubcategories: false,
    serviceCategory: '{{ $urlParams['service_category'] ?? '' }}',
    serviceCategoryName: '',
    serviceSubcategory: '{{ $urlParams['service_subcategory'] ?? '' }}',
    serviceSubcategoryName: '',
    serviceSubcategories: [],
    loadingServiceSubcategories: false,
    condition: '{{ $conditionId ?? '' }}',
    conditionName: '{{ $selectedConditionName }}',
    auction_type: '{{ $auctionType ?? '' }}',
    auctionTypeName: '{{ $selectedAuctionTypeName }}',
    content_type: '{{ $urlParams['content_type'] ?? 'all' }}',
    price_min: '{{ $urlParams['price_min'] ?? '' }}',
    price_max: '{{ $urlParams['price_max'] ?? '' }}',
    citySearch: '',

    async init() {
        // Load subcategories on init if category is already selected
        if (this.category) {
            this.loadingSubcategories = true;
            try {
                const response = await fetch(`/api/subcategories/listings/${this.category}`);
                if (response.ok) {
                    this.subcategories = await response.json();
                }
            } catch (error) {
                console.error('Error loading subcategories:', error);
            } finally {
                this.loadingSubcategories = false;
            }
        }

        // Load service subcategories on init if service category is already selected
        if (this.serviceCategory) {
            this.loadingServiceSubcategories = true;
            try {
                const response = await fetch(`/api/subcategories/services/${this.serviceCategory}`);
                if (response.ok) {
                    this.serviceSubcategories = await response.json();
                }
            } catch (error) {
                console.error('Error loading service subcategories:', error);
            } finally {
                this.loadingServiceSubcategories = false;
            }
        }
    },

    get filteredCities() {
        const normalize = (str) => {
            const map = { 'š': 's', 'ć': 'c', 'č': 'c', 'ž': 'z', 'đ': 'dj' };
            return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
        };
        return @js(config('cities', [])).filter(c =>
            normalize(c).includes(normalize(this.citySearch || ''))
        );
    },

    toggleFilters() {
        this.showFilters = !this.showFilters;
        // Prevent body scroll on mobile when filters are open
        if (window.innerWidth < 768) {
            if (this.showFilters) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    },

    resetFilters() {
        this.query = '';
        this.city = '';
        this.category = '';
        this.categoryName = '';
        this.serviceCategory = '';
        this.serviceCategoryName = '';
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
        this.serviceCategory = urlParams.get('service_category') || '';
        this.condition = urlParams.get('condition_id') || urlParams.get('condition') || '';
        this.auction_type = urlParams.get('auction_type') || '';
        this.content_type = urlParams.get('content_type') || 'all';
        this.price_min = urlParams.get('price_min') || '';
        this.price_max = urlParams.get('price_max') || '';

        // Get the mapping data
        const categoryMap = @js(\App\Models\Category::whereNull('parent_id')->where('is_active', true)->get()->keyBy('id')->map(fn($c) => $c->name)->toArray());
        const serviceCategoryMap = @js(\App\Models\ServiceCategory::whereNull('parent_id')->where('is_active', true)->get()->keyBy('id')->map(fn($c) => $c->name)->toArray());
        const conditionMap = @js(\App\Models\ListingCondition::where('is_active', true)->get()->keyBy('id')->map(fn($c) => $c->name)->toArray());

        // Update category name
        if (this.category) {
            this.categoryName = categoryMap[this.category] || '';
        } else {
            this.categoryName = '';
        }

        // Update service category name
        if (this.serviceCategory) {
            this.serviceCategoryName = serviceCategoryMap[this.serviceCategory] || '';
        } else {
            this.serviceCategoryName = '';
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
            'most_bids': 'Najviše ponuda',
            'scheduled': 'Zakazane aukcije'
        };
        if (this.auction_type) {
            this.auctionTypeName = auctionTypeMap[this.auction_type] || '';
        } else {
            this.auctionTypeName = '';
        }
    },

    async selectCategory(id, name) {
        this.category = id;
        this.categoryName = name;
        // Reset subcategory when category changes
        this.subcategory = '';
        this.subcategoryName = '';
        this.subcategories = [];

        // Load subcategories if category is selected
        if (id) {
            this.loadingSubcategories = true;
            try {
                const response = await fetch(`/api/subcategories/listings/${id}`);
                if (response.ok) {
                    this.subcategories = await response.json();
                }
            } catch (error) {
                console.error('Error loading subcategories:', error);
            } finally {
                this.loadingSubcategories = false;
            }
        }
    },

    selectSubcategory(id, name) {
        this.subcategory = id;
        this.subcategoryName = name;
    },

    async selectServiceCategory(id, name) {
        this.serviceCategory = id;
        this.serviceCategoryName = name;
        // Reset service subcategory when category changes
        this.serviceSubcategory = '';
        this.serviceSubcategoryName = '';
        this.serviceSubcategories = [];

        // Load service subcategories if category is selected
        if (id) {
            this.loadingServiceSubcategories = true;
            try {
                const response = await fetch(`/api/subcategories/services/${id}`);
                if (response.ok) {
                    this.serviceSubcategories = await response.json();
                }
            } catch (error) {
                console.error('Error loading service subcategories:', error);
            } finally {
                this.loadingServiceSubcategories = false;
            }
        }
    },

    selectServiceSubcategory(id, name) {
        this.serviceSubcategory = id;
        this.serviceSubcategoryName = name;
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

        // Use appropriate category based on content type
        if (this.content_type === 'services') {
            if (this.serviceCategory) params.set('service_category', this.serviceCategory);
            if (this.serviceSubcategory) params.set('service_subcategory', this.serviceSubcategory);
        } else {
            if (this.category) params.set('search_category', this.category);
            if (this.subcategory) params.set('search_subcategory', this.subcategory);
        }

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
    },

    getActiveFilterCount() {
        const count = (this.city ? 1 : 0) +
            (this.category ? 1 : 0) +
            (this.condition ? 1 : 0) +
            (this.auction_type ? 1 : 0) +
            (this.content_type !== 'all' ? 1 : 0) +
            (this.price_min ? 1 : 0) +
            (this.price_max ? 1 : 0);

        // Update Alpine store
        if (Alpine.store('searchFilters')) {
            Alpine.store('searchFilters').updateCount(count);
        }

        return count;
    },

    updateFilterCount() {
        const count = this.getActiveFilterCount();
        if (Alpine.store('searchFilters')) {
            Alpine.store('searchFilters').updateCount(count);
        }
    }
}" x-init="
    syncFromUrl();

    // Watch for filter changes and update store
    $watch('city', () => updateFilterCount());
    $watch('category', () => updateFilterCount());
    $watch('serviceCategory', () => updateFilterCount());
    $watch('condition', () => updateFilterCount());
    $watch('auction_type', () => updateFilterCount());
    $watch('content_type', () => updateFilterCount());
    $watch('price_min', () => updateFilterCount());
    $watch('price_max', () => updateFilterCount());

    // Initial count update
    updateFilterCount();

    // Listen for browser back/forward navigation
    window.addEventListener('popstate', () => {
        syncFromUrl();
        updateFilterCount();
    });

    // Listen for URL changes from Livewire (when filters change on unified-search page)
    let lastUrl = window.location.href;
    const urlObserver = setInterval(() => {
        if (window.location.href !== lastUrl) {
            lastUrl = window.location.href;
            syncFromUrl();
            updateFilterCount();
        }
    }, 500);
">
    <!-- Main Search Bar -->
    <div
        class="bg-white dark:bg-slate-700 dark:bg-slate-800 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 dark:border-slate-600 overflow-hidden">
        <div class="flex">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <form @submit.prevent="quickSearch()">
                    <input type="text" x-model="query"
                        class="block w-full pl-10 pr-28 py-3 border-0 bg-transparent text-slate-900 dark:text-slate-100 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-0 text-sm"
                        placeholder="Pretraži oglase...">
                </form>

                <!-- Integrated Detaljno Button (inside search bar) -->
                <div class="absolute top-1 bottom-1 right-0 flex items-center pr-1">
                    <button type="button" @click="toggleFilters()"
                        class="inline-flex items-center px-3 py-2 bg-sky-600 dark:bg-sky-600 text-white rounded text-sm font-medium transition-colors focus:outline-none hover:bg-sky-700 dark:hover:bg-sky-700 mr-1"
                        :class="showFilters ? 'bg-sky-700 dark:bg-sky-700' : 'bg-sky-600 dark:bg-sky-600'">
                        <span>Detaljno</span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200"
                            :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span x-show="$store.searchFilters.activeCount > 0" class="ml-2 px-1.5 py-0.5 bg-white dark:bg-slate-200 text-sky-600 dark:text-sky-700 rounded-full text-xs font-bold min-w-[20px] h-5 flex items-center justify-center"
                            x-text="$store.searchFilters.activeCount"></span>
                    </button>
                </div>
            </div>

            <button type="button" @click="quickSearch()"
                class="inline-flex items-center px-4 py-3 bg-sky-600 dark:bg-sky-600 text-white hover:bg-sky-700 dark:hover:bg-sky-700 focus:outline-none transition-colors"
                title="Pretraži">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Overlay Background -->
    <div x-show="showFilters && window.innerWidth < 768" x-cloak x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="toggleFilters()"
        class="fixed inset-0 bg-black bg-opacity-50 z-[40] md:hidden" style="display: none;">
    </div>

    <!-- Expanded Filters (KupujemProdajem style) -->
    <div x-show="showFilters" x-cloak x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        @click.away="if(window.innerWidth >= 768) showFilters = false"
        class="fixed md:absolute top-[140px] md:top-full left-0 right-0 md:mt-2 bg-white dark:bg-slate-700 dark:bg-slate-800 md:rounded-lg shadow-lg border-t md:border border-slate-300 dark:border-slate-600 dark:border-slate-600 z-[90] md:z-[100] p-4 md:p-6 h-[calc(100vh-140px)] md:h-auto md:max-h-none overflow-y-auto md:overflow-visible"
        style="display: none;">

        <!-- Content Type Selector -->
        <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-3">Pretražuj u:</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="all"
                        class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-500 dark:checked:bg-sky-500">
                    <span class="ml-2 text-sm text-slate-700 dark:text-slate-200">Sve</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="listings"
                        class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-500 dark:checked:bg-sky-500">
                    <span class="ml-2 text-sm text-sky-600 dark:text-sky-400">
                        <i class="fas fa-list mr-1"></i>
                        Oglasi
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="auctions"
                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-500 dark:checked:bg-amber-500">
                    <span class="ml-2 text-sm text-amber-600 dark:text-amber-400">
                        <i class="fas fa-gavel mr-1"></i>
                        Aukcije
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="services"
                        class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-500 dark:checked:bg-slate-600">
                    <span class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                        <i class="fas fa-tools mr-1"></i>
                        Usluge
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="content_type" x-model="content_type" value="giveaways"
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-500 dark:checked:bg-green-500">
                    <span class="ml-2 text-sm text-green-700 dark:text-green-200">
                        <i class="fas fa-gift mr-1"></i>
                        Pokloni
                    </span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Location, Category & Condition -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 uppercase tracking-wide">Lokacija i
                    kategorija</h4>

                <!-- City (full width) -->
                <div x-data="{ cityOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Grad/Mesto</label>
                    <button type="button" @click="cityOpen = !cityOpen"
                        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <span x-text="city || 'Odaberi grad'"
                            :class="city ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300'"></span>
                        <svg class="w-4 h-4 transition-transform" :class="cityOpen ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="cityOpen" x-cloak x-transition @click.away="cityOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-2 border-b">
                            <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-sky-500">
                        </div>
                        <div class="p-1">
                            <template x-for="cityOption in filteredCities" :key="cityOption">
                                <button type="button" @click="city = cityOption; cityOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition"
                                    :class="city === cityOption ?
                                        'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-200 dark:text-sky-400 font-medium' :
                                        'text-slate-700 dark:text-slate-200'">
                                    <span x-text="cityOption"></span>
                                </button>
                            </template>
                            <div x-show="filteredCities.length === 0"
                                class="text-center text-slate-500 dark:text-slate-300 py-3 text-sm">
                                Nema rezultata
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category (full width) -->
                <div x-data="{ categoryOpen: false }" class="relative" x-show="content_type !== 'services'">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Kategorija</label>
                    <button type="button" @click="categoryOpen = !categoryOpen"
                        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <span
                            :class="category ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300'">
                            @if (!empty($selectedCategoryName))
                                {{ $selectedCategoryName }}
                            @else
                                <span x-text="categoryName || 'Sve kategorije'"></span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="categoryOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="categoryOpen" x-cloak x-transition @click.away="categoryOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectCategory('', ''); categoryOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center"
                                :class="!category ?
                                    'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                    'text-slate-700 dark:text-slate-200'">
                                <span>Sve kategorije</span>
                            </button>
                            @foreach (\App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get() as $cat)
                                <button type="button"
                                    @click="selectCategory('{{ $cat->id }}', '{{ $cat->name }}'); categoryOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center"
                                    :class="category === '{{ $cat->id }}' ?
                                        'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                        'text-slate-700 dark:text-slate-300'">
                                    @if ($cat->icon)
                                        <i class="{{ $cat->icon }} text-sky-600 dark:text-sky-400 mr-2"></i>
                                    @else
                                        <i class="fas fa-folder text-sky-600 dark:text-sky-400 mr-2"></i>
                                    @endif
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Subcategory (full width) -->
                <div x-data="{ subcategoryOpen: false }" class="relative" x-show="content_type !== 'services' && category">
                    <label
                        class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Podkategorija</label>
                    <button type="button" @click="subcategoryOpen = !subcategoryOpen"
                        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <span
                            :class="subcategory ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300'">
                            <span x-text="subcategoryName || 'Sve podkategorije'"></span>
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="subcategoryOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="subcategoryOpen" x-cloak x-transition @click.away="subcategoryOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectSubcategory('', ''); subcategoryOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center"
                                :class="!subcategory ?
                                    'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                    'text-slate-700 dark:text-slate-400'">
                                <span>Sve podkategorije</span>
                            </button>
                            <template x-if="loadingSubcategories">
                                <div class="text-center text-slate-500 dark:text-slate-300 py-3 text-sm">
                                    <i class="fas fa-spinner fa-spin"></i> Učitavanje...
                                </div>
                            </template>
                            <template x-if="!loadingSubcategories && subcategories.length > 0">
                                <template x-for="subcat in subcategories" :key="subcat.id">
                                    <button type="button"
                                        @click="selectSubcategory(subcat.id, subcat.name); subcategoryOpen = false"
                                        class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center pl-6"
                                        :class="subcategory == subcat.id ?
                                            'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                            'text-slate-700 dark:text-slate-300'">
                                        <i class="fas fa-angle-right text-slate-400 mr-2"></i>
                                        <span x-text="subcat.name"></span>
                                    </button>
                                </template>
                            </template>
                            <template x-if="!loadingSubcategories && subcategories.length === 0 && category">
                                <div class="text-center text-slate-500 dark:text-slate-300 py-3 text-sm">
                                    Nema podkategorija
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Service Category (full width) -->
                <div x-data="{ serviceCategoryOpen: false }" class="relative" x-show="content_type === 'services'">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Kategorija</label>
                    <button type="button" @click="serviceCategoryOpen = !serviceCategoryOpen"
                        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <span
                            :class="serviceCategory ? 'text-slate-900 dark:text-slate-100' :
                                'text-slate-500 dark:text-slate-300'">
                            <span x-text="serviceCategoryName || 'Sve kategorije'"></span>
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="serviceCategoryOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="serviceCategoryOpen" x-cloak x-transition @click.away="serviceCategoryOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectServiceCategory('', ''); serviceCategoryOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center"
                                :class="!serviceCategory ?
                                    'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <span>Sve kategorije</span>
                            </button>
                            @foreach (\App\Models\ServiceCategory::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get() as $cat)
                                <button type="button"
                                    @click="selectServiceCategory('{{ $cat->id }}', '{{ $cat->name }}'); serviceCategoryOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center"
                                    :class="serviceCategory === '{{ $cat->id }}' ?
                                        'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                        'text-slate-700 dark:text-slate-300'">
                                    @if ($cat->icon)
                                        <i class="{{ $cat->icon }} text-sky-600 dark:text-sky-400 mr-2"></i>
                                    @else
                                        <i class="fas fa-tools text-sky-600 dark:text-sky-400 mr-2"></i>
                                    @endif
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Service Subcategory (full width) -->
                <div x-data="{ serviceSubcategoryOpen: false }" class="relative"
                    x-show="content_type === 'services' && serviceCategory">
                    <label
                        class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Podkategorija</label>
                    <button type="button" @click="serviceSubcategoryOpen = !serviceSubcategoryOpen"
                        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <span
                            :class="serviceSubcategory ? 'text-slate-900 dark:text-slate-100' :
                                'text-slate-500 dark:text-slate-300'">
                            <span x-text="serviceSubcategoryName || 'Sve podkategorije'"></span>
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="serviceSubcategoryOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="serviceSubcategoryOpen" x-cloak x-transition @click.away="serviceSubcategoryOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button"
                                @click="selectServiceSubcategory('', ''); serviceSubcategoryOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center"
                                :class="!serviceSubcategory ?
                                    'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <span>Sve podkategorije</span>
                            </button>
                            <template x-if="loadingServiceSubcategories">
                                <div class="text-center text-slate-500 dark:text-slate-300 py-3 text-sm">
                                    <i class="fas fa-spinner fa-spin"></i> Učitavanje...
                                </div>
                            </template>
                            <template x-if="!loadingServiceSubcategories && serviceSubcategories.length > 0">
                                <template x-for="subcat in serviceSubcategories" :key="subcat.id">
                                    <button type="button"
                                        @click="selectServiceSubcategory(subcat.id, subcat.name); serviceSubcategoryOpen = false"
                                        class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition flex items-center pl-6"
                                        :class="serviceSubcategory == subcat.id ?
                                            'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                            'text-slate-700 dark:text-slate-300'">
                                        <i class="fas fa-angle-right text-slate-400 mr-2"></i>
                                        <span x-text="subcat.name"></span>
                                    </button>
                                </template>
                            </template>
                            <template
                                x-if="!loadingServiceSubcategories && serviceSubcategories.length === 0 && serviceCategory">
                                <div class="text-center text-slate-500 dark:text-slate-300 py-3 text-sm">
                                    Nema podkategorija
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Middle Column: Price & Condition -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 uppercase tracking-wide">Cena i
                    stanje</h4>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Cena
                            od</label>
                        <input type="number" x-model="price_min" placeholder="0"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Cena
                            do</label>
                        <input type="number" x-model="price_max" placeholder="∞"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </div>

                <!-- Condition (full width) -->
                <div x-data="{ conditionOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Stanje</label>
                    <button type="button" @click="conditionOpen = !conditionOpen"
                        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <span
                            :class="condition ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300'">
                            @if (!empty($selectedConditionName))
                                {{ $selectedConditionName }}
                            @else
                                <span x-text="conditionName || 'Sva stanja'"></span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="conditionOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="conditionOpen" x-cloak x-transition @click.away="conditionOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectCondition('', ''); conditionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition"
                                :class="!condition ?
                                    'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-200 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <span>Sva stanja</span>
                            </button>
                            @foreach (\App\Models\ListingCondition::where('is_active', true)->orderBy('name')->get() as $cond)
                                <button type="button"
                                    @click="selectCondition('{{ $cond->id }}', '{{ $cond->name }}'); conditionOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition"
                                    :class="condition === '{{ $cond->id }}' ?
                                        'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium' :
                                        'text-slate-700 dark:text-slate-300'">
                                    {{ $cond->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Column: Auctions (Yellow Section) -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wide">AUKCIJE
                </h4>

                <!-- Auction Sort Type -->
                <div x-data="{ auctionOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-amber-600 dark:text-amber-400 mb-1">Sortiranje
                        aukcija</label>
                    <button type="button" @click="auctionOpen = !auctionOpen"
                        class="w-full flex justify-between items-center border border-amber-300 dark:border-amber-600 bg-amber-100 dark:bg-amber-800 dark:bg-amber-900 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <span
                            :class="auction_type ? 'text-amber-900 dark:text-amber-200' : 'text-amber-600 dark:text-amber-300'">
                            @if (!empty($selectedAuctionTypeName))
                                {{ $selectedAuctionTypeName }}
                            @else
                                <span x-text="auctionTypeName || 'Sve aukcije'"></span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform text-amber-600 dark:text-amber-400"
                            :class="auctionOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="auctionOpen" x-cloak x-transition @click.away="auctionOpen = false"
                        class="absolute z-10 mt-1 w-full bg-amber-100 dark:bg-amber-800 dark:bg-amber-900 border border-amber-300 dark:border-amber-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-1">
                            <button type="button" @click="selectAuctionType('', ''); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-amber-100 dark:hover:bg-amber-800 transition flex items-center"
                                :class="!auction_type ?
                                    'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <i class="fas fa-list text-slate-600 dark:text-slate-400 mr-2"></i>
                                <span>Sve aukcije</span>
                            </button>
                            <button type="button"
                                @click="selectAuctionType('ending_soon', 'Završavaju uskoro'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-amber-100 dark:hover:bg-amber-800 transition flex items-center"
                                :class="auction_type === 'ending_soon' ?
                                    'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <i class="fas fa-clock text-red-600 dark:text-red-400 mr-2"></i>
                                <span>Završavaju uskoro</span>
                            </button>
                            <button type="button"
                                @click="selectAuctionType('newest', 'Najnovije aukcije'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-amber-100 dark:hover:bg-amber-800 transition flex items-center"
                                :class="auction_type === 'newest' ?
                                    'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <i class="fas fa-plus text-green-600 mr-2"></i>
                                <span>Najnovije aukcije</span>
                            </button>
                            <button type="button"
                                @click="selectAuctionType('highest_price', 'Najviša cena'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-amber-100 dark:hover:bg-amber-800 transition flex items-center"
                                :class="auction_type === 'highest_price' ?
                                    'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <i class="fas fa-money-bill text-green-600 mr-2"></i>
                                <span>Najviša cena</span>
                            </button>
                            <button type="button"
                                @click="selectAuctionType('most_bids', 'Najviše ponuda'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-amber-100 dark:hover:bg-amber-800 transition flex items-center"
                                :class="auction_type === 'most_bids' ?
                                    'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <i class="fas fa-gavel text-orange-600 mr-2"></i>
                                <span>Najviše ponuda</span>
                            </button>
                            <button type="button"
                                @click="selectAuctionType('scheduled', 'Zakazane aukcije'); auctionOpen = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-amber-100 dark:hover:bg-amber-800 transition flex items-center"
                                :class="auction_type === 'scheduled' ?
                                    'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 font-medium' :
                                    'text-slate-700 dark:text-slate-300'">
                                <i class="fas fa-calendar text-amber-600 dark:text-amber-400 mr-2"></i>
                                <span>Zakazane aukcije</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- <div
                    class="text-xs text-amber-800 dark:text-amber-200 p-2 bg-amber-100 dark:bg-amber-800 border border-amber-200 dark:border-amber-600 rounded">
                    <i class="fas fa-info-circle mr-1"></i>
                    Ostavi prazno za prikaz svih oglasa, izaberi opciju za filtriranje samo aukcija
                </div> --}}
            </div>
        </div>

        <!-- Filter Actions -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 pt-4 border-t border-slate-200 space-y-3 md:space-y-0">
            <button type="button" @click="resetFilters()"
                class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Resetuj filtere
            </button>

            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
                <button type="button" @click="showFilters = false"
                    class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-colors">
                    Sakrij filtere
                </button>
                <button type="button" @click="submitSearch()"
                    class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-sky-600 dark:bg-sky-600 hover:bg-sky-700 dark:hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    <span>Primeni filtere</span>
                    <span x-show="$store.searchFilters.activeCount > 0" class="ml-1 px-1.5 py-0.5 bg-white dark:bg-slate-200 text-sky-600 dark:text-sky-700 rounded-full text-xs font-bold min-w-[20px] h-5 flex items-center justify-center"
                        x-text="$store.searchFilters.activeCount"></span>
                </button>
            </div>
        </div>
    </div>
</div>
