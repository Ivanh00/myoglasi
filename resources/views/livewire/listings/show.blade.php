<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    @if (!$listing)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 text-center">
            <i class="fas fa-exclamation-triangle text-amber-500 text-5xl mb-4"></i>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-200 mb-2">Sadržaj nije pronađen</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-4">Traženi sadržaj ne postoji ili je obrisan.</p>
            <a href="{{ url('/') }}"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                Nazad na početnu
            </a>
        </div>
    @else
        <!-- Navigacija - breadcrumbs -->
        <nav class="mb-6 flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('home') }}"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-400">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="flex items-center">
                    <span class="mx-2 text-slate-500 dark:text-slate-300">/</span>
                    <a href="{{ route('category.show', $listing->category->slug) }}"
                        class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                        {{ $listing->category->name }}
                    </a>
                </li>
                @if ($listing->subcategory)
                    <li class="flex items-center">
                        <span class="mx-2 text-slate-500 dark:text-slate-300">/</span>
                        <a href="{{ route('category.show', ['category' => $listing->category->slug, 'subcategory' => $listing->subcategory->slug]) }}"
                            class="text-slate-500 dark:text-slate-300 hover:text-slate-700 dark:hover:text-slate-200">
                            {{ $listing->subcategory->name }}
                        </a>
                    </li>
                @endif
                <li class="flex items-center">
                    <span class="mx-2 text-slate-500 dark:text-slate-300">/</span>
                    <span
                        class="text-slate-700 dark:text-slate-200 font-medium truncate">{{ Str::limit($listing->title, 30) }}</span>
                </li>
            </ol>
        </nav>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Glavni deo - slika i osnovne informacije -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- Slike oglasa -->
                <div>
                    @if ($listing->images->count() > 0)
                        <div class="relative">
                            <!-- Glavna slika -->
                            <div class="mb-4 rounded-lg overflow-hidden relative">
                                <img id="mainImage" src="{{ $listing->images->first()->url }}"
                                    alt="{{ $listing->title }}" class="w-full h-80 object-cover rounded-lg">

                                <!-- Auction Overlay Button -->
                                @auth
                                    @if (auth()->id() !== $listing->user_id && $listing->hasActiveAuction())
                                        <div class="absolute top-4 left-4">
                                            <a href="{{ route('auction.show', $listing->auction) }}"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 bg-opacity-90 text-white font-semibold rounded-lg hover:bg-red-700 hover:bg-opacity-100 transition-all shadow-lg">
                                                <i class="fas fa-gavel mr-2"></i>
                                                Kupi na aukciji
                                            </a>
                                        </div>
                                    @endif
                                @endauth

                                <!-- Auction Status Badge -->
                                @if ($listing->hasActiveAuction())
                                    <div class="absolute top-4 right-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-amber-500 bg-opacity-90 text-white text-sm font-medium rounded-lg">
                                            <i class="fas fa-clock mr-1"></i>
                                            @php
                                                $timeLeft = $listing->auction->time_left;
                                            @endphp
                                            @if ($timeLeft)
                                                @if ($timeLeft['days'] > 0)
                                                    {{ $timeLeft['days'] }}d {{ $timeLeft['hours'] }}h
                                                @else
                                                    {{ $timeLeft['hours'] }}:{{ sprintf('%02d', $timeLeft['minutes']) }}
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Mala galerija -->
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($listing->images as $index => $image)
                                    <div
                                        class="cursor-pointer border-2 rounded-lg overflow-hidden 
                                {{ $index === 0 ? 'border-sky-500' : 'border-slate-200' }}">
                                        <img src="{{ $image->url }}"
                                            alt="{{ $listing->title }} - slika {{ $index + 1 }}"
                                            class="w-full h-20 object-cover"
                                            onclick="changeMainImage('{{ $image->url }}', this)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="w-full h-80 bg-slate-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-slate-400 text-5xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Informacije o oglasu -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $listing->title }}</h1>

                        @auth
                            @if (auth()->id() !== $listing->user_id)
                                @php
                                    $userReport = \App\Models\ListingReport::where('user_id', auth()->id())
                                        ->where('listing_id', $listing->id)
                                        ->first();
                                @endphp
                                @if ($userReport)
                                    <!-- Already reported status -->
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 {{ $userReport->status_badge }} rounded-lg text-sm">
                                        <i class="fas fa-flag mr-1"></i>
                                        Prijavljen ({{ $userReport->status_text }})
                                    </span>
                                @else
                                    <!-- Report button -->
                                    <a href="{{ route('listing.report', ['slug' => $listing->slug]) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm">
                                        <i class="fas fa-flag mr-1"></i>
                                        Prijavi
                                    </a>
                                @endif
                            @endif
                        @endauth
                    </div>

                    <div class="flex items-center mb-4">
                        @if ($listing->isGiveaway())
                            <span class="text-3xl font-bold text-green-600">BESPLATNO</span>
                            <span class="ml-4 px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                POKLON
                            </span>
                        @else
                            <span
                                class="text-3xl font-bold text-sky-600 dark:text-sky-400">{{ number_format($listing->price, 2) }}
                                RSD</span>
                        @endif

                        @if ($listing->isService())
                            <span class="ml-4 px-3 py-1 bg-slate-100 text-slate-800 text-sm font-medium rounded-full">
                                USLUGA
                            </span>
                        @endif

                        @if ($listing->condition)
                            <span class="ml-4 px-3 py-1 bg-slate-100 text-slate-800 text-sm font-medium rounded-full">
                                {{ $listing->condition->name }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        @auth
                            <div class="flex items-center mb-1">
                                <i class="fas fa-user text-slate-500 dark:text-slate-300 mr-2"></i>
                                <span class="text-slate-700 dark:text-slate-200 font-bold">
                                    @if ($listing->isService())
                                        Pružalac usluge: {{ $listing->user->name }}
                                    @elseif($listing->isGiveaway())
                                        Poklanja: {{ $listing->user->name }}
                                    @else
                                        Prodavac: {{ $listing->user->name }}
                                    @endif
                                </span>
                                @if ($listing->user)
                                    {!! $listing->user->verified_icon !!}
                                @endif
                                @if ($listing->user->is_banned)
                                    <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                @endif
                                @if ($listing->user->shouldShowLastSeen())
                                    <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                                        @if ($listing->user->is_online)
                                            <span class="inline-flex items-center">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                {{ $listing->user->last_seen }}
                                            </span>
                                        @else
                                            {{ $listing->user->last_seen }}
                                        @endif
                                    </span>
                                @endif
                            </div>

                            {{-- User ratings --}}
                            @if ($listing->user->total_ratings_count > 0)
                                <a href="{{ route('user.ratings', $listing->user->id) }}"
                                    class="inline-flex items-center text-xs text-slate-600 mb-2 hover:text-sky-600 transition-colors">
                                    <span class="text-green-600 dark:text-green-400 mr-1">😊
                                        {{ $listing->user->positive_ratings_count }}</span>
                                    <span class="text-amber-600 dark:text-amber-400 mr-1">😐
                                        {{ $listing->user->neutral_ratings_count }}</span>
                                    <span class="text-red-600 dark:text-red-400 mr-1">😞
                                        {{ $listing->user->negative_ratings_count }}</span>
                                    @if ($listing->user->rating_badge)
                                        <span class="ml-1">{{ $listing->user->rating_badge }}</span>
                                    @endif
                                    <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            @endif
                        @endauth
                        <div class="flex items-center mb-2">
                            <i class="fas fa-map-marker-alt text-slate-500 dark:text-slate-300 mr-2"></i>
                            <span class="text-slate-700 dark:text-slate-200">{{ $listing->location }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-clock text-slate-500 dark:text-slate-300 mr-2"></i>
                            <span class="text-slate-700 dark:text-slate-200">Objavljeno:
                                {{ $listing->created_at->format('d.m.Y. H:i') }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-eye text-slate-500 dark:text-slate-300 mr-2"></i>
                            <span class="text-slate-700 dark:text-slate-200">Pregleda:
                                {{ $listing->views ?? 0 }}</span>
                        </div>
                        <!-- Dodajte ovaj div za prikaz broja pratilaca -->
                        <div class="flex items-center">
                            <i class="fas fa-heart text-slate-500 dark:text-slate-300 mr-2"></i>
                            <span class="text-slate-700 dark:text-slate-200">Pratilaca:
                                {{ $listing->favorites_count ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- Prikaz telefona samo ako je vlasnik dozvolio, korisnik ulogovan i prodavac NIJE blokiran --}}
                    @if ($listing->contact_phone && $listing->user->phone_visible && auth()->check() && !$listing->user->is_banned)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Kontakt telefon
                            </h3>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-green-500 mr-2"></i>
                                <a href="tel:{{ $listing->contact_phone }}" class="text-xl font-medium text-green-600">
                                    {{ $listing->contact_phone }}
                                </a>
                            </div>
                        </div>
                    @elseif ($listing->user->is_banned && auth()->check())
                        <div
                            class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                <span class="text-red-700 dark:text-red-300 font-medium">Kontakt informacije nisu
                                    dostupne - prodavac je blokiran</span>
                            </div>
                        </div>
                    @endif

                    <!-- Desktop Actions -->
                    <div class="hidden md:flex space-x-4 mt-8">
                        @auth
                            @if (auth()->id() !== $listing->user_id)
                                @if (!$listing->user->is_banned)
                                    <!-- Dugme za slanje poruke -->
                                    <a href="{{ route('listing.chat', ['slug' => $listing->slug]) }}"
                                        class="flex-1 flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-envelope mr-2"></i> Pošalji poruku
                                    </a>

                                    <!-- Favorite dugme (Livewire komponenta) -->
                                    <div class="flex-1" id="favorite-button-desktop">
                                        <livewire:favorite-button :listing="$listing" />
                                    </div>

                                    <!-- Dugme za deljenje -->
                                    <button onclick="shareListing()"
                                        class="flex-1 flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-share-alt mr-2"></i> Podeli
                                    </button>
                                @else
                                    <!-- Poruka za banovane prodavce - bez dugmadi -->
                                    <div
                                        class="w-full p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-ban text-red-500 mr-2"></i>
                                            <span class="text-red-700 dark:text-red-300 text-sm">Kontakt sa ovim prodavcem
                                                nije moguć jer je blokiran.</span>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <!-- Dugme za vlasnike oglasa -->
                                <div
                                    class="flex items-center justify-center px-4 py-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 rounded-lg">
                                    <i class="fas fa-user mr-2"></i> Vaš oglas
                                </div>

                                <!-- Dugme za uređivanje svog oglasa -->
                                @if (!$listing->auction || ($listing->auction && $listing->auction->total_bids == 0))
                                    <a href="{{ route('listings.edit', $listing) }}"
                                        class="flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                        <i class="fas fa-edit mr-2"></i> Uredi oglas
                                    </a>
                                @elseif($listing->auction)
                                    <a href="{{ route('auction.show', $listing->auction) }}"
                                        class="flex items-center justify-center px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                        <i class="fas fa-gavel mr-2"></i> Pogledaj aukciju
                                    </a>
                                @endif

                                <!-- Dugme za aukciju (only for regular listings) -->
                                @if ($listing->isRegularListing())
                                    @if (!$listing->auction)
                                        <a href="{{ route('auction.setup', $listing) }}"
                                            class="flex items-center justify-center px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                            <i class="fas fa-gavel mr-2"></i> Prodaj na aukciji
                                        </a>
                                    @else
                                        <span
                                            class="flex items-center justify-center px-4 py-3 bg-slate-400 text-white rounded-lg">
                                            <i class="fas fa-gavel mr-2"></i> Na aukciji
                                        </span>
                                    @endif
                                @endif
                            @endif
                        @else
                            <!-- Dugmad za neautentifikovane korisnike -->
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <i class="fas fa-envelope mr-2"></i> Prijavite se za slanje poruke
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Actions -->
                    <div class="md:hidden space-y-3 mt-8">
                        @auth
                            @if (auth()->id() !== $listing->user_id)
                                @if (!$listing->user->is_banned)
                                    <!-- Dugme za slanje poruke -->
                                    <a href="{{ route('listing.chat', ['slug' => $listing->slug]) }}"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                        <i class="fas fa-envelope mr-2"></i> Pošalji poruku
                                    </a>

                                    <!-- Favorite dugme (shared component) -->
                                    <div class="w-full" id="favorite-button-mobile"></div>

                                    <!-- Dugme za deljenje -->
                                    <button onclick="shareListing()"
                                        class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-share-alt mr-2"></i> Podeli oglas
                                    </button>
                                @else
                                    <!-- Poruka za banovane prodavce -->
                                    <div
                                        class="w-full p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-ban text-red-500 mr-2"></i>
                                            <span class="text-red-700 dark:text-red-300 text-sm">Kontakt sa ovim prodavcem
                                                nije moguć jer je blokiran.</span>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <!-- Dugme za vlasnike oglasa -->
                                <div
                                    class="w-full p-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 rounded-lg text-center">
                                    <i class="fas fa-user mr-2"></i> Vaš oglas
                                </div>

                                <!-- Dugme za uređivanje svog oglasa -->
                                @if (!$listing->auction || ($listing->auction && $listing->auction->total_bids == 0))
                                    <a href="{{ route('listings.edit', $listing) }}"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                        <i class="fas fa-edit mr-2"></i> Uredi oglas
                                    </a>
                                @elseif($listing->auction)
                                    <a href="{{ route('auction.show', $listing->auction) }}"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                        <i class="fas fa-gavel mr-2"></i> Pogledaj aukciju
                                    </a>
                                @endif

                                <!-- Dugme za aukciju (mobile, only for regular listings) -->
                                @if ($listing->isRegularListing())
                                    @if (!$listing->auction)
                                        <a href="{{ route('auction.setup', $listing) }}"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                            <i class="fas fa-gavel mr-2"></i> Prodaj na aukciji
                                        </a>
                                    @else
                                        <span
                                            class="w-full flex items-center justify-center px-4 py-3 bg-slate-400 text-white rounded-lg">
                                            <i class="fas fa-gavel mr-2"></i> Na aukciji
                                        </span>
                                    @endif
                                @endif
                            @endif
                        @else
                            <!-- Dugme za neautentifikovane korisnike -->
                            <a href="{{ route('login') }}"
                                class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <i class="fas fa-envelope mr-2"></i> Prijavite se za slanje poruke
                            </a>
                        @endauth
                    </div>

                </div>
            </div>

            <!-- Opis oglasa -->
            <div class="border-t border-slate-200 dark:border-slate-600 p-6">
                <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-4">
                    @if ($listing->isService())
                        Opis usluge
                    @elseif($listing->isGiveaway())
                        Opis poklona
                    @else
                        Opis oglasa
                    @endif
                </h2>
                <div class="text-slate-700 dark:text-slate-200 whitespace-pre-line">{{ $listing->description }}</div>
            </div>

            {{-- Uslovi prodaje – prikaz ako postoje --}}
            <div class="border-t border-slate-200 dark:border-slate-600 p-6">
                @if ($listing->user->seller_terms)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Uslovi prodaje</h3>
                        <div class="bg-slate-100 dark:bg-slate-700 p-4 rounded-lg text-slate-700 dark:text-slate-300">
                            {!! nl2br(e($listing->user->seller_terms)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Informacije o prodavcu (only for authenticated users) -->
            @auth
                <div class="border-t border-slate-200 dark:border-slate-600 p-6 bg-slate-50 dark:bg-slate-700">
                    <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-4">
                        @if ($listing->isService())
                            Informacije o pružaocu usluge
                        @elseif($listing->isGiveaway())
                            Informacije o davaocu
                        @else
                            Informacije o prodavcu
                        @endif
                    </h2>
                    <div class="flex items-start">
                        <!-- Avatar -->
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            @if ($listing->user->avatar)
                                <img src="{{ $listing->user->avatar_url }}" alt="{{ $listing->user->name }}"
                                    class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div
                                    class="w-16 h-16 bg-sky-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-medium text-slate-900 dark:text-slate-100 text-lg">
                                {{ $listing->user->name }}
                                {!! $listing->user->verified_icon !!}
                                @if ($listing->user->is_banned)
                                    <span class="text-red-600 dark:text-red-400 font-bold ml-2">BLOKIRAN</span>
                                @endif
                                @if ($listing->user->shouldShowLastSeen())
                                    <div class="text-sm text-slate-500 dark:text-slate-300 mt-1">
                                        @if ($listing->user->is_online)
                                            <span class="inline-flex items-center">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                                {{ $listing->user->last_seen }}
                                            </span>
                                        @else
                                            {{ $listing->user->last_seen }}
                                        @endif
                                    </div>
                                @endif
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Član od:
                                {{ $listing->user->created_at->format('m/Y') }}</p>

                            {{-- User ratings --}}
                            @if ($listing->user->total_ratings_count > 0)
                                <a href="{{ route('user.ratings', $listing->user->id) }}"
                                    class="inline-flex items-center text-sm text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                                    <span class="text-green-600 dark:text-green-400 mr-2">😊
                                        {{ $listing->user->positive_ratings_count }}</span>
                                    <span class="text-amber-600 dark:text-amber-400 mr-2">😐
                                        {{ $listing->user->neutral_ratings_count }}</span>
                                    <span class="text-red-600 dark:text-red-400 mr-2">😞
                                        {{ $listing->user->negative_ratings_count }}</span>
                                    @if ($listing->user->rating_badge)
                                        <span class="ml-1 mr-2">{{ $listing->user->rating_badge }}</span>
                                    @endif
                                    <span
                                        class="text-sky-500 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300">Pogledaj
                                        ocene</span>
                                    <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            @else
                                <p class="text-slate-500 dark:text-slate-300 text-sm">Još nema ocena</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Preporučeni oglasi -->
        @if ($recommendedListings && $recommendedListings->count() > 0)
            <div class="mt-12">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    @if ($recommendationType === 'seller')
                        Ostali oglasi ovog prodavca
                    @else
                        Slični oglasi
                    @endif
                </h2>
                <p class="text-slate-600 dark:text-slate-400 mb-8">
                    @if ($recommendationType === 'seller')
                        Pogledajte i druge oglase ovog prodavca
                    @else
                        Pronađite slične oglase iz iste kategorije
                    @endif
                </p>

                <!-- Lista oglasa (koristi isti layout kao Index stranica) -->
                <div class="space-y-4">
                    @foreach ($recommendedListings as $relatedListing)
                        <div
                            class="listing-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300
                            @if ($relatedListing->isGiveaway()) border-l-4 border-green-500
                            @elseif($relatedListing->isService())
                                border-l-4 border-slate-500
                            @else
                                border-l-4 border-sky-500 @endif">
                            <div class="flex flex-col md:flex-row">
                                <!-- Slika oglasa - responsive -->
                                <div class="w-full md:w-48 md:min-w-48 h-48">
                                    <a href="{{ route('listings.show', $relatedListing) }}">
                                        @if ($relatedListing->images->count() > 0)
                                            <img src="{{ $relatedListing->images->first()->url }}"
                                                alt="{{ $relatedListing->title }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                                <i class="fas fa-image text-slate-400 text-3xl"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <!-- Informacije o oglasu -->
                                <div class="flex-1 p-4 md:p-6">
                                    <div class="flex flex-col h-full">
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <a href="{{ route('listings.show', $relatedListing) }}"
                                                    class="flex-1">
                                                    <h3
                                                        class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-sky-600 transition-colors">
                                                        {{ $relatedListing->title }}
                                                    </h3>
                                                </a>

                                                <!-- Promotion Badges -->
                                                @if ($relatedListing->hasActivePromotion())
                                                    <div class="flex flex-wrap gap-1 ml-2">
                                                        @foreach ($relatedListing->getPromotionBadges() as $badge)
                                                            <span
                                                                class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">
                                                                {{ $badge['text'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <div
                                                class="flex items-center text-sm text-slate-600 dark:text-slate-300 mb-2">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span>{{ $relatedListing->location }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-folder mr-1"></i>
                                                <span>{{ $relatedListing->category->name }}</span>
                                            </div>

                                            <p class="text-slate-700 dark:text-slate-200 mb-3"
                                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ Str::limit(strip_tags($relatedListing->description), 120) }}
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            @if ($relatedListing->isGiveaway())
                                                <div class="text-green-600 font-bold text-xl">BESPLATNO</div>
                                            @else
                                                <div class="text-sky-600 font-bold text-xl">
                                                    {{ number_format($relatedListing->price, 2) }} RSD
                                                </div>
                                            @endif

                                            <div class="flex items-center gap-2">
                                                @if ($relatedListing->getTypeBadge())
                                                    <span
                                                        class="px-2 py-1 text-xs font-bold rounded-full {{ $relatedListing->getTypeBadge()['class'] }}">
                                                        {{ $relatedListing->getTypeBadge()['text'] }}
                                                    </span>
                                                @endif

                                                @if ($relatedListing->condition)
                                                    <span
                                                        class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-medium rounded-full">
                                                        {{ $relatedListing->condition->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desna strana - akcije i dodatne informacije -->
                                <div
                                    class="md:w-48 md:min-w-48 p-4 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-600
                                    @if ($relatedListing->isGiveaway()) bg-green-50 dark:bg-green-900
                                    @elseif($relatedListing->isService())
                                        bg-slate-50 dark:bg-slate-700
                                    @else
                                        bg-sky-50 dark:bg-slate-600 @endif">
                                    <div class="flex flex-col h-full justify-between">
                                        <div
                                            class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300 mb-4">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-slate-700 dark:text-slate-200"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 4.5C7.5 4.5 3.5 8.5 2 12c1.5 3.5 5.5 7.5 10 7.5s8.5-4 10-7.5c-1.5-3.5-5.5-7.5-10-7.5zm0 12c-2.5 0-4.5-2-4.5-4.5S9.5 8.5 12 8.5 16.5 10.5 16.5 12 14.5 16.5 12 16.5zm0-7c-1.5 0-2.5 1-2.5 2.5S10.5 14.5 12 14.5 14.5 13.5 14.5 12 13.5 9.5 12 9.5z" />
                                                </svg>
                                                <span
                                                    class="text-slate-700 dark:text-slate-200">{{ $relatedListing->views ?? 0 }}</span>
                                            </div>
                                            <!-- Favorites count -->
                                            <div class="flex items-center">
                                                <span class="text-slate-700 dark:text-slate-200">❤️
                                                    {{ $relatedListing->favorites_count ?? 0 }}</span>
                                            </div>
                                        </div>

                                        <div class="text-xs text-slate-700 dark:text-slate-200 mb-4">
                                            <i class="fas fa-clock mr-1"></i>
                                            Postavljeno pre {{ floor($relatedListing->created_at->diffInDays()) }} dana
                                        </div>

                                        <div class="space-y-2">
                                            <a href="{{ route('listings.show', $relatedListing) }}"
                                                class="block w-full text-center px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
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
                <h2 class="text-3xl font-bold text-slate-900 mb-4">
                    @if ($recommendationType === 'seller')
                        Ostali oglasi ovog prodavca
                    @else
                        Slični oglasi
                    @endif
                </h2>
                <p class="text-slate-600 mb-8">
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
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <!-- Informacije o oglasu -->
                            <div class="p-4">
                                <a href="{{ route('listings.show', $listing) }}">
                                    <h3
                                        class="text-lg font-semibold text-slate-900 mb-2 hover:text-sky-600 transition-colors">
                                        {{ Str::limit($listing->title, 60) }}
                                    </h3>
                                </a>

                                <div class="flex items-center text-sm text-slate-600 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $listing->location }}</span>
                                </div>

                                <p class="text-slate-700 mb-3 text-sm"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($listing->description), 100) }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="text-sky-600 font-bold text-xl">
                                        {{ number_format($listing->price, 2) }} RSD
                                    </div>

                                    @if ($listing->condition)
                                        <span
                                            class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-medium rounded-full">
                                            {{ $listing->condition->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mt-4 text-xs text-slate-500">
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
                                    class="block w-full text-center mt-4 px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
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
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Slični oglasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($similarListings as $similar)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('listings.show', $similar) }}">
                                @if ($similar->images->count() > 0)
                                    <img src="{{ $similar->images->first()->url }}" alt="{{ $similar->title }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('listings.show', $similar) }}">
                                    <h3
                                        class="font-semibold text-lg text-slate-900 mb-2 hover:text-sky-600 transition-colors">
                                        {{ Str::limit($similar->title, 40) }}
                                    </h3>
                                </a>
                                <p class="text-sky-600 font-bold text-xl mb-2">
                                    {{ number_format($similar->price, 2) }} RSD</p>
                                <p class="text-slate-600 text-sm">{{ $similar->location }}</p>
                                <p class="text-slate-400 text-xs mt-2">{{ $similar->created_at->diffForHumans() }}</p>
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
        document.querySelectorAll('.border-sky-500').forEach(item => {
            item.classList.remove('border-sky-500');
            item.classList.add('border-slate-200');
        });

        // Dodaj border na selektovanu sliku
        element.parentElement.classList.remove('border-slate-200');
        element.parentElement.classList.add('border-sky-500');
    }

    function shareListing() {
        const url = window.location.href;
        const title = "{{ $listing->title }}";

        if (navigator.share) {
            // Koristi Web Share API ako je dostupan (mobilni browsers)
            navigator.share({
                title: title,
                text: 'Pogledaj ovaj oglas',
                url: url
            });
        } else {
            // Fallback - kopiraj u clipboard
            navigator.clipboard.writeText(url).then(function() {
                alert('Link je kopiran u clipboard!');
            }, function() {
                // Ako clipboard ne radi, prikaži URL
                prompt('Kopiraj ovaj link:', url);
            });
        }
    }

    // Move favorite button between desktop and mobile based on viewport
    function manageFavoriteButton() {
        const desktopContainer = document.getElementById('favorite-button-desktop');
        const mobileContainer = document.getElementById('favorite-button-mobile');

        if (!desktopContainer || !mobileContainer) return;

        // Find component in either container
        let favoriteComponent = desktopContainer.querySelector('[wire\\:id]') ||
            mobileContainer.querySelector('[wire\\:id]');

        if (!favoriteComponent) return;

        if (window.innerWidth < 768) {
            // Mobile: ensure component is in mobile container
            if (favoriteComponent.parentNode !== mobileContainer) {
                mobileContainer.appendChild(favoriteComponent);
            }
        } else {
            // Desktop: ensure component is in desktop container
            if (favoriteComponent.parentNode !== desktopContainer) {
                desktopContainer.appendChild(favoriteComponent);
            }
        }
    }

    // Debounced resize handler to prevent excessive calls
    let resizeTimeout;

    function handleResize() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(manageFavoriteButton, 100);
    }

    // Run on load and resize
    document.addEventListener('DOMContentLoaded', function() {
        manageFavoriteButton();
        // Also run after a short delay to ensure Livewire components are fully loaded
        setTimeout(manageFavoriteButton, 500);
    });
    window.addEventListener('resize', handleResize);
</script>
