<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Navigacija - breadcrumbs -->
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700">
                    Kategorije
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-700 font-medium">{{ $category->name }}</span>
            </li>
            @if ($subcategory)
                <li class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-700 font-medium">{{ $subcategory->name }}</span>
                </li>
            @endif
        </ol>
    </nav>

    <!-- Naslov i filteri -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            @if ($subcategory)
                {{ $subcategory->name }} - Oglasi
            @else
                {{ $category->name }} - Oglasi
            @endif
        </h1>

        <!-- Traka sa filterima i sortiranjem -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="text-gray-600">
                Pronađeno oglasa: <span class="font-semibold">{{ $listings->total() }}</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Sortiranje -->
                <div class="relative">
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 13L1 8l5-5m0 10l5 5-5 5"></path>
                        </svg>
                        <span>Sortiraj</span>
                    </button>
                </div>

                <!-- Broj oglasa po strani -->
                <div class="relative">
                    <select
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="20">20 po strani</option>
                        <option value="50">50 po strani</option>
                        <option value="100">100 po strani</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista oglasa -->
    @if ($listings->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach ($listings as $listing)
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <!-- Slika oglasa -->
                        <div class="md:w-48 md:min-w-48 h-48 md:h-auto">
                            <a href="{{ route('listings.show', $listing) }}">
                                @if ($listing->images->count() > 0)
                                    <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Informacije o oglasu -->
                        <div class="flex-1 p-4">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <a href="{{ route('listings.show', $listing) }}">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                            {{ $listing->title }}
                                        </h3>
                                    </a>

                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $listing->location }}</span>
                                    </div>

                                    <p class="text-gray-700 mb-3"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($listing->description), 120) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-blue-600 font-bold text-xl">
                                        {{ number_format($listing->price, 2) }} RSD
                                    </div>

                                    @if ($listing->condition)
                                        <span
                                            class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                            {{ $listing->condition->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Desna strana - akcije i dodatne informacije -->
                        <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-gray-200">
                            <div class="flex flex-col h-full justify-between">
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $listing->views ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-heart mr-1"></i>
                                        <span>0</span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-400 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $listing->created_at->diffForHumans() }}
                                </div>

                                @auth
                                    @if (auth()->id() == $listing->user_id)
                                        <!-- Akcije za vlasnika oglasa -->
                                        <div class="space-y-2">
                                            <a href="{{ route('listings.edit', $listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                <i class="fas fa-edit mr-2"></i> Izmeni oglas
                                            </a>
                                            <a href="{{ route('listings.my') }}"
                                                class="block w-full text-center px-3 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm">
                                                <i class="fas fa-list mr-2"></i> Svi moji oglasi
                                            </a>
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                                <i class="fas fa-share-alt mr-2"></i> Podeli oglas
                                            </button>
                                        </div>
                                    @else
                                        <!-- Akcije za ostale ulogovane korisnike -->
                                        <div class="space-y-2">
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                <i class="fas fa-phone mr-2"></i> Pozovi
                                            </button>
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                                <i class="fas fa-envelope mr-2"></i> Poruka
                                            </button>
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                                <i class="fas fa-heart mr-2"></i> Sačuvaj
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <!-- Akcije za neulogovane korisnike -->
                                    <div class="space-y-2">
                                        <a href="{{ route('login') }}"
                                            class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            <i class="fas fa-phone mr-2"></i> Pozovi
                                        </a>
                                        <a href="{{ route('login') }}"
                                            class="block w-full text-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                            <i class="fas fa-envelope mr-2"></i> Poruka
                                        </a>
                                        <a href="{{ route('login') }}"
                                            class="block w-full text-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                            <i class="fas fa-heart mr-2"></i> Sačuvaj
                                        </a>
                                    </div>
                                @endauth

                                <div class="mt-4 flex justify-end">
                                    <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacija -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-4">
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema oglasa u ovoj kategoriji</h3>
            <p class="text-gray-600 mb-4">Trenutno nema aktivnih oglasa u ovoj kategoriji. Pokušajte kasnije ili
                pogledajte druge kategorije.</p>
            <a href="{{ route('categories.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Pogledaj sve kategorije
            </a>
        </div>
    @endif

    <!-- Podkategorije (ako postoje) -->
    @if ($subcategories->count() > 0 && !$subcategory)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Podkategorije</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($subcategories as $subcat)
                    <a href="{{ route('category.show', ['category' => $category->slug, 'subcategory' => $subcat->slug]) }}"
                        class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-folder text-blue-500"></i>
                        </div>
                        <h3 class="font-medium text-gray-900">{{ $subcat->name }}</h3>
                        <p class="text-gray-500 text-sm mt-1">{{ $subcat->listings_count }} oglasa</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
