<div x-data="{
    open: false,
    query: '{{ request('query') }}',
    city: '{{ request('city') }}',
    category: '{{ request('category') }}',
    subcategory: '{{ request('subcategory') }}',
    condition: '{{ request('condition') }}',
    price_min: '{{ request('price_min') }}',
    price_max: '{{ request('price_max') }}',
    citySearch: '',
    categories: @js($categories ?? []),
    conditions: @js($conditions ?? []),
    subcategories: [],
    normalize(str) {
        const map = {
            'š': 's',
            's': 's',
            'ć': 'c',
            'č': 'c',
            'c': 'c',
            'ž': 'z',
            'z': 'z',
            'đ': 'dj',
            'd': 'd',
            'а': 'a',
            'б': 'b',
            'в': 'v',
            'г': 'g',
            'д': 'd',
            'ђ': 'dj',
            'е': 'e',
            'ж': 'z',
            'з': 'z',
            'и': 'i',
            'ј': 'j',
            'к': 'k',
            'л': 'l',
            'љ': 'lj',
            'м': 'm',
            'н': 'n',
            'њ': 'nj',
            'о': 'o',
            'п': 'p',
            'р': 'r',
            'с': 's',
            'т': 't',
            'ћ': 'c',
            'у': 'u',
            'ф': 'f',
            'х': 'h',
            'ц': 'c',
            'ч': 'c',
            'џ': 'dz',
            'ш': 's',
            'А': 'a',
            'Б': 'b',
            'В': 'v',
            'Г': 'g',
            'Д': 'd',
            'Ђ': 'dj',
            'Е': 'e',
            'Ж': 'z',
            'З': 'z',
            'И': 'i',
            'Ј': 'j',
            'К': 'k',
            'Л': 'l',
            'Љ': 'lj',
            'М': 'm',
            'Н': 'n',
            'Њ': 'nj',
            'О': 'o',
            'П': 'p',
            'Р': 'r',
            'С': 's',
            'Т': 't',
            'Ћ': 'c',
            'У': 'u',
            'Ф': 'f',
            'Х': 'h',
            'Ц': 'c',
            'Ч': 'c',
            'Џ': 'dz',
            'Ш': 's'
        };
        return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
    },
    get filteredCities() {
        return @js(config('cities')).filter(c =>
            this.normalize(c).includes(this.normalize(this.citySearch || ''))
        );
    },
    loadSubcategories() {
        if (this.category) {
            fetch(`/api/categories/${this.category}/subcategories`)
                .then(response => response.json())
                .then(data => {
                    this.subcategories = data;
                })
                .catch(error => {
                    console.error('Error loading subcategories:', error);
                    this.subcategories = [];
                });
        } else {
            this.subcategories = [];
            this.subcategory = '';
        }
    }
}" class="relative flex-1 max-w-lg mx-4">
    <form action="{{ route('search.index') }}" method="GET">
        <div class="flex shadow-sm rounded-md">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="query" x-model="query"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-l-md bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Pretraži oglase...">
            </div>
            <button type="button" @click="open = true"
                class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Detaljno
            </button>
            <button type="submit"
                class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-r-md">
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
            class="fixed inset-0 overflow-y-auto z-50" style="display: none;" @click.away="open = false">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
                    @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detaljna pretraga</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Grad/Mesto -->
                            <div x-data="{
                                cityOpen: false,
                                selectedCity: city
                            }" class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Grad/Mesto</label>
                                <button type="button" @click="cityOpen = !cityOpen"
                                    class="mt-1 w-full flex justify-between items-center border border-gray-300 rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span x-text="selectedCity ? selectedCity : 'Odaberi grad'"></span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Popup za gradove -->
                                <div x-show="cityOpen" x-transition @click.away="cityOpen = false"
                                    class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">

                                    <!-- Search bar za gradove -->
                                    <div class="p-2 border-b border-gray-200">
                                        <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-500">
                                    </div>

                                    <!-- Lista gradova -->
                                    <div class="p-2 max-h-48 overflow-y-auto">
                                        <template x-for="cityItem in filteredCities" :key="cityItem">
                                            <button type="button"
                                                @click="selectedCity = cityItem; city = cityItem; cityOpen = false"
                                                class="w-full text-left px-3 py-2 rounded-md hover:bg-blue-100 transition"
                                                :class="selectedCity === cityItem ? 'bg-blue-100 text-blue-800' : ''">
                                                <span x-text="cityItem"></span>
                                            </button>
                                        </template>
                                        <div x-show="filteredCities.length === 0"
                                            class="text-center text-gray-500 py-2">
                                            Nema rezultata
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="city" x-model="selectedCity">
                            </div>

                            <!-- Kategorija -->
                            <div>
                                <label for="category"
                                    class="block text-sm font-medium text-gray-700 mb-1">Kategorija</label>
                                <select name="category" x-model="category" @change="loadSubcategories()"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sve kategorije</option>
                                    <template x-for="cat in categories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Podkategorija -->
                            <div x-show="subcategories.length > 0">
                                <label for="subcategory"
                                    class="block text-sm font-medium text-gray-700 mb-1">Podkategorija</label>
                                <select name="subcategory" x-model="subcategory"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sve podkategorije</option>
                                    <template x-for="subcat in subcategories" :key="subcat.id">
                                        <option :value="subcat.id" x-text="subcat.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Stanje -->
                            <div>
                                <label for="condition"
                                    class="block text-sm font-medium text-gray-700 mb-1">Stanje</label>
                                <select name="condition" x-model="condition"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sva stanja</option>
                                    <template x-for="cond in conditions" :key="cond.id">
                                        <option :value="cond.id" x-text="cond.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Cena od -->
                            <div>
                                <label for="price_min" class="block text-sm font-medium text-gray-700 mb-1">Cena od
                                    (RSD)</label>
                                <input type="number" name="price_min" x-model="price_min" placeholder="0"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- Cena do -->
                            <div>
                                <label for="price_max" class="block text-sm font-medium text-gray-700 mb-1">Cena do
                                    (RSD)</label>
                                <input type="number" name="price_max" x-model="price_max"
                                    placeholder="Unesite maksimalnu cenu"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" @click="open = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Primeni filtere
                        </button>
                        <button type="button"
                            @click="open = false; query = ''; city = ''; category = ''; subcategory = ''; condition = ''; price_min = ''; price_max = ''; subcategories = []; citySearch = ''; $nextTick(() => { const cityDiv = document.querySelector('[x-data*=selectedCity]'); if(cityDiv && cityDiv.__x) cityDiv.__x.$data.selectedCity = ''; })"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Poništi filtere
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>