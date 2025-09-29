<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Navigacija - breadcrumbs -->
    {{-- <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-300">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-slate-500 dark:text-slate-300">/</span>
                <a href="{{ route('services.index') }}"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                    Usluge
                </a>
            </li>
            @if ($service->category)
                <li class="flex items-center">
                    <span class="mx-2 text-slate-500 dark:text-slate-300">/</span>
                    <span class="text-slate-500 dark:text-slate-300">
                        {{ $service->category->name }}
                    </span>
                </li>
            @endif
            <li class="flex items-center">
                <span class="mx-2 text-slate-500 dark:text-slate-300">/</span>
                <span
                    class="text-slate-700 dark:text-slate-200 font-medium truncate">{{ Str::limit($service->title, 30) }}</span>
            </li>
        </ol>
    </nav> --}}

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Glavni deo - slika i osnovne informacije -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-0 md:p-6">
            <!-- Slike usluge -->
            <div>
                @if ($service->images->count() > 0)
                    @php
                        $imageArray = $service->images
                            ->map(function ($img) use ($service) {
                                return ['url' => $img->url, 'alt' => $service->title];
                            })
                            ->toArray();
                    @endphp
                    <x-image-lightbox :images="$imageArray" :title="$service->title">
                        <div class="relative">
                            <!-- Glavna slika -->
                            <div class="mb-4 rounded-lg overflow-hidden relative">
                                <img id="mainImage" src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                    class="w-full h-80 object-cover rounded-lg cursor-pointer hover:opacity-95 transition-opacity"
                                    @click="openLightbox(0)">
                                <!-- Zoom icon overlay -->
                                <div
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 hover:opacity-100 transition-opacity bg-black bg-opacity-20">
                                    <div class="bg-white bg-opacity-90 rounded-full p-3">
                                        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Mala galerija -->
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($service->images as $index => $image)
                                    <div
                                        class="cursor-pointer border-2 rounded-lg overflow-hidden
                                {{ $index === 0 ? 'border-sky-500' : 'border-slate-200' }}">
                                        <img src="{{ $image->url }}"
                                            alt="{{ $service->title }} - slika {{ $index + 1 }}"
                                            class="w-full h-20 object-cover"
                                            @click="currentIndex = {{ $index }}; changeMainImage('{{ $image->url }}', $el)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-image-lightbox>
                @else
                    <div class="w-full h-80 bg-slate-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-slate-400 text-5xl"></i>
                    </div>
                @endif
            </div>

            <!-- Informacije o usluzi -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $service->title }}</h1>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-3xl font-bold text-sky-600 dark:text-sky-400">
                        @if ($service->price_type === 'fixed')
                            {{ number_format($service->price, 0, ',', '.') }} RSD
                        @elseif($service->price_type === 'hourly')
                            {{ number_format($service->price, 0, ',', '.') }} RSD/sat
                        @elseif($service->price_type === 'daily')
                            {{ number_format($service->price, 0, ',', '.') }} RSD/dan
                        @elseif($service->price_type === 'per_m2')
                            {{ number_format($service->price, 0, ',', '.') }} RSD/m²
                        @else
                            Po dogovoru
                        @endif
                    </span>
                    <span
                        class="ml-4 px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-sm font-medium rounded-full">
                        USLUGA
                    </span>
                </div>

                <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                    @auth
                        <div class="flex items-center mb-1">
                            <i class="fas fa-user text-slate-500 dark:text-slate-400 mr-2"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-bold">
                                Pružalac usluge: {{ $service->user->name }}
                            </span>
                            @if ($service->user)
                                {!! $service->user->verified_icon !!}
                            @endif
                            @if ($service->user->is_banned)
                                <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                            @endif
                            @if ($service->user->shouldShowLastSeen())
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
                        </div>

                        {{-- User ratings --}}
                        @if ($service->user->total_ratings_count > 0)
                            <a href="{{ route('user.ratings', $service->user->id) }}"
                                class="inline-flex items-center text-xs text-slate-600 dark:text-slate-400 mb-2 hover:text-sky-600 transition-colors">
                                <span class="text-green-600 dark:text-green-400 mr-1">😊
                                    {{ $service->user->positive_ratings_count }}</span>
                                <span class="text-amber-600 dark:text-amber-400 mr-1">😐
                                    {{ $service->user->neutral_ratings_count }}</span>
                                <span class="text-red-600 dark:text-red-400 mr-1">😞
                                    {{ $service->user->negative_ratings_count }}</span>
                                @if ($service->user->rating_badge)
                                    <span class="ml-1">{{ $service->user->rating_badge }}</span>
                                @endif
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        @endif
                    @endauth
                    <div class="flex items-center mb-2">
                        <i class="fas fa-map-marker-alt text-slate-500 dark:text-slate-300 mr-2"></i>
                        <span class="text-slate-700 dark:text-slate-200">{{ $service->location }}</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-clock text-slate-500 dark:text-slate-300 mr-2"></i>
                        <span class="text-slate-700 dark:text-slate-200">Objavljeno:
                            {{ $service->created_at->format('d.m.Y. H:i') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-eye text-slate-500 dark:text-slate-300 mr-2"></i>
                        <span class="text-slate-700 dark:text-slate-200">Pregleda: {{ $service->views ?? 0 }}</span>
                    </div>
                </div>

                {{-- Prikaz telefona samo ako je vlasnik dozvolio, korisnik ulogovan i pružalac NIJE blokiran --}}
                @if ($service->contact_phone && $service->user->phone_visible && auth()->check() && !$service->user->is_banned)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Kontakt telefon</h3>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-500 mr-2"></i>
                            <a href="tel:{{ $service->contact_phone }}"
                                class="text-xl font-medium text-green-600 dark:text-green-400">
                                {{ $service->contact_phone }}
                            </a>
                        </div>
                    </div>
                @elseif ($service->user->is_banned && auth()->check())
                    <div
                        class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-red-700 dark:text-red-200 font-medium">Kontakt informacije nisu dostupne -
                                pružalac usluge je blokiran</span>
                        </div>
                    </div>
                @endif

                <!-- Desktop Actions -->
                <div class="hidden md:block space-y-3 mt-8">
                    @auth
                        @if (auth()->id() !== $service->user_id)
                            @if (!$service->user->is_banned)
                                <!-- Favorite dugme (Livewire komponenta) -->
                                <div class="w-full" id="service-favorite-button-desktop">
                                    <livewire:service-favorite-button :service="$service" />
                                </div>

                                <!-- Dugme za slanje poruke -->
                                <a href="{{ route('messages.inbox') }}"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                        </path>
                                    </svg> Pošalji poruku
                                </a>

                                <!-- Dugme za deljenje -->
                                <button onclick="shareService()"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i> Podeli
                                </button>
                            @else
                                <!-- Poruka za banovane pružaoce usluga -->
                                <div
                                    class="w-full p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-ban text-red-500 mr-2"></i>
                                        <span class="text-red-700 dark:text-red-200 text-sm">Kontakt sa ovim pružaocem
                                            usluge nije moguć jer je blokiran.</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Dugme za vlasnike usluge -->
                            <div
                                class="w-full flex items-center justify-center px-4 py-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg mb-3">
                                <i class="fas fa-tools mr-2"></i> Vaša usluga
                            </div>

                            <!-- Dugme za uređivanje svoje usluge -->
                            <a href="{{ route('services.edit', $service) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Uredi uslugu
                            </a>
                        @endif
                    @else
                        <!-- Dugme za neautentifikovane korisnike -->
                        <div class="w-full" id="service-favorite-button-desktop">
                            <livewire:service-favorite-button :service="$service" />
                        </div>

                        <a href="{{ route('login') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                </path>
                            </svg> Prijavite se za kontakt
                        </a>

                        <!-- Dugme za deljenje (dostupno svima) -->
                        <button onclick="shareService()"
                            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <i class="fas fa-share-alt mr-2"></i> Podeli
                        </button>
                    @endauth
                </div>

                <!-- Mobile Actions -->
                <div class="md:hidden space-y-3 mt-8">
                    @auth
                        @if (auth()->id() !== $service->user_id)
                            @if (!$service->user->is_banned)
                                <!-- Favorite dugme (shared component) -->
                                <div class="w-full" id="service-favorite-button-mobile"></div>

                                <!-- Dugme za slanje poruke -->
                                <a href="{{ route('messages.inbox') }}"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                        </path>
                                    </svg> Pošalji poruku
                                </a>

                                <!-- Dugme za deljenje -->
                                <button onclick="shareService()"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i> Podeli uslugu
                                </button>
                            @else
                                <!-- Poruka za banovane pružaoce -->
                                <div
                                    class="w-full p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-ban text-red-500 mr-2"></i>
                                        <span class="text-red-700 dark:text-red-200 text-sm">Kontakt sa ovim pružaocem
                                            usluge nije moguć jer je blokiran.</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Dugme za vlasnike usluge -->
                            <div
                                class="w-full p-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg text-center">
                                <i class="fas fa-tools mr-2"></i> Vaša usluga
                            </div>

                            <!-- Dugme za uređivanje svoje usluge -->
                            <a href="{{ route('services.edit', $service) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Uredi uslugu
                            </a>
                        @endif
                    @else
                        <!-- Dugme za neautentifikovane korisnike -->
                        <div class="w-full" id="service-favorite-button-mobile"></div>

                        <a href="{{ route('login') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                </path>
                            </svg> Prijavite se za kontakt
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Opis usluge -->
        <div class="border-t border-slate-200 dark:border-slate-600 p-2 md:p-6">
            <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-4">Opis usluge</h2>
            <div class="text-slate-700 dark:text-slate-200 whitespace-pre-line">{{ $service->description }}</div>
        </div>

        {{-- Uslovi pružanja usluge – prikaz ako postoje --}}
        <div class="border-t border-slate-200 dark:border-slate-600 p-2 md:p-6">
            @if ($service->user->seller_terms)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Uslovi pružanja usluge
                    </h3>
                    <div class="bg-slate-100 dark:bg-slate-700 p-4 rounded-lg text-slate-700 dark:text-slate-300">
                        {!! nl2br(e($service->user->seller_terms)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Seller Information Section -->
        @auth
            @if (auth()->id() !== $service->user_id)
                <x-seller-info :seller="$service->user" :listing="$service" />
            @else
                <div class="border-t border-slate-200 dark:border-slate-600 p-2 md:p-6 bg-slate-50 dark:bg-slate-700">
                    <p class="text-slate-600 dark:text-slate-400 text-center">Ovo je vaša usluga</p>
                </div>
            @endif
        @endauth
    </div>

    <!-- Preporučene usluge -->
    @if ($recommendedListings && $recommendedListings->count() > 0)
        <div class="mt-12">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                @if ($recommendationType === 'seller')
                    Ostale usluge ovog korisnika
                @else
                    Slične usluge
                @endif
            </h2>
            <p class="text-slate-600 dark:text-slate-400 mb-8">
                @if ($recommendationType === 'seller')
                    Pogledajte i druge usluge ovog korisnika
                @else
                    Pronađite slične usluge iz iste kategorije
                @endif
            </p>

            <!-- Lista usluga -->
            <div class="space-y-4">
                @foreach ($recommendedListings as $relatedService)
                    <div
                        class="listing-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 border-slate-500">
                        <div class="flex flex-col md:flex-row">
                            <!-- Slika -->
                            <div class="w-full md:w-48 md:min-w-48 h-48">
                                <a href="{{ route('services.show', $relatedService->slug) }}">
                                    @if ($relatedService->images->count() > 0)
                                        <img src="{{ $relatedService->images->first()->url }}"
                                            alt="{{ $relatedService->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-tools text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <!-- Informacije -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-2">
                                            <a href="{{ route('services.show', $relatedService->slug) }}"
                                                class="flex-1">
                                                <h3
                                                    class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-sky-600 transition-colors">
                                                    {{ $relatedService->title }}
                                                </h3>
                                            </a>
                                        </div>

                                        <div class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>{{ $relatedService->location }}</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-tools mr-1"></i>
                                            <span>{{ $relatedService->category->name ?? '' }}</span>
                                        </div>

                                        <p class="text-slate-700 dark:text-slate-200 mb-3"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($relatedService->description), 120) }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="text-sky-600 dark:text-sky-400 font-bold text-xl">
                                            @if ($relatedService->price_type === 'fixed')
                                                {{ number_format($relatedService->price, 0, ',', '.') }} RSD
                                            @elseif($relatedService->price_type === 'hourly')
                                                {{ number_format($relatedService->price, 0, ',', '.') }} RSD/sat
                                            @elseif($relatedService->price_type === 'daily')
                                                {{ number_format($relatedService->price, 0, ',', '.') }} RSD/dan
                                            @elseif($relatedService->price_type === 'per_m2')
                                                {{ number_format($relatedService->price, 0, ',', '.') }} RSD/m²
                                            @else
                                                Po dogovoru
                                            @endif
                                        </div>
                                        <span
                                            class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-sm font-medium rounded-full">
                                            USLUGA
                                        </span>
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
        document.querySelectorAll('.border-sky-500').forEach(item => {
            item.classList.remove('border-sky-500');
            item.classList.add('border-slate-200');
        });

        // Dodaj border na selektovanu sliku
        if (element && element.parentElement) {
            element.parentElement.classList.remove('border-slate-200');
            element.parentElement.classList.add('border-sky-500');
        }
    }

    function shareService() {
        const url = window.location.href;
        const title = {{ Js::from($service->title) }};
        const text = 'Pogledaj ovu uslugu: ' + title;

        // Check if mobile and has native share
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        if (navigator.share && isMobile) {
            // Use native share on mobile
            navigator.share({
                title: title,
                text: text,
                url: url
            }).catch(err => {
                // If share fails, show popup
                showSharePopup(url, title, text);
            });
        } else {
            // Desktop - show popup
            showSharePopup(url, title, text);
        }
    }

    function showSharePopup(url, title, text) {
        // Remove existing popup if any
        const existing = document.getElementById('sharePopup');
        if (existing) existing.remove();

        // Create popup container
        const overlay = document.createElement('div');
        overlay.id = 'sharePopup';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

        // Create popup content
        const popup = document.createElement('div');
        popup.className = 'bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4';

        // Header
        const header = document.createElement('div');
        header.className = 'flex justify-between items-center mb-4';

        const heading = document.createElement('h3');
        heading.className = 'text-lg font-semibold text-slate-800 dark:text-slate-200';
        heading.textContent = 'Podeli uslugu';

        const closeBtn = document.createElement('button');
        closeBtn.className = 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200';
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.onclick = () => overlay.remove();

        header.appendChild(heading);
        header.appendChild(closeBtn);

        // Share options container
        const options = document.createElement('div');
        options.className = 'space-y-3';

        // Facebook
        const fbLink = document.createElement('a');
        fbLink.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
        fbLink.target = '_blank';
        fbLink.className = 'flex items-center p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors';
        fbLink.innerHTML = '<i class="fab fa-facebook-f w-6"></i><span class="ml-3">Podeli na Facebook-u</span>';

        // WhatsApp
        const waLink = document.createElement('a');
        waLink.href = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
        waLink.target = '_blank';
        waLink.className = 'flex items-center p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors';
        waLink.innerHTML = '<i class="fab fa-whatsapp w-6"></i><span class="ml-3">Podeli na WhatsApp-u</span>';

        // Email
        const emailLink = document.createElement('a');
        emailLink.href = 'mailto:?subject=' + encodeURIComponent(title) + '&body=' + encodeURIComponent(text + '\n\n' + url);
        emailLink.className = 'flex items-center p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors';
        emailLink.innerHTML = '<i class="fas fa-envelope w-6"></i><span class="ml-3">Pošalji Email</span>';

        // Copy link button
        const copyBtn = document.createElement('button');
        copyBtn.className = 'w-full flex items-center p-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors';
        copyBtn.innerHTML = '<i class="fas fa-link w-6"></i><span class="ml-3">Kopiraj link</span>';
        copyBtn.onclick = () => {
            copyToClipboard(url);
            overlay.remove();
        };

        // Add all options
        options.appendChild(fbLink);
        options.appendChild(waLink);
        options.appendChild(emailLink);
        options.appendChild(copyBtn);

        // Assemble popup
        popup.appendChild(header);
        popup.appendChild(options);
        overlay.appendChild(popup);

        // Close on overlay click
        overlay.onclick = (e) => {
            if (e.target === overlay) overlay.remove();
        };

        document.body.appendChild(overlay);
    }

    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(() => {
            // Show success message
            const message = document.createElement('div');
            message.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';

            const icon = document.createElement('i');
            icon.className = 'fas fa-check mr-2';

            const text = document.createElement('span');
            text.textContent = 'Link je kopiran u clipboard!';

            message.appendChild(icon);
            message.appendChild(text);
            document.body.appendChild(message);

            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.3s';
                setTimeout(() => message.remove(), 300);
            }, 2000);
        }).catch(() => {
            // Fallback for older browsers
            prompt('Kopiraj link:', url);
        });
    }

    // Move service favorite button between desktop and mobile based on viewport
    function manageServiceFavoriteButton() {
        const desktopContainer = document.getElementById('service-favorite-button-desktop');
        const mobileContainer = document.getElementById('service-favorite-button-mobile');
        if (!desktopContainer || !mobileContainer) return;

        const button = desktopContainer.querySelector('[wire\\:id]') || mobileContainer.querySelector('[wire\\:id]');
        if (!button) return;

        const isMobile = window.innerWidth < 768;

        if (isMobile && button.parentElement === desktopContainer) {
            mobileContainer.appendChild(button);
        } else if (!isMobile && button.parentElement === mobileContainer) {
            desktopContainer.appendChild(button);
        }
    }

    // Initialize
    window.addEventListener('DOMContentLoaded', manageServiceFavoriteButton);
    window.addEventListener('resize', manageServiceFavoriteButton);

</script>
