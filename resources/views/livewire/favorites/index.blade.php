<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Omiljeni oglasi</h1>
        <p class="text-gray-600 mt-2">Vaši sačuvani oglasi</p>
    </div>

    <!-- Sortiranje -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">Sortiraj po:</label>
            <select wire:model="sortBy" wire:change="setSortBy($event.target.value)"
                class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                <option value="newest">Najnovije dodato</option>
                <option value="oldest">Najstarije dodato</option>
                <option value="price_asc">Cena rastući</option>
                <option value="price_desc">Cena opadajući</option>
            </select>
        </div>
        <div class="text-sm text-gray-500">
            {{ $favorites->total() }} omiljenih oglasa
        </div>
    </div>

    <!-- Flash poruke -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Grid oglasa -->
    @if ($favorites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach ($favorites as $listing)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Slika -->
                    <div class="relative">
                        <a href="{{ route('listings.show', $listing) }}">
                            @if ($listing->images->count() > 0)
                                <img class="w-full h-48 object-cover" src="{{ $listing->images->first()->url }}"
                                    alt="{{ $listing->title }}">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Status badge -->
                        @if ($listing->status === 'active')
                            <span
                                class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                Aktivan
                            </span>
                        @endif
                    </div>

                    <!-- Sadržaj -->
                    <div class="p-4">
                        <div class="mb-2">
                            <a href="{{ route('listings.show', $listing) }}"
                                class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                {{ Str::limit($listing->title, 40) }}
                            </a>
                        </div>

                        <div class="text-sm text-gray-500 mb-2">
                            {{ $listing->category->name }}
                        </div>

                        <div class="text-xl font-bold text-blue-600 mb-3">
                            {{ number_format($listing->price, 0, ',', '.') }} RSD
                        </div>

                        <div class="text-xs text-gray-500 mb-3">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $listing->location }}
                        </div>

                        <!-- Akcije -->
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="{{ route('listings.show', $listing) }}"
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-eye"></i> Pregled
                                </a>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')"
                                    class="text-green-600 hover:text-green-900 text-sm">
                                    <i class="fas fa-share-alt"></i> Podeli
                                </button>
                            </div>

                            <button wire:click="removeFromFavorites({{ $listing->id }})"
                                wire:confirm="Da li ste sigurni da želite da uklonite ovaj oglas iz omiljenih?"
                                class="text-red-600 hover:text-red-900 text-sm">
                                <i class="fas fa-heart"></i> Ukloni
                            </button>
                        </div>

                        <!-- Datum dodavanja u favorite -->
                        <div class="text-xs text-gray-400 mt-2">
                            Dodato u omiljene: {{ $listing->pivot->created_at->format('d.m.Y. H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacija -->
        <div class="mt-6">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-heart text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nemate omiljene oglase</h3>
            <p class="text-gray-600 mb-4">Počnite da čuvate oglase koje volite klikom na srce.</p>
            <a href="{{ route('home') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Pretraži oglase
            </a>
        </div>
    @endif
</div>
