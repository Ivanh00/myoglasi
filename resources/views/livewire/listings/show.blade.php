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

                    @if ($listing->contact_phone)
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
                                <button
                                    class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-phone mr-2"></i> Pozovi
                                </button>
                                <button
                                    class="flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i> Poruka
                                </button>
                                <button
                                    class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-heart mr-2"></i> Sačuvaj
                                </button>
                            @endif
                        @else
                            <!-- Akcije za neulogovane korisnike -->
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-phone mr-2"></i> Pozovi
                            </a>
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope mr-2"></i> Poruka
                            </a>
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-heart mr-2"></i> Sačuvaj
                            </a>
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

        <!-- Slični oglasi -->
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
        @endif
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
