<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Mobile specific adjustments -->
    <style>
        @media (max-width: 768px) {
            .giveaway-card {
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
        <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-600 rounded-lg shadow-md p-4">
            <div class="text-slate-600 dark:text-slate-300 mb-2">
                Pronađeno poklona: <span class="font-semibold">{{ $giveaways->total() }}</span>
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
                    class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
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
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedCategory ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
                        Sve kategorije
                    </button>
                    @foreach ($categories as $category)
                        <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false"
                            type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $selectedCategory == $category->id ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-green-600 dark:text-green-400 mr-2"></i>
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
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
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
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <button @click="$wire.set('selectedSubcategory', ''); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedSubcategory ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
                            Sve podkategorije
                        </button>
                        @foreach ($subcategories as $subcategory)
                            <button @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $selectedSubcategory == $subcategory->id ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
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
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($sortBy === 'newest')
                                        Najnovije ↓
                                    @elseif($sortBy === 'oldest')
                                        Najstarije ↓
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                                <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    Najnovije ↓
                                </button>
                                <button @click="$wire.set('sortBy', 'oldest'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    Najstarije ↓
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile per page -->
                    <div class="flex-1" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
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


    <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-600 rounded-lg shadow-md p-4 mb-6 hidden md:block">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <!-- Results Info (Desktop - Left aligned) -->
            <div class="text-slate-600 dark:text-slate-300 mb-4">
                Pronađeno poklona: <span class="font-semibold">{{ $giveaways->total() }}</span>
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
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
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
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.set('selectedCategory', ''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedCategory ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve kategorije
                            </button>
                            @foreach ($categories as $category)
                                <button @click="$wire.set('selectedCategory', '{{ $category->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $selectedCategory == $category->id ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} text-green-600 dark:text-green-400 mr-2"></i>
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
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
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
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <button @click="$wire.set('selectedSubcategory', ''); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedSubcategory ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    Sve podkategorije
                                </button>
                                @foreach ($subcategories as $subcategory)
                                    <button @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                        type="button"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 {{ $selectedSubcategory == $subcategory->id ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-slate-700 dark:text-slate-200' }}">
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
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                                <span>
                                    @if ($sortBy === 'newest')
                                        Najnovije ↓
                                    @elseif($sortBy === 'oldest')
                                        Najstarije ↓
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                                <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                    Najnovije ↓
                                </button>
                                <button @click="$wire.set('sortBy', 'oldest'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                    Najstarije ↓
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Per page -->
                    <div class="w-32" x-data="{ open: false }" x-init="open = false">
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                                <span>{{ $perPage }} po strani</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
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
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-l-lg transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                    <button wire:click="setViewMode('grid')"
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600' }} rounded-r-lg transition-colors">
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
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
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

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
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
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-green-500 transition-colors flex items-center justify-between">
                            <span>{{ $perPage }} po strani</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
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

    <!-- Lista/Grid poklonja -->
    @if ($giveaways->count() > 0)
        @if ($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4 mb-8">
                @foreach ($giveaways as $giveaway)
                    <div
                        class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
                        <div class="flex flex-col md:flex-row">
                            <!-- Slika poklonja -->
                            <div class="w-full md:w-48 md:min-w-48 h-48">
                                <a href="{{ route('listings.show', $giveaway) }}">
                                    @if ($giveaway->images->count() > 0)
                                        <img src="{{ $giveaway->images->first()->url }}" alt="{{ $giveaway->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                            <i class="fas fa-gift text-green-500 dark:text-green-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <!-- Informacije o poklonju -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <a href="{{ route('listings.show', $giveaway) }}" class="flex-1">
                                                <h3
                                                    class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                                    {{ $giveaway->title }}
                                                </h3>
                                            </a>
                                            <span
                                                class="ml-2 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full font-medium">
                                                BESPLATNO
                                            </span>
                                        </div>

                                        <!-- Davalac -->
                                        @auth
                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                                Poklanja: {{ $giveaway->user->name ?? 'Nepoznat korisnik' }}
                                                @if ($giveaway->user)
                                                    {!! $giveaway->user->verified_icon !!}
                                                @endif
                                                @if ($giveaway->user && $giveaway->user->is_banned)
                                                    <span
                                                        class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                                @endif
                                                @if ($giveaway->user && $giveaway->user->shouldShowLastSeen())
                                                    <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                                        @if ($giveaway->user->is_online)
                                                            <span class="inline-flex items-center">
                                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                                {{ $giveaway->user->last_seen }}
                                                            </span>
                                                        @else
                                                            {{ $giveaway->user->last_seen }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </p>
                                        @endauth

                                        <div class="flex items-center text-sm text-slate-600 dark:text-slate-400 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $giveaway->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-folder mr-1"></i>
                                            <span>{{ $giveaway->category->name }}</span>
                                            @if ($giveaway->condition)
                                                <span class="mx-2">•</span>
                                                <span
                                                    class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs rounded-full">
                                                    {{ $giveaway->condition->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-slate-700 dark:text-slate-200 mb-3"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($giveaway->description), 120) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Desna strana - akcije -->
                            <div
                                class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-green-50 dark:bg-green-900/50">
                                <div class="flex flex-col h-full justify-between">
                                    <div
                                        class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300 mb-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span>{{ $giveaway->views ?? 0 }}</span>
                                        </div>
                                    </div>

                                    <div class="text-xs text-slate-700 dark:text-slate-200 mb-4">
                                        <i class="fas fa-clock mr-1"></i>
                                        Postavljeno pre {{ floor($giveaway->created_at->diffInDays()) }} dana
                                    </div>

                                    <div class="space-y-2">
                                        @auth
                                            @if (auth()->id() !== $giveaway->user_id)
                                                @if ($giveaway->approvedReservation)
                                                    <button disabled
                                                        class="block w-full text-center px-3 py-2 bg-slate-600 text-white rounded-lg opacity-50 cursor-not-allowed text-sm">
                                                        <i class="fas fa-check-circle mr-2"></i> Poklonjeno
                                                    </button>
                                                @elseif ($giveaway->giveawayReservations->count() > 0)
                                                    <button disabled
                                                        class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg opacity-50 cursor-not-allowed text-sm">
                                                        <i class="fas fa-paper-plane mr-2"></i> Zahtev poslat
                                                    </button>
                                                @elseif ($giveaway->pending_reservations_count >= \App\Models\Setting::get('max_giveaway_requests', 9))
                                                    <button disabled
                                                        class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg opacity-50 cursor-not-allowed text-sm">
                                                        <i class="fas fa-users mr-2"></i> Max. broj zahteva
                                                    </button>
                                                @else
                                                    <button wire:click="requestGiveaway({{ $giveaway->id }})"
                                                        class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                                        <i class="fas fa-hand-paper mr-2"></i> Želim poklon
                                                        @if ($giveaway->pending_reservations_count > 0)
                                                            <span
                                                                class="text-xs">({{ $giveaway->pending_reservations_count }}/{{ \App\Models\Setting::get('max_giveaway_requests', 9) }})</span>
                                                        @endif
                                                    </button>
                                                @endif
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                                class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                                <i class="fas fa-sign-in-alt mr-2"></i> Prijavite se
                                            </a>
                                        @endauth

                                        <a href="{{ route('listings.show', $giveaway) }}"
                                            class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                            <i class="fas fa-gift mr-2"></i> Pregled
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach ($giveaways as $giveaway)
                    <div
                        class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500 flex flex-col h-full">
                        <!-- Slika poklonja -->
                        <div class="w-full h-48">
                            <a href="{{ route('listings.show', $giveaway) }}">
                                @if ($giveaway->images->count() > 0)
                                    <img src="{{ $giveaway->images->first()->url }}" alt="{{ $giveaway->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                        <i class="fas fa-gift text-green-500 dark:text-green-400 text-4xl"></i>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Informacije o poklonju -->
                        <div class="p-4 flex flex-col flex-1">
                            <!-- Main content -->
                            <div class="flex-1">
                                <a href="{{ route('listings.show', $giveaway) }}">
                                    <h3
                                        class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-green-600 dark:hover:text-green-400 transition-colors mb-2">
                                        {{ $giveaway->title }}
                                    </h3>
                                </a>

                                {{-- User info --}}
                                @auth
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                        {{ $giveaway->user->name ?? 'Nepoznat korisnik' }}
                                        @if ($giveaway->user)
                                            {!! $giveaway->user->verified_icon !!}
                                        @endif
                                        @if ($giveaway->user && $giveaway->user->is_banned)
                                            <span class="text-red-600 dark:text-red-400 font-bold ml-1">BLOKIRAN</span>
                                        @endif
                                        @if ($giveaway->user && $giveaway->user->shouldShowLastSeen())
                                            <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                                @if ($giveaway->user->is_online)
                                                    <span class="inline-flex items-center">
                                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                        {{ $giveaway->user->last_seen }}
                                                    </span>
                                                @else
                                                    {{ $giveaway->user->last_seen }}
                                                @endif
                                            </span>
                                        @endif
                                    </p>
                                @endauth

                                <!-- Lokacija i kategorija -->
                                <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span class="truncate">{{ Str::limit($giveaway->location, 15) }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-folder mr-1"></i>
                                    <span class="truncate">{{ Str::limit($giveaway->category->name, 15) }}</span>
                                </div>

                                <p class="text-slate-700 dark:text-slate-200 mb-3 text-sm"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($giveaway->description), 80) }}
                                </p>

                                <!-- Vreme -->
                                <div class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pre {{ floor($giveaway->created_at->diffInDays()) }} dana
                                </div>
                            </div>

                            <!-- Dugmići - Always at bottom -->
                            <div class="space-y-2 mt-auto">
                                @auth
                                    @if (auth()->id() !== $giveaway->user_id)
                                        @if ($giveaway->approvedReservation)
                                            <button disabled
                                                class="block w-full text-center px-3 py-2 bg-slate-600 text-white rounded-lg opacity-50 cursor-not-allowed text-sm">
                                                <i class="fas fa-check-circle mr-2"></i> Poklonjeno
                                            </button>
                                        @elseif ($giveaway->giveawayReservations->count() > 0)
                                            <button disabled
                                                class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg opacity-50 cursor-not-allowed text-sm">
                                                <i class="fas fa-paper-plane mr-2"></i> Zahtev poslat
                                            </button>
                                        @elseif ($giveaway->pending_reservations_count >= \App\Models\Setting::get('max_giveaway_requests', 9))
                                            <button disabled
                                                class="block w-full text-center px-3 py-2 bg-amber-600 text-white rounded-lg opacity-50 cursor-not-allowed text-sm">
                                                <i class="fas fa-users mr-2"></i> Max. broj zahteva
                                            </button>
                                        @else
                                            <button wire:click="requestGiveaway({{ $giveaway->id }})"
                                                class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                                <i class="fas fa-hand-paper mr-2"></i> Želim poklon
                                                @if ($giveaway->pending_reservations_count > 0)
                                                    <span
                                                        class="text-xs">({{ $giveaway->pending_reservations_count }}/{{ \App\Models\Setting::get('max_giveaway_requests', 9) }})</span>
                                                @endif
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Prijavite se
                                    </a>
                                @endauth

                                <a href="{{ route('listings.show', $giveaway) }}"
                                    class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                    <i class="fas fa-gift mr-2"></i> Pregled
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Paginacija -->
        @if ($giveaways->hasPages())
            <div class="mt-8 bg-white dark:bg-slate-700 rounded-lg shadow-sm p-4">
                {{ $giveaways->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gift text-green-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema poklona</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">
                @if ($selectedCategory)
                    Trenutno nema poklona u ovoj kategoriji.
                @else
                    Trenutno nema poklona.
                @endif
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-300">Budite prvi koji će pokloniti nešto!</p>
        </div>
    @endif

    <!-- Reservation Modal -->
    @if ($showReservationModal && $selectedGiveaway)
        <div
            class="fixed inset-0 bg-slate-600/50 dark:bg-slate-900/75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div
                class="relative mx-auto p-6 border border-slate-200 dark:border-slate-600 w-full max-w-lg shadow-lg rounded-xl bg-white dark:bg-slate-800">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        <i class="fas fa-gift text-green-500 mr-2"></i>
                        Zahtev za poklon
                    </h3>
                    <button wire:click="closeReservationModal"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Giveaway Info -->
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">
                        {{ $selectedGiveaway->title }}
                    </h4>
                    <p class="text-sm text-green-700 dark:text-green-200">
                        Poklon od: <span class="font-medium">{{ $selectedGiveaway->user->name }}</span>
                    </p>
                </div>

                <!-- Message Form -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Poruka za vlasnika <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="reservationMessage" rows="4" placeholder="Objasnite zašto bi ste želeli ovaj poklon..."
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 focus:outline-none focus:border-green-500 dark:focus:border-green-400 transition-colors"></textarea>
                    @error('reservationMessage')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Minimum 10 karaktera, maksimum 500 karaktera
                    </p>
                </div>

                <!-- Info Alert -->
                <div
                    class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-700 rounded-lg p-3 mb-4">
                    <p class="text-sm text-sky-800 dark:text-sky-200">
                        <i class="fas fa-info-circle mr-2"></i>
                        Vlasnik će dobiti obaveštenje o vašem zahtevu i moći će da vam odobri poklon.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <button wire:click="closeReservationModal"
                        class="px-4 py-2 bg-slate-300 dark:bg-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-400 dark:hover:bg-slate-500 transition-colors">
                        Otkaži
                    </button>
                    <button wire:click="submitReservation"
                        class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Pošalji zahtev
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div
            class="fixed bottom-4 right-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 p-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-2"></i>
                <span class="text-green-800 dark:text-green-200">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="fixed bottom-4 right-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 p-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
                <span class="text-red-800 dark:text-red-200">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
