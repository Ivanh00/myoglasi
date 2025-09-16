<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Navigacija - breadcrumbs -->
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400 dark:text-gray-500">/</span>
                <a href="{{ route('services.index') }}"
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    Usluge
                </a>
            </li>
            @if ($service->category)
                <li class="flex items-center">
                    <span class="mx-2 text-gray-400 dark:text-gray-500">/</span>
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ $service->category->name }}
                    </span>
                </li>
            @endif
            <li class="flex items-center">
                <span class="mx-2 text-gray-400 dark:text-gray-500">/</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium truncate">{{ Str::limit($service->title, 30) }}</span>
            </li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Glavni deo - slika i osnovne informacije -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <!-- Slike usluge -->
            <div>
                @if ($service->images->count() > 0)
                    <div class="relative">
                        <!-- Glavna slika -->
                        <div class="mb-4 rounded-lg overflow-hidden relative">
                            <img id="mainImage" src="{{ $service->images->first()->url }}"
                                alt="{{ $service->title }}" class="w-full h-80 object-cover rounded-lg">
                        </div>

                        <!-- Mala galerija -->
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($service->images as $index => $image)
                                <div
                                    class="cursor-pointer border-2 rounded-lg overflow-hidden
                            {{ $index === 0 ? 'border-blue-500' : 'border-gray-200' }}">
                                    <img src="{{ $image->url }}"
                                        alt="{{ $service->title }} - slika {{ $index + 1 }}"
                                        class="w-full h-20 object-cover"
                                        onclick="changeMainImage('{{ $image->url }}', this)">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-gray-400 text-5xl"></i>
                    </div>
                @endif
            </div>

            <!-- Informacije o usluzi -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $service->title }}</h1>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($service->price, 2) }} RSD</span>
                    <span class="ml-4 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-full">
                        USLUGA
                    </span>
                </div>

                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    @auth
                        <div class="flex items-center mb-1">
                            <i class="fas fa-user text-gray-500 dark:text-gray-400 mr-2"></i>
                            <span class="text-gray-700 dark:text-gray-300 font-bold">
                                Pružalac usluge: {{ $service->user->name }}
                            </span>
                            @if($service->user){!! $service->user->verified_icon !!}@endif
                            @if($service->user->is_banned)
                                <span class="text-red-600 font-bold ml-2">BLOKIRAN</span>
                            @endif
                            @if($service->user->shouldShowLastSeen())
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
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
                        </div>

                        {{-- User ratings --}}
                        @if($service->user->total_ratings_count > 0)
                            <a href="{{ route('user.ratings', $service->user->id) }}" class="inline-flex items-center text-xs text-gray-600 dark:text-gray-400 mb-2 hover:text-blue-600 transition-colors">
                                <span class="text-green-600 mr-1">😊 {{ $service->user->positive_ratings_count }}</span>
                                <span class="text-yellow-600 mr-1">😐 {{ $service->user->neutral_ratings_count }}</span>
                                <span class="text-red-600 mr-1">😞 {{ $service->user->negative_ratings_count }}</span>
                                @if($service->user->rating_badge)
                                    <span class="ml-1">{{ $service->user->rating_badge }}</span>
                                @endif
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        @endif
                    @endauth
                    <div class="flex items-center mb-2">
                        <i class="fas fa-map-marker-alt text-gray-500 dark:text-gray-400 mr-2"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $service->location }}</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-clock text-gray-500 dark:text-gray-400 mr-2"></i>
                        <span class="text-gray-700 dark:text-gray-300">Objavljeno:
                            {{ $service->created_at->format('d.m.Y. H:i') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-eye text-gray-500 dark:text-gray-400 mr-2"></i>
                        <span class="text-gray-700 dark:text-gray-300">Pregleda: {{ $service->views ?? 0 }}</span>
                    </div>
                </div>

                {{-- Prikaz telefona samo ako je vlasnik dozvolio, korisnik ulogovan i pružalac NIJE blokiran --}}
                @if ($service->contact_phone && $service->user->phone_visible && auth()->check() && !$service->user->is_banned)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Kontakt telefon</h3>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-500 mr-2"></i>
                            <a href="tel:{{ $service->contact_phone }}" class="text-xl font-medium text-green-600">
                                {{ $service->contact_phone }}
                            </a>
                        </div>
                    </div>
                @elseif ($service->user->is_banned && auth()->check())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-red-700 dark:text-red-300 font-medium">Kontakt informacije nisu dostupne - pružalac usluge je blokiran</span>
                        </div>
                    </div>
                @endif

                <!-- Desktop Actions -->
                <div class="hidden md:flex space-x-4 mt-8">
                    @auth
                        @if (auth()->id() !== $service->user_id)
                            @if(!$service->user->is_banned)
                                <!-- Dugme za slanje poruke -->
                                <a href="{{ route('messages.inbox') }}"
                                    class="flex-1 flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i> Pošalji poruku
                                </a>

                                <!-- Dugme za deljenje -->
                                <button onclick="shareService()"
                                    class="flex-1 flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i> Podeli
                                </button>
                            @else
                                <!-- Poruka za banovane pružaoce usluga -->
                                <div class="w-full p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-ban text-red-500 mr-2"></i>
                                        <span class="text-red-700 dark:text-red-300 text-sm">Kontakt sa ovim pružaocem usluge nije moguć jer je blokiran.</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Dugme za vlasnike usluge -->
                            <div
                                class="flex items-center justify-center px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg">
                                <i class="fas fa-tools mr-2"></i> Vaša usluga
                            </div>

                            <!-- Dugme za uređivanje svoje usluge -->
                            <a href="{{ route('services.edit', $service) }}"
                                class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Uredi uslugu
                            </a>
                        @endif
                    @else
                        <!-- Dugme za neautentifikovane korisnike -->
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i> Prijavite se za kontakt
                        </a>
                    @endauth
                </div>

                <!-- Mobile Actions -->
                <div class="md:hidden space-y-3 mt-8">
                    @auth
                        @if (auth()->id() !== $service->user_id)
                            @if(!$service->user->is_banned)
                                <!-- Dugme za slanje poruke -->
                                <a href="{{ route('messages.inbox') }}"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i> Pošalji poruku
                                </a>

                                <!-- Dugme za deljenje -->
                                <button onclick="shareService()"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i> Podeli uslugu
                                </button>
                            @else
                                <!-- Poruka za banovane pružaoce -->
                                <div class="w-full p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-ban text-red-500 mr-2"></i>
                                        <span class="text-red-700 dark:text-red-300 text-sm">Kontakt sa ovim pružaocem usluge nije moguć jer je blokiran.</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Dugme za vlasnike usluge -->
                            <div class="w-full p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-center">
                                <i class="fas fa-tools mr-2"></i> Vaša usluga
                            </div>

                            <!-- Dugme za uređivanje svoje usluge -->
                            <a href="{{ route('services.edit', $service) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Uredi uslugu
                            </a>
                        @endif
                    @else
                        <!-- Dugme za neautentifikovane korisnike -->
                        <a href="{{ route('login') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i> Prijavite se za kontakt
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Opis usluge -->
        <div class="border-t border-gray-200 dark:border-gray-600 p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Opis usluge</h2>
            <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $service->description }}</div>
        </div>

        {{-- Uslovi pružanja usluge – prikaz ako postoje --}}
        <div class="border-t border-gray-200 dark:border-gray-600 p-6">
            @if ($service->user->seller_terms)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Uslovi pružanja usluge</h3>
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-gray-700 dark:text-gray-300">
                        {!! nl2br(e($service->user->seller_terms)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Informacije o pružaocu usluge -->
        @auth
            <div class="border-t border-gray-200 dark:border-gray-600 p-6 bg-gray-50 dark:bg-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Informacije o pružaocu usluge</h2>
                <div class="flex items-start">
                    <!-- Avatar -->
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        @if($service->user->avatar)
                            <img src="{{ $service->user->avatar_url }}" alt="{{ $service->user->name }}"
                                 class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($service->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100 text-lg">
                            {{ $service->user->name }}
                            {!! $service->user->verified_icon !!}
                            @if($service->user->is_banned)
                                <span class="text-red-600 font-bold ml-2">BLOKIRAN</span>
                            @endif
                            @if($service->user->shouldShowLastSeen())
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    @if($service->user->is_online)
                                        <span class="inline-flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                            {{ $service->user->last_seen }}
                                        </span>
                                    @else
                                        {{ $service->user->last_seen }}
                                    @endif
                                </div>
                            @endif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">Član od: {{ $service->user->created_at->format('m/Y') }}</p>

                        {{-- User ratings --}}
                        @if($service->user->total_ratings_count > 0)
                            <a href="{{ route('user.ratings', $service->user->id) }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <span class="text-green-600 mr-2">😊 {{ $service->user->positive_ratings_count }}</span>
                                <span class="text-yellow-600 mr-2">😐 {{ $service->user->neutral_ratings_count }}</span>
                                <span class="text-red-600 mr-2">😞 {{ $service->user->negative_ratings_count }}</span>
                                @if($service->user->rating_badge)
                                    <span class="ml-1 mr-2">{{ $service->user->rating_badge }}</span>
                                @endif
                                <span class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Pogledaj ocene</span>
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Još nema ocena</p>
                        @endif
                    </div>
                </div>
            </div>
        @endauth
    </div>

    <!-- Preporučeni oglasi/usluge -->
    @if ($recommendedListings && $recommendedListings->count() > 0)
        <div class="mt-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                @if($recommendationType === 'seller')
                    Ostali oglasi ovog korisnika
                @else
                    Slične usluge
                @endif
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                @if($recommendationType === 'seller')
                    Pogledajte i druge oglase i usluge ovog korisnika
                @else
                    Pronađite slične usluge iz iste kategorije
                @endif
            </p>

            <!-- Lista oglasa/usluga -->
            <div class="space-y-4">
                @foreach ($recommendedListings as $relatedItem)
                    <div class="listing-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300
                        @if($relatedItem instanceof \App\Models\Service)
                            border-l-4 border-gray-500
                        @elseif($relatedItem->isGiveaway())
                            border-l-4 border-green-500
                        @else
                            border-l-4 border-blue-500
                        @endif">
                        <div class="flex flex-col md:flex-row">
                            <!-- Slika -->
                            <div class="w-full md:w-48 md:min-w-48 h-48">
                                @if($relatedItem instanceof \App\Models\Service)
                                    <a href="{{ route('services.show', $relatedItem) }}">
                                @else
                                    <a href="{{ route('listings.show', $relatedItem) }}">
                                @endif
                                    @if ($relatedItem->images->count() > 0)
                                        <img src="{{ $relatedItem->images->first()->url }}" alt="{{ $relatedItem->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            @if($relatedItem instanceof \App\Models\Service)
                                                <i class="fas fa-tools text-gray-400 text-3xl"></i>
                                            @else
                                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                                            @endif
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <!-- Informacije -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-2">
                                            @if($relatedItem instanceof \App\Models\Service)
                                                <a href="{{ route('services.show', $relatedItem) }}" class="flex-1">
                                            @else
                                                <a href="{{ route('listings.show', $relatedItem) }}" class="flex-1">
                                            @endif
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 transition-colors">
                                                    {{ $relatedItem->title }}
                                                </h3>
                                            </a>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $relatedItem->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-folder mr-1"></i>
                                            <span>{{ $relatedItem->category->name ?? '' }}</span>
                                        </div>

                                        <p class="text-gray-700 dark:text-gray-300 mb-3"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($relatedItem->description), 120) }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        @if($relatedItem instanceof \App\Models\Service)
                                            <div class="text-blue-600 font-bold text-xl">
                                                {{ number_format($relatedItem->price, 2) }} RSD
                                            </div>
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-full">
                                                USLUGA
                                            </span>
                                        @elseif($relatedItem->isGiveaway())
                                            <div class="text-green-600 font-bold text-xl">BESPLATNO</div>
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                POKLON
                                            </span>
                                        @else
                                            <div class="text-blue-600 font-bold text-xl">
                                                {{ number_format($relatedItem->price, 2) }} RSD
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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

    function shareService() {
        const url = window.location.href;
        const title = "{{ $service->title }}";

        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Pogledaj ovu uslugu',
                url: url
            });
        } else {
            navigator.clipboard.writeText(url).then(function() {
                alert('Link je kopiran u clipboard!');
            }, function() {
                prompt('Kopiraj ovaj link:', url);
            });
        }
    }
</script>