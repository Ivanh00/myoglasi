<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Header with Add Service Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Usluge</h1>
            <p class="text-gray-600 dark:text-gray-400">Pronađite ili ponudite usluge u vašoj oblasti</p>
        </div>
        @auth
            <a href="{{ route('services.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Dodaj uslugu
            </a>
        @endauth
    </div>

    <!-- Filteri i sortiranje -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Left: Category filter -->
            <div class="flex items-center gap-3">
                <div class="w-60" x-data="{ open: false }" x-init="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-sm text-gray-700 dark:text-gray-200 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                            <span>
                                @if($selectedCategory)
                                    @php $selectedCat = $categories->firstWhere('id', $selectedCategory); @endphp
                                    {{ $selectedCat ? $selectedCat->name : 'Sve kategorije' }}
                                @else
                                    Sve kategorije
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.setCategory(''); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 rounded-t-lg {{ !$selectedCategory ? 'bg-blue-50 text-blue-700' : '' }}">
                                Sve kategorije
                            </button>
                            @foreach ($categories as $category)
                                <button @click="$wire.setCategory('{{ $category->id }}'); open = false" type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 flex items-center {{ $selectedCategory == $category->id ? 'bg-blue-50 text-blue-700' : '' }}">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-blue-600 mr-2"></i>
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
                            class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-sm text-gray-700 dark:text-gray-200 text-sm text-left hover:border-gray-400 focus:outline-none focus:border-blue-500 transition-colors flex items-center justify-between">
                            <span>
                                @if($sortBy === 'newest') Najnovije
                                @elseif($sortBy === 'price_asc') Cena ↑
                                @elseif($sortBy === 'price_desc') Cena ↓
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-lg">
                            <button @click="$wire.setSorting('newest'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 rounded-t-lg">
                                Najnovije
                            </button>
                            <button @click="$wire.setSorting('price_asc'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50">
                                Cena ↑
                            </button>
                            <button @click="$wire.setSorting('price_desc'); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 rounded-b-lg">
                                Cena ↓
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Results count -->
            <div class="text-gray-600">
                Pronađeno usluga: <span class="font-semibold">{{ $services->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Lista usluga -->
    @if ($services->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach ($services as $service)
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-gray-500">
                    <div class="flex flex-col md:flex-row">
                        <!-- Slika usluge -->
                        <div class="w-full md:w-48 md:min-w-48 h-48">
                            @if ($service->images->count() > 0)
                                <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-tools text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informacije o usluzi -->
                        <div class="flex-1 p-4 md:p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 hover:text-blue-600 transition-colors">
                                        {{ $service->title }}
                                    </h3>

                                    <!-- Pružalac usluge -->
                                    @auth
                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            Pružalac: {{ $service->user->name ?? 'Nepoznat korisnik' }}
                                            @if($service->user){!! $service->user->verified_icon !!}@endif
                                            @if ($service->user && $service->user->is_banned)
                                                <span class="text-red-600 font-bold ml-2">BLOKIRAN</span>
                                            @endif
                                            @if($service->user && $service->user->shouldShowLastSeen())
                                                <span class="text-xs text-gray-500 ml-2">
                                                    @if($service->user->is_online)
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

                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $service->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-tools mr-1"></i>
                                        <span>{{ $service->category->name }}</span>
                                    </div>

                                    <p class="text-gray-700 dark:text-gray-200 mb-3"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($service->description), 120) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-blue-600 font-bold text-xl">
                                        {{ number_format($service->price, 2) }} RSD
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Desna strana - akcije -->
                        <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-600">
                            <div class="flex flex-col h-full justify-between">
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $service->views ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-700 dark:text-gray-200 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    Postavljeno pre {{ floor($service->created_at->diffInDays()) }} dana
                                </div>

                                <div class="space-y-2">
                                    <a href="{{ route('services.show', $service) }}"
                                        class="block w-full text-center px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                        <i class="fas fa-eye mr-2"></i> Pregled
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacija -->
        <div class="mt-8 bg-white dark:bg-gray-700 rounded-lg shadow-sm p-4">
            {{ $services->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-tools text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Nema usluga</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if ($selectedCategory)
                    Trenutno nema aktivnih usluga u ovoj kategoriji.
                @else
                    Trenutno nema aktivnih usluga.
                @endif
            </p>
            @auth
                <a href="{{ route('services.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Dodaj prvu uslugu
                </a>
            @endauth
        </div>
    @endif
</div>