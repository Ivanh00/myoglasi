<div class="max-w-7xl mx-auto py-6 px-0 sm:px-6 lg:px-8">

    <!-- Filteri i sortiranje -->
    <div class="bg-slate-100 dark:bg-slate-700 rounded-lg shadow-md p-4 mb-6">
        <!-- Results Info (Top - Left aligned) -->
        <div class="text-slate-600 dark:text-slate-300 mb-4">
            Pronađeno usluga: <span class="font-semibold">{{ $services->total() }}</span>
            @if ($selectedCategory)
                @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                @if ($selectedCat)
                    u kategoriji: <span class="font-semibold">{{ $selectedCat->name }}</span>
                @endif
            @endif
        </div>

        <!-- Filter Controls -->
        <div class="flex items-center justify-between gap-4">
            <!-- Left: Category filter and Sort -->
            <div class="flex items-center gap-3">
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
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
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.setCategory(''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ !$selectedCategory ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                Sve kategorije
                            </button>
                            @foreach ($categories as $category)
                                <button @click="$wire.setCategory('{{ $category->id }}'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center {{ $selectedCategory == $category->id ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-200' }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} text-sky-600 dark:text-sky-400 mr-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sort dropdown -->
                <div class="w-40" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 focus:outline-none focus:border-sky-500 transition-colors flex items-center justify-between">
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
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 rounded-lg shadow-lg">
                            <button @click="$wire.setSorting('newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg">
                                Najnovije
                            </button>
                            <button @click="$wire.setSorting('price_asc'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                                Cena ↑
                            </button>
                            <button @click="$wire.setSorting('price_desc'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg">
                                Cena ↓
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: View Mode Toggle -->
            <div class="flex bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm">
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

    <!-- Lista/Grid usluga -->
    @if ($services->count() > 0)
        @if($viewMode === 'list')
            <!-- List View -->
            <div class="space-y-4 mb-8">
                @foreach ($services as $service)
                <div
                    class="rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4
                    {{ $service->hasActivePromotion('urgent') ? 'border-red-500' : ($service->hasActivePromotion('featured') ? 'border-sky-500' : ($service->hasActivePromotion('top') ? 'border-purple-500' : 'border-slate-500')) }}
                    {{ $service->hasActivePromotion('highlighted') ? 'bg-amber-50 dark:bg-amber-900' : 'bg-white dark:bg-slate-700' }}">
                    <div class="flex flex-col md:flex-row">
                        <!-- Slika usluge -->
                        <div class="w-full md:w-48 md:min-w-48 h-48">
                            @if ($service->images->count() > 0)
                                <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                    <i class="fas fa-tools text-slate-400 text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informacije o usluzi -->
                        <div class="flex-1 p-4 md:p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <div class="flex items-start">
                                        <h3
                                            class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2 hover:text-sky-600 transition-colors">
                                            {{ $service->title }}
                                        </h3>

                                        <!-- Promotion Badges -->
                                        @if ($service->hasActivePromotion())
                                            <div class="flex flex-wrap gap-1 ml-2">
                                                @foreach ($service->getPromotionBadges() as $badge)
                                                    <span
                                                        class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                        {{ $badge['text'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Pružalac usluge -->
                                    @auth
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                                            Pružalac: {{ $service->user->name ?? 'Nepoznat korisnik' }}
                                            @if ($service->user)
                                                {!! $service->user->verified_icon !!}
                                            @endif
                                            @if ($service->user && $service->user->is_banned)
                                                <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                            @endif
                                            @if ($service->user && $service->user->shouldShowLastSeen())
                                                <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                                    @if ($service->user->is_online)
                                                        <span class="inline-flex items-center">
                                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                            {{ $service->user->last_seen }}
                                                        </span>
                                                    @else
                                                        {{ $service->user->last_seen }}
                                                    @endif
                                                </span>
                                            @endif
                                        </p>
                                    @endauth

                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-400 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $service->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-tools mr-1"></i>
                                        <span>{{ $service->category->name }}</span>
                                    </div>

                                    <p class="text-slate-700 dark:text-slate-200 mb-3"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($service->description), 120) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-sky-600 dark:text-sky-400 font-bold text-xl">
                                        {{ number_format($service->price, 2) }} RSD
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Desna strana - akcije -->
                        <div
                            class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800">
                            <div class="flex flex-col h-full justify-between">
                                <div
                                    class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $service->views ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-slate-700 dark:text-slate-200 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    Postavljeno pre {{ floor($service->created_at->diffInDays()) }} dana
                                </div>

                                <div class="space-y-2">
                                    @auth
                                        @if ($service->user_id === auth()->id())
                                            <a href="{{ route('services.edit', $service->slug) }}"
                                                class="block w-full text-center px-3 py-2 bg-slate-600 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-700 dark:hover:bg-slate-500 transition-colors text-sm">
                                                <i class="fas fa-edit mr-2"></i> Uredi uslugu
                                            </a>
                                        @else
                                            <a href="{{ route('services.show', $service) }}"
                                                class="block w-full text-center px-3 py-2 bg-slate-600 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-700 dark:hover:bg-slate-500 transition-colors text-sm">
                                                <i class="fas fa-eye mr-2"></i> Pregled
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('services.show', $service) }}"
                                            class="block w-full text-center px-3 py-2 bg-slate-600 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-700 dark:hover:bg-slate-500 transition-colors text-sm">
                                            <i class="fas fa-eye mr-2"></i> Pregled
                                        </a>
                                    @endauth
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
                @foreach ($services as $service)
                    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-t-4 flex flex-col h-full
                        {{ $service->hasActivePromotion('urgent') ? 'border-red-500' : ($service->hasActivePromotion('featured') ? 'border-sky-500' : ($service->hasActivePromotion('top') ? 'border-purple-500' : 'border-slate-500')) }}
                        {{ $service->hasActivePromotion('highlighted') ? 'bg-amber-50 dark:bg-amber-900' : '' }}">
                        <!-- Slika usluge -->
                        <div class="w-full h-48">
                            @if ($service->images->count() > 0)
                                <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-tools text-slate-400 dark:text-slate-500 text-4xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informacije o usluzi -->
                        <div class="p-4 flex flex-col flex-1">
                            <!-- Main content -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">
                                        {{ $service->title }}
                                    </h3>
                                    <!-- Promotion Badges -->
                                    @if ($service->hasActivePromotion())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($service->getPromotionBadges() as $badge)
                                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                    {{ $badge['text'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <p class="text-sm text-slate-600 dark:text-slate-300 mb-3 line-clamp-2">
                                    {{ Str::limit($service->description, 100) }}
                                </p>

                                <!-- Kategorija -->
                                @if($service->serviceCategory)
                                    <div class="flex items-center text-xs text-slate-600 dark:text-slate-400 mb-3">
                                        @if ($service->serviceCategory->icon)
                                            <i class="{{ $service->serviceCategory->icon }} mr-1"></i>
                                        @endif
                                        {{ $service->serviceCategory->name }}
                                    </div>
                                @endif

                                <!-- Cena -->
                                <div class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-3">
                                    {{ number_format($service->price, 0, ',', '.') }} RSD
                                </div>

                                <!-- Korisnik i vreme -->
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                                    <div>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $service->user->name ?? 'Nepoznat korisnik' }}
                                    </div>
                                    <div>
                                        <i class="fas fa-clock mr-1"></i>
                                        Pre {{ floor($service->created_at->diffInDays()) }} dana
                                    </div>
                                </div>
                            </div>

                            <!-- Dugmići - Always at bottom -->
                            <div class="space-y-2 mt-auto">
                                @auth
                                    @if ($service->user_id === auth()->id())
                                        <a href="{{ route('services.edit', $service->slug) }}"
                                            class="block w-full text-center px-3 py-2 bg-slate-600 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-700 dark:hover:bg-slate-500 transition-colors text-sm">
                                            <i class="fas fa-edit mr-2"></i> Uredi
                                        </a>
                                    @else
                                        <a href="{{ route('services.show', $service) }}"
                                            class="block w-full text-center px-3 py-2 bg-slate-600 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-700 dark:hover:bg-slate-500 transition-colors text-sm">
                                            <i class="fas fa-eye mr-2"></i> Pregled
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('services.show', $service) }}"
                                        class="block w-full text-center px-3 py-2 bg-slate-600 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-700 dark:hover:bg-slate-500 transition-colors text-sm">
                                        <i class="fas fa-eye mr-2"></i> Pregled
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
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
