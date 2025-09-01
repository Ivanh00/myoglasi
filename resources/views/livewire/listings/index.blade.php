<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Svi oglasi</h1>
        <p class="text-gray-600">Pronađite najbolje ponude iz svih kategorija</p>
    </div>

    <!-- Traka sa kategorijama -->
    <div class="mb-8 overflow-hidden">
        <div class="flex space-x-4 pb-4 overflow-x-auto scrollbar-hide">
            <!-- Sve kategorije -->
            <a href="{{ route('listings.index') }}"
                class="flex-shrink-0 px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors whitespace-nowrap {{ !$selectedCategory ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14-8H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
                    </svg>
                    Sve kategorije
                </div>
            </a>

            <!-- Pojedinačne kategorije -->
            @foreach ($categories as $category)
                <a href="{{ route('listings.index', ['selectedCategory' => $category->id]) }}"
                    class="flex-shrink-0 px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                    <div class="flex items-center">
                        @if ($category->icon)
                            <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-5 h-5 mr-2">
                        @else
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                        @endif
                        {{ $category->name }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Filteri i sortiranje -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="text-gray-600">
                Pronađeno oglasa: <span class="font-semibold">{{ $listings->total() }}</span>
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

            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Sortiranje -->
                <div class="relative">
                    <select wire:model.live="sortBy"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="newest">Najnovije</option>
                        <option value="price_asc">Cena: niža → viša</option>
                        <option value="price_desc">Cena: viša → niža</option>
                    </select>
                </div>

                <!-- Broj oglasa po strani -->
                <div class="relative">
                    <select wire:model.live="perPage"
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
                        <!-- Slika oglasa - kvadratni kontejner -->
                        <div class="md:w-48 md:min-w-48 h-48 md:h-48"> <!-- Fiksna visina za desktop i mobile -->
                            <a href="{{ route('listings.show', $listing) }}">
                                @if ($listing->images->count() > 0)
                                    <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover">
                                    <!-- object-cover osigurava da slika popuni kontejner -->
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

                                    {{-- Korisničko ime kreatora --}}
                                    @auth
                                        <p class="text-sm font-bold text-gray-700 mb-2">
                                            Prodavac: {{ $listing->user->name ?? 'Nepoznat korisnik' }}
                                        </p>
                                    @endauth

                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>{{ $listing->location }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-folder mr-1"></i>
                                        <span>{{ $listing->category->name }}</span>
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
                                        <svg class="w-4 h-4 mr-1 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 4.5C7.5 4.5 3.5 8.5 2 12c1.5 3.5 5.5 7.5 10 7.5s8.5-4 10-7.5c-1.5-3.5-5.5-7.5-10-7.5zm0 12c-2.5 0-4.5-2-4.5-4.5S9.5 8.5 12 8.5 16.5 10.5 16.5 12 14.5 16.5 12 16.5zm0-7c-1.5 0-2.5 1-2.5 2.5S10.5 14.5 12 14.5 14.5 13.5 14.5 12 13.5 9.5 12 9.5z" />
                                        </svg>
                                        <span class="text-gray-700">{{ $listing->views ?? 0 }}</span>
                                    </div>
                                    <!-- Dodajte ovaj div za prikaz broja pratilaca -->
                                    <div class="flex items-center">
                                        <i class="fas fa-heart text-red-500 mr-2"></i>
                                        <span class="text-gray-700">❤️ {{ $listing->favorites_count ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-700 mb-4">
                                    <i class="fas fa-clock mr-1"></i>
                                    Postavljeno pre {{ floor($listing->created_at->diffInDays()) }} dana
                                </div>

                                <div class="space-y-2">
                                    <a href="{{ route('listings.show', $listing) }}"
                                        class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
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
        <div class="mt-8 bg-white rounded-lg shadow-sm p-4">
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema oglasa</h3>
            <p class="text-gray-600 mb-4">
                @if ($selectedCategory)
                    Trenutno nema aktivnih oglasa u ovoj kategoriji.
                @else
                    Trenutno nema aktivnih oglasa.
                @endif
            </p>
            <a href="{{ route('listings.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Dodaj prvi oglas
            </a>
        </div>
    @endif
</div>
