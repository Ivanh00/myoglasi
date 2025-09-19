<div x-data="{
    open: false,
    query: '{{ request('query') ?? '' }}',
    city: '{{ request('city') ?? '' }}',
    category: '{{ request('category') ?? '' }}',
    subcategory: '{{ request('subcategory') ?? '' }}',
    condition: '{{ request('condition') ?? '' }}',
    price_min: '{{ request('price_min') ?? '' }}',
    price_max: '{{ request('price_max') ?? '' }}',
    citySearch: '',
    categories: @js($categories ?? []),
    conditions: @js($conditions ?? []),
    subcategories: [],

    // Store initial values for proper reset
    initialQuery: '{{ request('query') ?? '' }}',
    initialCity: '{{ request('city') ?? '' }}',
    initialCategory: '{{ request('category') ?? '' }}',
    initialSubcategory: '{{ request('subcategory') ?? '' }}',
    initialCondition: '{{ request('condition') ?? '' }}',
    initialPriceMin: '{{ request('price_min') ?? '' }}',
    initialPriceMax: '{{ request('price_max') ?? '' }}',

    normalize(str) {
        const map = {
            'š': 's',
            'ć': 'c',
            'č': 'c',
            'ž': 'z',
            'đ': 'dj',
            'Š': 's',
            'Ć': 'c',
            'Č': 'c',
            'Ž': 'z',
            'Đ': 'dj'
        };
        return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
    },

    get filteredCities() {
        return @js(config('cities')).filter(c =>
            this.normalize(c).includes(this.normalize(this.citySearch || ''))
        );
    },

    // Reset all filters to empty state
    resetAllFilters() {
        this.query = '';
        this.city = '';
        this.category = '';
        this.subcategory = '';
        this.condition = '';
        this.price_min = '';
        this.price_max = '';
        this.citySearch = '';
        this.subcategories = [];
    },

    // Restore values when modal opens
    restoreValues() {
        this.query = this.initialQuery;
        this.city = this.initialCity;
        this.category = this.initialCategory;
        this.subcategory = this.initialSubcategory;
        this.condition = this.initialCondition;
        this.price_min = this.initialPriceMin;
        this.price_max = this.initialPriceMax;
        this.citySearch = '';
    },

    // Check if any filters are active
    hasActiveFilters() {
        return this.initialQuery || this.initialCity || this.initialCategory ||
            this.initialSubcategory || this.initialCondition ||
            this.initialPriceMin || this.initialPriceMax;
    }
}" class="relative flex-1 max-w-lg mx-4">
    <form action="{{ route('search.index') }}" method="GET">
        <div class="flex shadow-sm rounded-md">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="query" x-model="query"
                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-l-md bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                    placeholder="Pretraži oglase...">
            </div>
            <button type="button" @click="open = true; restoreValues()"
                class="inline-flex items-center px-3 py-2 border border-l-0 border-slate-300 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500"
                :class="hasActiveFilters() ? 'bg-sky-100 text-sky-700 border-sky-300' :
                    'bg-slate-50 text-slate-700 hover:bg-slate-100'">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    :class="hasActiveFilters() ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-300'">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Detaljno
                <span x-show="hasActiveFilters()" class="ml-1 w-2 h-2 bg-sky-600 rounded-full"></span>
            </button>
            <button type="submit"
                class="inline-flex items-center px-3 py-2 border border-l-0 border-slate-300 bg-sky-600 text-sm font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 rounded-r-md">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>

        <!-- Modal za detaljnu pretragu -->
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 overflow-y-auto z-50" style="display: none;"
            @click.away="open = false; restoreValues()" @keydown.escape.window="open = false; restoreValues()"
            x-on:close-modal="restoreValues()">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
                    @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-slate-900">Detaljna pretraga</h3>
                            <button type="button" @click="open = false; restoreValues()"
                                class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Grad/Mesto -->
                            <div x-data="{
                                cityOpen: false,
                                get selectedCity() { return city; },
                                set selectedCity(value) { city = value; }
                            }" class="relative">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Grad/Mesto</label>
                                <button type="button" @click="cityOpen = !cityOpen"
                                    class="mt-1 w-full flex justify-between items-center border border-slate-300 rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-2 focus:ring-sky-500">
                                    <span x-text="selectedCity ? selectedCity : 'Odaberi grad'"></span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Popup za gradove -->
                                <div x-show="cityOpen" x-transition @click.away="cityOpen = false"
                                    class="absolute z-20 mt-1 w-full bg-white border border-slate-300 rounded-md shadow-lg">

                                    <!-- Search bar za gradove -->
                                    <div class="p-2 border-b border-slate-200">
                                        <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-sky-500">
                                    </div>

                                    <!-- Lista gradova -->
                                    <div class="p-2 max-h-48 overflow-y-auto">
                                        <template x-for="cityOption in filteredCities" :key="cityOption">
                                            <button type="button" @click="selectedCity = cityOption; cityOpen = false"
                                                class="w-full text-left px-3 py-2 rounded-md hover:bg-sky-100 transition"
                                                :class="selectedCity === cityOption ? 'bg-sky-100 text-sky-800' : ''">
                                                <span x-text="cityOption"></span>
                                            </button>
                                        </template>
                                        <div x-show="filteredCities.length === 0"
                                            class="text-center text-slate-500 dark:text-slate-300 py-2">
                                            Nema rezultata
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="city" x-model="selectedCity">
                            </div>

                            <!-- Kategorija -->
                            <div>
                                <label for="category"
                                    class="block text-sm font-medium text-slate-700 mb-1">Kategorija</label>
                                <select name="category" x-model="category"
                                    class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                                    <option value="">Sve kategorije</option>
                                    @foreach (\App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stanje -->
                            <div>
                                <label for="condition"
                                    class="block text-sm font-medium text-slate-700 mb-1">Stanje</label>
                                <select name="condition" x-model="condition"
                                    class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                                    <option value="">Sva stanja</option>
                                    @foreach (\App\Models\ListingCondition::where('is_active', true)->orderBy('name')->get() as $cond)
                                        <option value="{{ $cond->id }}">{{ $cond->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cena od -->
                            <div>
                                <label for="price_min" class="block text-sm font-medium text-slate-700 mb-1">Cena od
                                    (RSD)</label>
                                <input type="number" name="price_min" x-model="price_min" placeholder="0"
                                    class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                            </div>

                            <!-- Cena do -->
                            <div>
                                <label for="price_max" class="block text-sm font-medium text-slate-700 mb-1">Cena do
                                    (RSD)</label>
                                <input type="number" name="price_max" x-model="price_max"
                                    placeholder="Unesite maksimalnu cenu"
                                    class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" @click="open = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Primeni filtere
                        </button>
                        <button type="button" @click="resetAllFilters(); $el.closest('form').submit(); open = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Poništi filtere
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
