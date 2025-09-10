@php
$urlParams = request()->all();
$hasFilters = !empty(array_filter([
    $urlParams['city'] ?? '',
    $urlParams['category'] ?? '',
    $urlParams['condition'] ?? '',
    $urlParams['price_min'] ?? '',
    $urlParams['price_max'] ?? ''
]));
@endphp

<div class="relative flex-1 max-w-4xl mx-4" x-data="{
    showFilters: {{ $hasFilters ? 'true' : 'false' }},
    query: '{{ $urlParams['query'] ?? '' }}',
    city: '{{ $urlParams['city'] ?? '' }}',
    category: '{{ $urlParams['category'] ?? '' }}',
    condition: '{{ $urlParams['condition'] ?? '' }}',
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
        this.condition = '';
        this.price_min = '';
        this.price_max = '';
        this.citySearch = '';
        this.submitSearch();
    },
    
    submitSearch() {
        // Build URL with parameters
        const params = new URLSearchParams();
        if (this.query) params.set('query', this.query);
        if (this.city) params.set('city', this.city);
        if (this.category) params.set('category', this.category);
        if (this.condition) params.set('condition', this.condition);
        if (this.price_min) params.set('price_min', this.price_min);
        if (this.price_max) params.set('price_max', this.price_max);
        
        const url = '{{ route('search.index') }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    },
    
    hasActiveFilters() {
        return this.city || this.category || this.condition || this.price_min || this.price_max;
    }
}">
    <!-- Main Search Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-300 overflow-hidden">
        <div class="flex">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" x-model="query" @keydown.enter="submitSearch()"
                    class="block w-full pl-10 pr-3 py-3 border-0 bg-transparent placeholder-gray-400 focus:outline-none focus:ring-0 text-sm"
                    placeholder="Pretraži oglase...">
            </div>
            
            <button type="button" @click="toggleFilters()" 
                class="inline-flex items-center px-4 py-3 border-l border-gray-300 text-sm font-medium transition-colors focus:outline-none"
                :class="hasActiveFilters() || showFilters ? 'bg-blue-50 text-blue-700 border-blue-300' : 'text-gray-700 hover:bg-gray-50'">
                <svg class="h-4 w-4 mr-2 transition-transform" :class="showFilters ? 'rotate-180' : ''" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Detaljno</span>
                <span x-show="hasActiveFilters()" class="ml-2 w-2 h-2 bg-blue-600 rounded-full"></span>
            </button>
            
            <button type="button" @click="submitSearch()"
                class="inline-flex items-center px-4 py-3 border-l border-gray-300 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none transition-colors">
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
        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-300 z-50 p-6"
        style="display: none;">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Location & Category -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Lokacija i kategorija</h4>
                
                <!-- City -->
                <div x-data="{ cityOpen: false }" class="relative">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Grad/Mesto</label>
                    <button type="button" @click="cityOpen = !cityOpen"
                        class="w-full flex justify-between items-center border border-gray-300 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span x-text="city || 'Odaberi grad'" :class="city ? 'text-gray-900' : 'text-gray-500'"></span>
                        <svg class="w-4 h-4 transition-transform" :class="cityOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="cityOpen" x-transition @click.away="cityOpen = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-2 border-b">
                            <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                                class="w-full px-3 py-2 border rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="p-1">
                            <template x-for="cityOption in filteredCities" :key="cityOption">
                                <button type="button" @click="city = cityOption; cityOpen = false"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-blue-50 transition"
                                    :class="city === cityOption ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700'">
                                    <span x-text="cityOption"></span>
                                </button>
                            </template>
                            <div x-show="filteredCities.length === 0" class="text-center text-gray-500 py-3 text-sm">
                                Nema rezultata
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kategorija</label>
                    <select x-model="category" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sve kategorije</option>
                        @foreach(\App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Middle Column: Price -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Cena</h4>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Cena od</label>
                        <input type="number" x-model="price_min" placeholder="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Cena do</label>
                        <input type="number" x-model="price_max" placeholder="∞"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Right Column: Additional Filters -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Dodatni filteri</h4>
                
                <!-- Condition -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Stanje</label>
                    <select x-model="condition" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sva stanja</option>
                        @foreach(\App\Models\ListingCondition::where('is_active', true)->orderBy('name')->get() as $cond)
                            <option value="{{ $cond->id }}">{{ $cond->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <button type="button" @click="resetFilters()" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Resetuj filtere
            </button>
            
            <div class="flex space-x-3">
                <button type="button" @click="showFilters = false" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    Sakrij filtere
                </button>
                <button type="button" @click="submitSearch()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Primeni filtere
                </button>
            </div>
        </div>
    </div>
</div>