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

            <!-- View mode toggle (mobile) -->
            <div class="flex gap-2">
                <button wire:click="setViewMode('grid')"
                    class="flex-1 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'grid' ? 'bg-purple-600 text-white' : 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-purple-300 dark:border-purple-600' }}">
                    <i class="fas fa-th mr-1"></i> Grid
                </button>
                <button wire:click="setViewMode('list')"
                    class="flex-1 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'list' ? 'bg-purple-600 text-white' : 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-purple-300 dark:border-purple-600' }}">
                    <i class="fas fa-list mr-1"></i> Lista
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop view -->
    <div class="hidden md:block bg-white dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-600 dark:text-slate-300">
                Pronađeno business-a: <span class="font-semibold">{{ $businesses->total() }}</span>
                @if ($currentCategory)
                    u kategoriji: <span class="font-semibold">{{ $currentCategory->name }}</span>
                @endif
            </div>

            <!-- View mode toggle -->
            <div class="flex gap-2">
                <button wire:click="setViewMode('grid')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'grid' ? 'bg-purple-600 text-white' : 'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-200' }}">
                    <i class="fas fa-th mr-1"></i> Grid
                </button>
                <button wire:click="setViewMode('list')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'list' ? 'bg-purple-600 text-white' : 'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-200' }}">
                    <i class="fas fa-list mr-1"></i> Lista
                </button>
            </div>
        </div>

        <!-- Desktop filters -->
        <div class="flex gap-4">
            <!-- Category dropdown -->
            <div class="w-64" x-data="{ open: false }" x-init="open = false">
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
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
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

            <!-- Subcategory dropdown -->
            @if ($selectedCategory && count($subcategories) > 0)
                <div class="w-64" x-data="{ open: false }" x-init="open = false">
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
                                <button
                                    @click="$wire.set('selectedSubcategory', '{{ $subcategory->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-purple-50 dark:hover:bg-purple-900/20 {{ $selectedSubcategory == $subcategory->id ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    {{ $subcategory->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
