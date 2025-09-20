<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Navigacija - breadcrumbs -->
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-500">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-slate-400">/</span>
                <a href="{{ route('categories.index') }}" class="text-slate-500 hover:text-slate-700">
                    Kategorije
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-slate-400">/</span>
                <span class="text-slate-700 dark:text-slate-200 font-medium">{{ $category->name }}</span>
            </li>
            @if ($subcategory)
                <li class="flex items-center">
                    <span class="mx-2 text-slate-400">/</span>
                    <span class="text-slate-700 dark:text-slate-200 font-medium">{{ $subcategory->name }}</span>
                </li>
            @endif
        </ol>
    </nav>

    <!-- Naslov i filteri -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">
            @if ($subcategory)
                {{ $subcategory->name }} - Oglasi
            @else
                {{ $category->name }} - Oglasi
            @endif
        </h1>

        <!-- Traka sa filterima i sortiranjem -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 bg-white rounded-lg shadow-sm border border-slate-200">
            <div class="text-slate-600 dark:text-slate-400">
                Pronađeno oglasa: <span class="font-semibold">{{ $listings->total() }}</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Sortiranje -->
                <div class="relative">
                    <select wire:model.live="sortBy"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="newest">Najnovije</option>
                        <option value="price_asc">Cena: niža → viša</option>
                        <option value="price_desc">Cena: viša → niža</option>
                    </select>
                </div>

                <!-- Broj oglasa po strani -->
                <div class="relative">
                    <select wire:model.live="perPage"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
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
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-3xl"></i>
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
                                            class="text-lg font-semibold text-slate-900 mb-2 hover:text-sky-600 transition-colors">
                                            {{ $listing->title }}
                                        </h3>
                                    </a>

                                    {{-- Korisničko ime kreatora --}}
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                                        {{ $listing->user->name ?? 'Nepoznat korisnik' }}
                                    </p>

                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-400 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $listing->location }}</span>
                                    </div>

                                    <p class="text-slate-700 dark:text-slate-200 mb-3"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($listing->description), 120) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-sky-600 dark:text-sky-400 font-bold text-xl">
                                        {{ number_format($listing->price, 2) }} RSD
                                    </div>

                                    @if ($listing->condition)
                                        <span
                                            class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-medium rounded-full">
                                            {{ $listing->condition->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Desna strana - akcije i dodatne informacije -->
                        <div class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200">
                            <div class="flex flex-col h-full justify-between">
                                <div
                                    class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $listing->views ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-heart mr-1"></i>
                                        <span>0</span>
                                    </div>
                                </div>

                                <div class="text-xs text-slate-400 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $listing->created_at->diffForHumans() }}
                                </div>

                                @auth
                                    @if (auth()->id() == $listing->user_id)
                                        <!-- Akcije za vlasnika oglasa -->
                                        <div class="space-y-2">
                                            <a href="{{ route('listings.edit', $listing) }}"
                                                class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                <i class="fas fa-edit mr-2"></i> Izmeni oglas
                                            </a>
                                            <a href="{{ route('listings.my') }}"
                                                class="block w-full text-center px-3 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50 transition-colors text-sm">
                                                <i class="fas fa-list mr-2"></i> Svi moji oglasi
                                            </a>
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                                                <i class="fas fa-share-alt mr-2"></i> Podeli oglas
                                            </button>
                                        </div>
                                    @else
                                        <!-- Akcije za ostale ulogovane korisnike -->
                                        <div class="space-y-2">
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                <i class="fas fa-phone mr-2"></i> Pozovi
                                            </button>
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                                                <i class="fas fa-envelope mr-2"></i> Poruka
                                            </button>
                                            <button
                                                class="w-full flex items-center justify-center px-3 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                                                <i class="fas fa-heart mr-2"></i> Sačuvaj
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <!-- Akcije za neulogovane korisnike -->
                                    <div class="space-y-2">
                                        <a href="{{ route('login') }}"
                                            class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                            <i class="fas fa-phone mr-2"></i> Pozovi
                                        </a>
                                        <a href="{{ route('login') }}"
                                            class="block w-full text-center px-3 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                                            <i class="fas fa-envelope mr-2"></i> Poruka
                                        </a>
                                        <a href="{{ route('login') }}"
                                            class="block w-full text-center px-3 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                                            <i class="fas fa-heart mr-2"></i> Sačuvaj
                                        </a>
                                    </div>
                                @endauth

                                <div class="mt-4 flex justify-end">
                                    <button class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
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
            <i class="fas fa-search text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema oglasa u ovoj kategoriji</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">Trenutno nema aktivnih oglasa u ovoj kategoriji.
                Pokušajte kasnije ili
                pogledajte druge kategorije.</p>
            <a href="{{ route('categories.index') }}"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                Pogledaj sve kategorije
            </a>
        </div>
    @endif

    <!-- Podkategorije (ako postoje) -->
    @if ($subcategories->count() > 0 && !$subcategory)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Podkategorije</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($subcategories as $subcat)
                    <a href="{{ route('category.show', ['category' => $category->slug, 'subcategory' => $subcat->slug]) }}"
                        class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-folder text-sky-500"></i>
                        </div>
                        <h3 class="font-medium text-slate-900">{{ $subcat->name }}</h3>
                        <p class="text-slate-500 dark:text-slate-300 text-sm mt-1">{{ $subcat->listings_count }}
                            oglasa</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
