<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    @if (!$listing)
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-5xl mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Oglas nije pronađen</h2>
            <p class="text-gray-600 mb-4">Traženi oglas ne postoji ili je obrisan.</p>
            <a href="{{ url('/') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Nazad na početnu
            </a>
        </div>
    @else
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
                    <a href="{{ route('category.show', $listing->category->slug) }}"
                        class="text-gray-500 hover:text-gray-700">
                        {{ $listing->category->name }}
                    </a>
                </li>
                @if ($listing->subcategory)
                    <li class="flex items-center">
                        <span class="mx-2 text-gray-400">/</span>
                        <a href="{{ route('category.show', ['category' => $listing->category->slug, 'subcategory' => $listing->subcategory->slug]) }}"
                            class="text-gray-500 hover:text-gray-700">
                            {{ $listing->subcategory->name }}
                        </a>
                    </li>
                @endif
                <li class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-700 font-medium truncate">{{ Str::limit($listing->title, 30) }}</span>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Glavni deo - slika i osnovne informacije -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- Slike oglasa -->
                <div>
                    @if ($listing->images->count() > 0)
                        <div class="relative">
                            <!-- Glavna slika -->
                            <div class="mb-4 rounded-lg overflow-hidden">
                                <img id="mainImage" src="{{ $listing->images->first()->url }}"
                                    alt="{{ $listing->title }}" class="w-full h-80 object-cover rounded-lg">
                            </div>

                            <!-- Mala galerija -->
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($listing->images as $index => $image)
                                    <div
                                        class="cursor-pointer border-2 rounded-lg overflow-hidden 
                                {{ $index === 0 ? 'border-blue-500' : 'border-gray-200' }}">
                                        <img src="{{ $image->url }}"
                                            alt="{{ $listing->title }} - slika {{ $index + 1 }}"
                                            class="w-full h-20 object-cover"
                                            onclick="changeMainImage('{{ $image->url }}', this)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-5xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Informacije o oglasu -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $listing->title }}</h1>

                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-bold text-blue-600">{{ number_format($listing->price, 2) }}
                            RSD</span>
                        @if ($listing->condition)
                            <span class="ml-4 px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                                {{ $listing->condition->name }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                            <span class="text-gray-700">{{ $listing->location }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-clock text-gray-500 mr-2"></i>
                            <span class="text-gray-700">Objavljeno:
                                {{ $listing->created_at->format('d.m.Y. H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-eye text-gray-500 mr-2"></i>
                            <span class="text-gray-700">Pregleda: {{ $listing->views ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- Prikaz telefona samo ako je vlasnik dozvolio i ako je korisnik ulogovan --}}
                    @if ($listing->contact_phone && $listing->user->phone_visible && auth()->check())
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Kontakt telefon</h3>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-green-500 mr-2"></i>
                                <a href="tel:{{ $listing->contact_phone }}" class="text-xl font-medium text-green-600">
                                    {{ $listing->contact_phone }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Akcije -->
                    <div class="flex space-x-4 mt-8">
                        @auth
                            @if (auth()->id() == $listing->user_id)
                                <!-- Akcije za vlasnika oglasa -->
                                <a href="{{ route('listings.edit', $listing) }}"
                                    class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i> Izmeni
                                </a>
                                <a href="{{ route('listings.my') }}"
                                    class="flex items-center justify-center px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-list mr-2"></i> Moji oglasi
                                </a>
                                <button
                                    class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i> Podeli
                                </button>
                            @else
                                <!-- Akcije za ostale ulogovane korisnike -->
                                @if ($listing->user->phone_visible)
                                    <button
                                        class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-phone mr-2"></i> Pozovi
                                    </button>
                                @endif

                                <a href="{{ route('listing.chat', $listing->slug) }}" class="btn btn-primary">
                                    Pošalji poruku
                                </a>
                                <button
                                    class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-heart mr-2"></i> Sačuvaj
                                </button>
                            @endif
                        @else
                            <!-- Akcije za neulogovane korisnike -->
                            <a href="{{ route('register') }}"
                                class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-phone mr-2"></i> Registruj se da bi video detalje o prodavcu
                            </a>
                            {{-- <a href="{{ route('login') }}"
                                class="flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope mr-2"></i> Poruka
                            </a>
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-heart mr-2"></i> Sačuvaj
                            </a> --}}
                        @endauth
                    </div>

                </div>
            </div>

            <!-- Opis oglasa -->
            <div class="border-t border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Opis oglasa</h2>
                <div class="text-gray-700 whitespace-pre-line">{{ $listing->description }}</div>
            </div>

            <!-- Informacije o prodavcu -->
            <div class="border-t border-gray-200 p-6 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informacije o prodavcu</h2>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $listing->user->name }}</h3>
                        <p class="text-gray-600 text-sm">Član od: {{ $listing->user->created_at->format('m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Preporučeni oglasi -->
        @if ($recommendedListings && $recommendedListings->count() > 0)
            <div class="mt-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    @if ($recommendationType === 'seller')
                        Ostali oglasi prodavca {{ $listing->user->name }}
                    @else
                        Slični oglasi
                    @endif
                </h2>

                <p class="text-gray-600 mb-8">
                    @if ($recommendationType === 'seller')
                        Pogledajte i druge oglase ovog prodavca
                    @else
                        Pronađite slične oglase iz iste kategorije
                    @endif
                </p>

                <div class="space-y-4">
                    @foreach ($recommendedListings as $listing)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="flex flex-col md:flex-row">
                                <!-- Slika oglasa - kvadratni kontejner -->
                                <div class="md:w-48 md:min-w-48 h-48 md:h-48">
                                    <!-- Fiksna visina za desktop i mobile -->
                                    <a href="{{ route('listings.show', $listing) }}">
                                        @if ($listing->images->count() > 0)
                                            <img src="{{ $listing->images->first()->url }}"
                                                alt="{{ $listing->title }}" class="w-full h-full object-cover">
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
                                            <p class="text-sm font-bold text-gray-700 mb-2">
                                                {{ $listing->user->name ?? 'Nepoznat korisnik' }}
                                            </p>

                                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span>{{ $listing->location }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-folder mr-1"></i>
                                                <span>{{ $listing->category->name }}</span>
                                                @if ($listing->subcategory)
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $listing->subcategory->name }}</span>
                                                @endif
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
                                <div
                                    class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-gray-200">
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
            </div>
        @endif

        {{-- <!-- Preporučeni oglasi - Grid prikaz -->
        @if ($recommendedListings && $recommendedListings->count() > 0)
            <div class="mt-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    @if ($recommendationType === 'seller')
                        Ostali oglasi ovog prodavca
                    @else
                        Slični oglasi
                    @endif
                </h2>
                <p class="text-gray-600 mb-8">
                    @if ($recommendationType === 'seller')
                        Pogledajte i druge oglase ovog prodavca
                    @else
                        Pronađite slične oglase iz iste kategorije
                    @endif
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($recommendedListings as $listing)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <!-- Slika oglasa -->
                            <div class="h-48">
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
                            <div class="p-4">
                                <a href="{{ route('listings.show', $listing) }}">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                        {{ Str::limit($listing->title, 60) }}
                                    </h3>
                                </a>

                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $listing->location }}</span>
                                </div>

                                <p class="text-gray-700 mb-3 text-sm"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($listing->description), 100) }}
                                </p>

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

                                <div class="flex items-center justify-between mt-4 text-xs text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ $listing->views ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>{{ $listing->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('listings.show', $listing) }}"
                                    class="block w-full text-center mt-4 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    <i class="fas fa-eye mr-2"></i> Pregled oglasa
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif --}}

        {{-- <!-- Slični oglasi -->
        @if ($similarListings && $similarListings->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Slični oglasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($similarListings as $similar)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('listings.show', $similar) }}">
                                @if ($similar->images->count() > 0)
                                    <img src="{{ $similar->images->first()->url }}" alt="{{ $similar->title }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('listings.show', $similar) }}">
                                    <h3
                                        class="font-semibold text-lg text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                        {{ Str::limit($similar->title, 40) }}
                                    </h3>
                                </a>
                                <p class="text-blue-600 font-bold text-xl mb-2">
                                    {{ number_format($similar->price, 2) }} RSD</p>
                                <p class="text-gray-600 text-sm">{{ $similar->location }}</p>
                                <p class="text-gray-400 text-xs mt-2">{{ $similar->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif --}}
    @endif
</div>

<script>
    function changeMainImage(src, element) {
        // Postavi glavnu sliku
        document.getElementById('mainImage').src = src;

        // Ukloni prethodni border
        document.querySelectorAll('.border-blue-500').forEach(item => {
            item.classList.remove('border-blue-500');
            item.classList.add('border-gray-200');
        });

        // Dodaj border na selektovanu sliku
        element.parentElement.classList.remove('border-gray-200');
        element.parentElement.classList.add('border-blue-500');
    }
</script>
