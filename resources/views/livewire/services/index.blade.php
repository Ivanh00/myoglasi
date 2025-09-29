<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Mobile specific adjustments -->
    <style>
        @media (max-width: 768px) {
            .service-card {
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

    <!-- Desktop kategorije dropdown će biti u donjoj sekciji -->

    <!-- Mobile kategorija dropdown -->
    <div class="md:hidden mb-6">
        <div class="bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-600 rounded-lg shadow-md p-4">
            <div class="text-slate-600 dark:text-slate-300 mb-2">
                Pronađeno usluga: <span class="font-semibold">{{ $services->total() }}</span>
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
                    class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
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

                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedCategory ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                        Sve kategorije
                    </button>
                    @foreach ($categories as $category)
                        <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false"
                            type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $selectedCategory == $category->id ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-slate-600 dark:text-slate-400 mr-2"></i>
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
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
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

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <button @click="$wire.set('selectedSubcategory', ''); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedSubcategory ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                            Sve podkategorije
                        </button>
                        @foreach ($subcategories as $subcategory)
                            <button @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $selectedSubcategory == $subcategory->id ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                                {{ $subcategory->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- Filteri i sortiranje -->
            <div class="md:hidden">
                <div class="flex gap-3">
                    <!-- Mobile filters (50/50 split) -->
                    <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($sortBy === 'newest')
                                        Najnovije
                                    @elseif($sortBy === 'price_asc')
                                        Cena ↑
                                    @elseif($sortBy === 'price_desc')
                                        Cena ↓
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
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
                            </div>
                        </div>
                    </div>

                    <!-- Mobile per page -->
                    <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                                <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    20 po strani
                                </button>
                                <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    50 po strani
                                </button>
                                <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    100 po strani
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-600 rounded-lg shadow-md p-4 mb-6 hidden md:block">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <!-- Results Info (Desktop - Left aligned) -->
            <div class="text-slate-600 dark:text-slate-300 mb-4">
                Pronađeno usluga: <span class="font-semibold">{{ $services->total() }}</span>
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
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
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

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedCategory ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve kategorije
                            </button>
                            @foreach ($categories as $category)
                                <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $selectedCategory == $category->id ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} text-slate-600 dark:text-slate-400 mr-2"></i>
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
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
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

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <button @click="$wire.set('selectedSubcategory', ''); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedSubcategory ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    Sve podkategorije
                                </button>
                                @foreach ($subcategories as $subcategory)
                                    <button @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $selectedSubcategory == $subcategory->id ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-700 dark:text-slate-200' }}">
                                        {{ $subcategory->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Center: Filters -->
                <div class="flex items-center gap-3">
                    <!-- Sortiranje -->
                    <div class="w-40" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($sortBy === 'newest')
                                        Najnovije
                                    @elseif($sortBy === 'price_asc')
                                        Cena ↑
                                    @elseif($sortBy === 'price_desc')
                                        Cena ↓
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
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
                            </div>
                        </div>
                    </div>

                    <!-- Per page -->
                    <div class="w-32" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                                <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    20 po strani
                                </button>
                                <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                    50 po strani
                                </button>
                                <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    100 po strani
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: View Mode Toggle -->
                <div
                    class="flex bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm">
                    <button wire:click="setViewMode('list')"
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-l-lg transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                    <button wire:click="setViewMode('grid')"
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-r-lg transition-colors">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Sort/PerPage Layout -->
        <div class="md:hidden">
            <div class="flex gap-3">
                <!-- Mobile filters (50/50 split) -->
                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>
                                @if ($sortBy === 'newest')
                                    Najnovije
                                @elseif($sortBy === 'price_asc')
                                    Cena ↑
                                @elseif($sortBy === 'price_desc')
                                    Cena ↓
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
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
                        </div>
                    </div>
                </div>

                <!-- Mobile per page -->
                <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }} po strani</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                            <button @click="$wire.set('perPage', '20'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                20 po strani
                            </button>
                            <button @click="$wire.set('perPage', '50'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                50 po strani
                            </button>
                            <button @click="$wire.set('perPage', '100'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                100 po strani
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista/Grid usluga -->
    @if ($services->count() > 0)
        @if ($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4 mb-8">
                @foreach ($services as $service)
                    @include('livewire.components.view-service-component', ['service' => $service, 'viewMode' => 'list'])
                @endforeach
            </div>
        @else
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach ($services as $service)
                    @include('livewire.components.view-service-component', ['service' => $service, 'viewMode' => 'grid'])
                @endforeach
            </div>
        @endif

        <!-- Paginacija -->
        @if ($services->hasPages())
            <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                {{ $services->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-tools text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Nema usluga</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">
                @if ($selectedCategory)
                    Trenutno nema aktivnih usluga u ovoj kategoriji.
                @else
                    Trenutno nema aktivnih usluga.
                @endif
            </p>
            @auth
                <a href="{{ route('services.create') }}"
                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                    Dodaj prvu uslugu
                </a>
            @endauth
        </div>
    @endif
</div>
