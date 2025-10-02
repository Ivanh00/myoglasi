<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Mobile specific adjustments -->
    <style>
        @media (max-width: 768px) {
            .business-card {
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

    <!-- Mobile kategorija dropdown -->
    <div class="md:hidden mb-6">
        <div class="bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-700 rounded-lg shadow-md p-4">
            <div class="text-slate-600 dark:text-slate-300 mb-2">
                Pronađeno business-a: <span class="font-semibold">{{ $businesses->total() }}</span>
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
            <div class="relative mb-2" x-data="{ open: false }" x-init="open = false">
                <button @click="open = !open" type="button"
                    class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                    <span>
                        @if ($selectedCategory)
                            @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                            {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                        @else
                            Sve kategorije
                        @endif
                    </span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak @click.away="open = false" x-transition
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-t-lg {{ !$selectedCategory ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                        Sve kategorije
                    </button>
                    @foreach ($categories as $category)
                        <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false"
                            type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 flex items-center {{ $selectedCategory == $category->id ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-purple-600 dark:text-purple-400 mr-2"></i>
                            @endif
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Subcategory dropdown (mobile) -->
            @if ($selectedCategory && count($subcategories) > 0)
                <div class="relative mb-2" x-data="{ open: false }" x-init="open = false">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                        <span>
                            @if ($selectedSubcategory)
                                @php $selectedSubcat = $subcategories->firstWhere('id', $selectedSubcategory); @endphp
                                {{ $selectedSubcat ? $selectedSubcat->name : 'Sve podkategorije' }}
                            @else
                                Sve podkategorije
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <button @click="$wire.set('selectedSubcategory', ''); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-t-lg {{ !$selectedSubcategory ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                            Sve podkategorije
                        </button>
                        @foreach ($subcategories as $subcategory)
                            <button @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 {{ $selectedSubcategory == $subcategory->id ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                                {{ $subcategory->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- City dropdown (mobile) -->
            <div class="relative mb-2" x-data="{
                open: false,
                citySearch: '',
                get filteredCities() {
                    const normalize = (str) => {
                        const map = { 'š': 's', 'ć': 'c', 'č': 'c', 'ž': 'z', 'đ': 'dj' };
                        return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
                    };
                    return @js(config('cities', [])).filter(c =>
                        normalize(c).includes(normalize(this.citySearch || ''))
                    );
                }
            }" x-init="open = false">
                <button @click="open = !open" type="button"
                    class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                    <span x-text="$wire.selectedCity || 'Grad/Mesto'"
                        :class="$wire.selectedCity ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300'"></span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak @click.away="open = false" x-transition
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <div class="p-2 border-b">
                        <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                            class="w-full px-3 py-2 border border-purple-300 dark:border-purple-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-purple-500">
                    </div>
                    <div class="p-1">
                        <button type="button" @click="$wire.set('selectedCity', ''); open = false"
                            class="w-full text-left px-3 py-2 text-sm rounded hover:bg-purple-50 dark:hover:bg-purple-900/20 transition"
                            :class="!$wire.selectedCity ?
                                'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-medium' :
                                'text-slate-700 dark:text-slate-200'">
                            <span>Svi gradovi</span>
                        </button>
                        <template x-for="cityOption in filteredCities" :key="cityOption">
                            <button type="button" @click="$wire.set('selectedCity', cityOption); open = false"
                                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-purple-50 dark:hover:bg-purple-900/20 transition"
                                :class="$wire.selectedCity === cityOption ?
                                    'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-medium' :
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

            <!-- Per page and View mode (mobile) -->
            <div class="flex gap-2">
                <!-- Per page dropdown (mobile) -->
                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }} po strani</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-t-lg">
                                20 po strani
                            </button>
                            <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                50 po strani
                            </button>
                            <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-b-lg">
                                100 po strani
                            </button>
                        </div>
                    </div>
                </div>

                <!-- View mode toggle (mobile) -->
                <div class="flex bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm">
                    <button wire:click="setViewMode('list')"
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-600 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }} rounded-l-lg transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                    <button wire:click="setViewMode('grid')"
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-600 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }} rounded-r-lg transition-colors">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop view -->
    <div class="bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-700 rounded-lg shadow-md p-4 mb-6 hidden md:block">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <!-- Results Info (Desktop - Left aligned) -->
            <div class="text-slate-600 dark:text-slate-300 mb-4">
                Pronađeno business-a: <span class="font-semibold">{{ $businesses->total() }}</span>
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

            <!-- Filter Controls -->
            <div class="flex items-center justify-between gap-4">
                <!-- Left: Category dropdown -->
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($selectedCategory)
                                    @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
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
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-t-lg {{ !$selectedCategory ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve kategorije
                            </button>
                            @foreach ($categories as $category)
                                <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 flex items-center {{ $selectedCategory == $category->id ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} text-purple-600 dark:text-purple-400 mr-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Subcategory dropdown (desktop) -->
                @if ($selectedCategory && count($subcategories) > 0)
                    <div class="w-60" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($selectedSubcategory)
                                        @php $selectedSubcat = $subcategories->firstWhere('id', $selectedSubcategory); @endphp
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
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <button @click="$wire.set('selectedSubcategory', ''); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-t-lg {{ !$selectedSubcategory ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    Sve podkategorije
                                </button>
                                @foreach ($subcategories as $subcategory)
                                    <button @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 {{ $selectedSubcategory == $subcategory->id ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                                        {{ $subcategory->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Center: Filters -->
                <div class="flex items-center gap-3">
                    <!-- City dropdown -->
                    <div class="w-48" x-data="{
                        open: false,
                        citySearch: '',
                        get filteredCities() {
                            const normalize = (str) => {
                                const map = { 'š': 's', 'ć': 'c', 'č': 'c', 'ž': 'z', 'đ': 'dj' };
                                return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
                            };
                            return @js(config('cities', [])).filter(c =>
                                normalize(c).includes(normalize(this.citySearch || ''))
                            );
                        }
                    }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                                <span x-text="$wire.selectedCity || 'Grad/Mesto'"
                                    :class="$wire.selectedCity ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300'"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak x-transition @click.away="open = false"
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <div class="p-2 border-b">
                                    <input type="text" x-model="citySearch" placeholder="Pretraži grad..."
                                        class="w-full px-3 py-2 border border-purple-300 dark:border-purple-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-purple-500">
                                </div>
                                <div class="p-1">
                                    <button type="button" @click="$wire.set('selectedCity', ''); open = false"
                                        class="w-full text-left px-3 py-2 text-sm rounded hover:bg-purple-50 dark:hover:bg-purple-900/20 transition"
                                        :class="!$wire.selectedCity ?
                                            'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-medium' :
                                            'text-slate-700 dark:text-slate-200'">
                                        <span>Svi gradovi</span>
                                    </button>
                                    <template x-for="cityOption in filteredCities" :key="cityOption">
                                        <button type="button" @click="$wire.set('selectedCity', cityOption); open = false"
                                            class="w-full text-left px-3 py-2 text-sm rounded hover:bg-purple-50 dark:hover:bg-purple-900/20 transition"
                                            :class="$wire.selectedCity === cityOption ?
                                                'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-medium' :
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
                    </div>

                    <!-- Per page -->
                    <div class="w-32" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-purple-400 focus:outline-none focus:border-purple-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-lg">
                                <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-t-lg">
                                    20 po strani
                                </button>
                                <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                    50 po strani
                                </button>
                                <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-b-lg">
                                    100 po strani
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: View Mode Toggle -->
                <div
                    class="flex bg-white dark:bg-slate-700 border border-purple-300 dark:border-purple-600 rounded-lg shadow-sm">
                    <button wire:click="setViewMode('list')"
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-600 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }} rounded-l-lg transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                    <button wire:click="setViewMode('grid')"
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-600 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }} rounded-r-lg transition-colors">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    @if ($businesses->count() > 0)
        @if ($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($businesses as $business)
                    <x-view-business-grid-component :business="$business" />
                @endforeach
            </div>
        @else
            <!-- List View -->
            <div class="space-y-4">
                @foreach ($businesses as $business)
                    <x-view-business-list-component :business="$business" />
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @if ($businesses->hasPages())
            <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                {{ $businesses->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-briefcase text-slate-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-200 mb-2">Nema pronađenih business-a</h3>
            <p class="text-slate-600 dark:text-slate-300">
                Pokušajte da promenite filtere ili kategoriju.
            </p>
        </div>
    @endif
</div>
