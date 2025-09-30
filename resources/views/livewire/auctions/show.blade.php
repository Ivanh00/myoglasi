<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Single column layout -->
    <div>
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden mb-6">
            <!-- Auction Header -->
            <div
                class="bg-gradient-to-r from-orange-600 via-amber-400 to-orange-600 dark:from-orange-800 dark:via-amber-600 dark:to-orange-800 text-white p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-xl font-bold">{{ $auction->listing->title }}</h1>
                                <p class="text-amber-100">Aukcija #{{ $auction->id }}</p>
                            </div>
                        </div>
                        <!-- Category display -->
                        <div class="flex items-center text-amber-100 text-sm mt-1">
                            <i class="fas fa-folder mr-1"></i>
                            <span>{{ $auction->listing->category->name }}</span>
                            @if ($auction->listing->subcategory)
                                <span class="mx-1">></span>
                                <span>{{ $auction->listing->subcategory->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        @if ($auction->isActive())
                            <div class="text-2xl font-bold auction-countdown"
                                data-end-time="{{ $auction->ends_at->timestamp }}"
                                data-total-seconds="{{ $auction->time_left['total_seconds'] ?? 0 }}">
                                @if ($auction->time_left)
                                    {{ $auction->time_left['formatted'] }}
                                @endif
                            </div>
                            <p class="text-amber-100 text-sm">vremena ostalo</p>
                        @elseif($auction->status === 'active' && $auction->starts_at->isFuture())
                            <div class="text-2xl font-bold">ZAKAZANO</div>
                            <p class="text-amber-100 text-sm">Počinje: {{ $auction->starts_at->format('d.m. H:i') }}</p>
                        @else
                            <div class="text-2xl font-bold">ZAVRŠENO</div>
                            @if ($auction->winner)
                                <p class="text-amber-100 text-sm">Pobednik: {{ $auction->winner->name }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-0 md:p-6">
                <!-- Images -->
                <div>
                    @if ($auction->listing->images->count() > 0)
                        <x-image-lightbox :images="$auction->listing->images->map(function ($img) use ($auction) {
                            return ['url' => $img->url, 'alt' => $auction->listing->title];
                        })" :title="$auction->listing->title">
                            <div class="relative">
                                <!-- Main image -->
                                <div class="mb-4 rounded-lg overflow-hidden relative">
                                    <img id="mainImage" src="{{ $auction->listing->images->first()->url }}"
                                        alt="{{ $auction->listing->title }}"
                                        class="w-full h-80 object-cover rounded-lg cursor-pointer hover:opacity-95 transition-opacity"
                                        @click="openLightbox(0)">
                                    <!-- Zoom icon overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 hover:opacity-100 transition-opacity bg-black bg-opacity-20"
                                        @click="openLightbox(0)">
                                        <div class="bg-white bg-opacity-90 rounded-full p-3">
                                            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thumbnail gallery -->
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach ($auction->listing->images as $index => $image)
                                        <div
                                            class="cursor-pointer border-2 rounded-lg overflow-hidden {{ $index === 0 ? 'border-amber-500' : 'border-slate-200' }}">
                                            <img src="{{ $image->url }}"
                                                alt="{{ $auction->listing->title }} - slika {{ $index + 1 }}"
                                                class="w-full h-20 object-cover"
                                                @click="currentIndex = {{ $index }}; changeMainImage('{{ $image->url }}', $el)">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </x-image-lightbox>
                    @else
                        <div class="w-full h-80 bg-slate-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-gavel text-slate-400 text-5xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Auction Info -->
                <div>
                    <!-- Current Price -->
                    <div class="relative text-center mb-6 p-2 md:p-6 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <!-- Report button - absolute positioned -->
                        @auth
                            @if (auth()->id() !== $auction->user_id)
                                @php
                                    $userReport = \App\Models\ListingReport::where('user_id', auth()->id())
                                        ->where('listing_id', $auction->listing->id)
                                        ->first();
                                @endphp
                                @if ($userReport)
                                    <span
                                        class="absolute top-2 right-2 inline-flex items-center px-2 py-1 {{ $userReport->status_badge }} rounded-lg text-xs">
                                        <i class="fas fa-flag mr-1"></i>
                                        Prijavljen
                                    </span>
                                @else
                                    <a href="{{ route('listing.report', ['slug' => $auction->listing->slug]) }}"
                                        class="absolute top-2 right-2 inline-flex items-center px-2 py-1 bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 rounded-lg hover:bg-red-300 dark:hover:bg-red-700 transition-colors text-xs">
                                        <i class="fas fa-flag mr-1"></i>
                                        Prijavi
                                    </a>
                                @endif
                            @endif
                        @endauth

                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Trenutna cena</h3>
                        <div class="text-4xl font-bold text-amber-700 dark:text-amber-400 mb-2">
                            {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                        </div>
                        @if ($auction->total_bids > 0)
                            <p class="text-slate-600 dark:text-slate-300">{{ $auction->total_bids }} ponuda</p>
                            @if ($auction->winningBid)
                                <p class="text-sm text-slate-500 dark:text-slate-300">
                                    Vodi: {{ $auction->winningBid->user->name }}
                                    @if ($auction->winningBid->user->shouldShowLastSeen())
                                        <span class="text-xs">
                                            @if ($auction->winningBid->user->is_online)
                                                <span class="inline-flex items-center">
                                                    <span
                                                        class="w-1.5 h-1.5 bg-green-500 rounded-full ml-1 mr-1"></span>
                                                    {{ $auction->winningBid->user->last_seen }}
                                                </span>
                                            @else
                                                ({{ $auction->winningBid->user->last_seen }})
                                            @endif
                                        </span>
                                    @endif
                                </p>
                            @endif
                        @else
                            <p class="text-slate-600 dark:text-slate-300">Još nema ponuda</p>
                        @endif
                    </div>

                    <!-- Bidding Form -->
                    @auth
                        @if (auth()->id() === $auction->user_id)
                            <!-- Owner Controls -->
                            <div
                                class="p-2 md:p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                                    <i class="fas fa-crown text-amber-600 dark:text-amber-400 mr-2"></i>
                                    Vaša aukcija
                                </h3>

                                <div class="space-y-3">
                                    @if ($auction->status === 'active' && $auction->starts_at->isFuture())
                                        <!-- Scheduled auction controls -->
                                        <a href="{{ route('listings.edit', $auction->listing) }}"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Uredi aukciju
                                        </a>

                                        <button type="button" x-data @click="$dispatch('open-remove-auction-modal')"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                            <i class="fas fa-times mr-2"></i>
                                            Ukloni iz aukcija
                                        </button>
                                    @elseif($auction->isActive())
                                        <!-- Active auction controls -->
                                        @if ($auction->total_bids == 0)
                                            <a href="{{ route('listings.edit', $auction->listing) }}"
                                                class="w-full flex items-center justify-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                                <i class="fas fa-edit mr-2"></i>
                                                Uredi aukciju
                                            </a>

                                            <button type="button" x-data @click="$dispatch('open-remove-auction-modal')"
                                                class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                <i class="fas fa-times mr-2"></i>
                                                Ukloni iz aukcija
                                            </button>
                                        @else
                                            <div
                                                class="w-full p-3 bg-amber-50 dark:bg-amber-900 border border-amber-200 dark:border-amber-700 rounded-lg">
                                                <div class="flex items-center">
                                                    <i
                                                        class="fas fa-info-circle text-amber-600 dark:text-amber-400 mr-2"></i>
                                                    <span class="text-amber-800 dark:text-amber-200 text-sm">
                                                        Aukcija se ne može uređivati ili otkazati jer već ima ponude.
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            class="w-full p-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-center">
                                            <i class="fas fa-flag-checkered mr-2"></i>
                                            Aukcija je završena
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif(auth()->id() !== $auction->user_id)
                            @if ($auction->isActive())
                                <div
                                    class="p-2 md:p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Postavite
                                        ponudu</h3>

                                    @if (!$isAutoBid)
                                        <div class="mb-4">
                                            <label for="bidAmount"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Vaša ponuda (minimum:
                                                {{ number_format($auction->minimum_bid, 0, ',', '.') }} RSD)
                                            </label>
                                            <input type="number" wire:model="bidAmount" id="bidAmount"
                                                min="{{ $auction->minimum_bid }}" step="{{ $auction->bid_increment }}"
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                            @error('bidAmount')
                                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    <!-- Auto Bid Option -->
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model.live="isAutoBid"
                                                    class="mr-3 h-4 w-4 text-amber-600 focus:ring-amber-500 rounded">
                                                <span class="text-sm text-slate-700 dark:text-slate-200">Automatska
                                                    ponuda</span>
                                            </label>

                                            @php
                                                $userAutoBid = \App\Models\Bid::where('auction_id', $auction->id)
                                                    ->where('user_id', auth()->id())
                                                    ->where('is_auto_bid', true)
                                                    ->whereNotNull('max_bid')
                                                    ->latest()
                                                    ->first();
                                            @endphp
                                            @if ($userAutoBid)
                                                <div class="flex items-center space-x-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                                        <i class="fas fa-robot mr-1"></i>
                                                        Auto-bid aktivan (do
                                                        {{ number_format($userAutoBid->max_bid, 0, ',', '.') }} RSD)
                                                    </span>
                                                    <button wire:click="removeAutoBid"
                                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200"
                                                        onclick="return confirm('Da li želite da uklonite automatsku ponudu?')">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Ukloni
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        @if ($isAutoBid)
                                            <div
                                                class="mt-3 p-3 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg">
                                                <div class="mb-3">
                                                    <label
                                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Maksimalna
                                                        cena</label>
                                                    <input type="number" wire:model="maxBidAmount"
                                                        placeholder="Unesite maksimalnu cenu"
                                                        min="{{ $auction->minimum_bid + $auction->bid_increment }}"
                                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                                                    @error('maxBidAmount')
                                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">
                                                            {{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Set Auto-Bid Button -->
                                                <div class="mb-3">
                                                    <button type="button" wire:click="setAutoBid"
                                                        class="w-full px-3 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                                                        <i class="fas fa-robot mr-2"></i>
                                                        Postavi automatsku ponudu
                                                    </button>
                                                </div>

                                                <div class="text-xs text-sky-800 dark:text-sky-200 space-y-1">
                                                    <div class="flex items-start">
                                                        <i
                                                            class="fas fa-info-circle text-sky-600 dark:text-sky-400 mt-0.5 mr-2 flex-shrink-0"></i>
                                                        <div>
                                                            <strong>Kako funkcioniše automatska ponuda:</strong>
                                                            <ul class="list-disc list-inside mt-1 space-y-1">
                                                                <li>Kliknite "Postavi automatsku ponudu" da aktivirate</li>
                                                                <li>Sistem automatski povećava vašu ponudu kada vas neko
                                                                    nadmaši</li>
                                                                <li>Povećanje za
                                                                    {{ number_format($auction->bid_increment, 0, ',', '.') }}
                                                                    RSD (minimum korak)</li>
                                                                <li>Automatsko licitiranje se zaustavlja kad dostignete
                                                                    maksimalnu cenu</li>
                                                                <li>Dobićete obaveštenje svaki put kada se aktivira</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if (!$isAutoBid)
                                        <div class="space-y-3">
                                            <button type="button" wire:click="placeBid"
                                                class="w-full px-4 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition-colors">
                                                <i class="fas fa-gavel mr-2"></i>
                                                Pošaljite ponudu
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Common Actions (available in both modes) -->
                                    <div class="space-y-3 {{ $isAutoBid ? '' : 'mt-3' }}">
                                        @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
                                            <button type="button" x-data @click="$dispatch('open-buy-now-modal')"
                                                class="w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                                <i class="fas fa-shopping-cart mr-2"></i>
                                                Kupi odmah ({{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD)
                                            </button>
                                        @endif

                                        <!-- Message seller button -->
                                        @if (!$auction->seller->is_banned)
                                            <a href="{{ route('listing.chat', ['slug' => $auction->listing->slug]) }}"
                                                class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                                    </path>
                                                </svg>
                                                Pošaljite poruku prodavcu
                                            </a>

                                            <!-- Share link (after message button) -->
                                            <button onclick="shareAuction()"
                                                class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                                <i class="fas fa-share-alt mr-2"></i>
                                                Podeli link
                                            </button>
                                        @else
                                            <div class="w-full p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center justify-center">
                                                    <i class="fas fa-ban text-red-500 mr-2"></i>
                                                    <span class="text-red-700 dark:text-red-200 text-sm">Prodavac je
                                                        blokiran</span>
                                                </div>
                                            </div>

                                            <!-- Share link (even when seller is banned) -->
                                            <button onclick="shareAuction()"
                                                class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                                <i class="fas fa-share-alt mr-2"></i>
                                                Podeli link
                                            </button>
                                        @endif
                                    </div>
                                </div>
                    </div>
                    @endif
                    @endif
                @endauth

                <!-- Share button for non-authenticated users in active auctions -->
                @guest
                    @if ($auction->isActive())
                        <div
                            class="p-2 md:p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg">
                            <div class="text-center text-slate-600 dark:text-slate-400 text-sm mb-4">
                                <a href="{{ route('login') }}" class="text-sky-500 hover:text-sky-700">Prijavite se</a>
                                da biste učestvovali u aukciji
                            </div>

                            <!-- Share link (available to all) -->
                            <button onclick="shareAuction()"
                                class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <i class="fas fa-share-alt mr-2"></i>
                                Podeli link
                            </button>
                        </div>
                    @endif
                @endguest

                <!-- Scheduled Auction Info and Actions (for non-owners) -->
                @if (!auth()->check() || auth()->id() !== $auction->user_id)
                    @if ($auction->status === 'active' && $auction->starts_at->isFuture())
                        <div
                            class="mb-6 p-6 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2 text-center">
                                <i class="fas fa-clock text-sky-600 dark:text-sky-400 mr-2"></i>
                                Aukcija je zakazana
                            </h3>
                            <p class="text-slate-700 dark:text-slate-200 mb-2 text-center">
                                Aukcija počinje: <strong>{{ $auction->starts_at->format('d.m.Y \\u H:i') }}</strong>
                            </p>
                            <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 text-center">
                                Licitiranje će biti omogućeno kada aukcija počne.
                            </p>

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @auth
                                    <!-- Notify about auction start -->
                                    <button wire:click="toggleNotification"
                                        class="w-full flex items-center justify-center px-4 py-3 {{ $isNotificationSet ? 'border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' : 'bg-amber-600 text-white hover:bg-amber-700' }} rounded-lg transition-colors">
                                        <i class="fas {{ $isNotificationSet ? 'fa-bell-slash' : 'fa-bell' }} mr-2"></i>
                                        {{ $isNotificationSet ? 'Ukloni obaveštenje' : 'Obavesti me o početku aukcije' }}
                                    </button>

                                    <!-- Message seller button -->
                                    @if (!$auction->seller->is_banned)
                                        <a href="{{ route('listing.chat', ['slug' => $auction->listing->slug]) }}"
                                            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                                </path>
                                            </svg>
                                            Pošaljite poruku prodavcu
                                        </a>

                                        <!-- Share link (after message button) -->
                                        <button onclick="shareAuction()"
                                            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                            <i class="fas fa-share-alt mr-2"></i>
                                            Podeli link
                                        </button>
                                    @else
                                        <div class="w-full p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center justify-center">
                                                <i class="fas fa-ban text-red-500 mr-2"></i>
                                                <span class="text-red-700 dark:text-red-200 text-sm">Prodavac je
                                                    blokiran</span>
                                            </div>
                                        </div>

                                        <!-- Share link (even when seller is banned) -->
                                        <button onclick="shareAuction()"
                                            class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                            <i class="fas fa-share-alt mr-2"></i>
                                            Podeli link
                                        </button>
                                    @endif
                                @else
                                    <div class="text-center text-slate-600 dark:text-slate-400 text-sm mb-2">
                                        <a href="{{ route('login') }}" class="text-sky-500 hover:text-sky-700">Prijavite
                                            se</a> da biste koristili ove opcije
                                    </div>

                                    <!-- Share link (available to all non-authenticated) -->
                                    <button onclick="shareAuction()"
                                        class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-share-alt mr-2"></i>
                                        Podeli link
                                    </button>
                                @endauth
                            </div>
                        </div>
                    @endif
                @endif

            </div>

            <!-- Listing Description -->
            <div class="border-t border-slate-200 dark:border-slate-600 p-2 md:p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Opis proizvoda</h3>
                <div class="text-slate-700 dark:text-slate-200">
                    {{ $auction->listing->description }}
                </div>
            </div>

            <!-- Seller Information Section -->
            @auth
                @if (auth()->id() !== $auction->user_id)
                    <x-seller-info :seller="$auction->seller" :listing="$auction->listing" />
                @endif
            @endauth
        </div>
    </div>

    <!-- Bid History Section -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Istorija ponuda</h3>
            <button wire:click="$refresh"
                class="text-sm text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300">
                <i class="fas fa-refresh mr-1"></i> Osvežite
            </button>
        </div>

        @if ($auction->bids->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach ($auction->bids()->orderBy('amount', 'desc')->orderBy('created_at', 'desc')->take(10)->get() as $bid)
                    <div
                        class="flex items-center justify-between p-3 {{ $bid->is_winning ? 'bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700' : 'bg-slate-50 dark:bg-slate-700' }} rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                @if ($bid->user->avatar)
                                    <img src="{{ $bid->user->avatar_url }}" alt="{{ $bid->user->name }}"
                                        class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-8 h-8 bg-slate-500 rounded-full flex items-center justify-center text-white font-medium text-xs">
                                        {{ strtoupper(substr($bid->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-slate-900 dark:text-slate-100">
                                    {{ $bid->user->name }}
                                    @if ($bid->user->shouldShowLastSeen())
                                        <span class="font-normal text-xs text-slate-500 dark:text-slate-300 ml-1">
                                            @if ($bid->user->is_online)
                                                <span class="inline-flex items-center">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                                    {{ $bid->user->last_seen }}
                                                </span>
                                            @else
                                                ({{ $bid->user->last_seen }})
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">
                                    {{ $bid->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div
                                class="font-bold {{ $bid->is_winning ? 'text-green-600' : 'text-slate-900 dark:text-slate-100' }}">
                                {{ number_format($bid->amount, 0, ',', '.') }} RSD
                            </div>
                            @if ($bid->is_auto_bid)
                                <div class="text-xs text-sky-500 dark:text-sky-400">Auto</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-gavel text-slate-400 text-3xl mb-3"></i>
                <p class="text-slate-600 dark:text-slate-300">Još nema ponuda</p>
                <p class="text-slate-500 dark:text-slate-300 text-sm">Budite prvi koji će licitirati!</p>
            </div>
        @endif
    </div>

    <!-- Auction Rules Section -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6 mt-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
            <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mr-2"></i>
            Pravila licitiranja
        </h3>

        <div class="p-4 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg">
            <div class="text-sm text-sky-800 dark:text-sky-200">
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-arrow-up text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Minimalni korak povećanja ponude:
                            <strong>{{ number_format($auction->bid_increment, 0, ',', '.') }} RSD</strong></span>
                    </li>
                    @php
                        $triggerTime = \App\Models\Setting::get('auction_extension_trigger_time', 3);
                        $extensionTime = \App\Models\Setting::get('auction_extension_time', 3);
                        $maxExtensions = \App\Models\Setting::get('auction_max_extensions', 10);
                    @endphp
                    <li class="flex items-start">
                        <i class="fas fa-clock text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Ako se postavi ponuda u poslednje <strong>{{ $triggerTime }} minuta</strong>
                            aukcije</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-plus text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Aukcija se automatski produžava za <strong>{{ $extensionTime }} minuta</strong></span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-repeat text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Ovo se može desiti maksimalno <strong>{{ $maxExtensions }} puta</strong> po
                            aukciji</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-shield-alt text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Sprečava "last second sniping" i omogućava fer nadmetanje</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-lock text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Vlasnik ne može otkazati aukciju ako je trenutna cena veća od početne cene aukcije</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-edit text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                        <span>Vlasnik ne može uređivati aukciju nakon što primi prvu ponudu</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Buy Now Confirmation Modal -->
    @if ($auction->buy_now_price && $auction->current_price < $auction->buy_now_price)
        <div x-data="{ showBuyNowModal: false }" x-show="showBuyNowModal" x-on:open-buy-now-modal.window="showBuyNowModal = true"
            x-on:close-buy-now-modal.window="showBuyNowModal = false"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showBuyNowModal = false">
            </div>

            <!-- Modal content -->
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <!-- Modal header with icon -->
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-white">Potvrda kupovine</h3>
                            </div>
                            <button @click="showBuyNowModal = false" class="text-white hover:text-slate-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="px-6 py-5">
                        <!-- Product info -->
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                {{ $auction->listing->title }}
                            </h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Ovom akcijom završavate aukciju i kupujete proizvod odmah.
                            </p>
                        </div>

                        <!-- Price breakdown -->
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-slate-600 dark:text-slate-300">Cena "Kupi odmah":</span>
                                <span class="font-bold text-lg text-slate-900 dark:text-slate-100">
                                    {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 dark:text-slate-300">Trenutna ponuda:</span>
                                <span class="text-slate-600 dark:text-slate-400">
                                    {{ number_format($auction->current_price, 0, ',', '.') }} RSD
                                </span>
                            </div>
                        </div>

                        <!-- Warning message -->
                        <div
                            class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-3 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-amber-600 dark:text-amber-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-800 dark:text-amber-200">
                                        <strong>Napomena:</strong> Ova akcija se ne može poništiti. Morate kontaktirati
                                        prodavca za dogovor o isporuci i plaćanju.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Seller info -->
                        <div class="border-t border-slate-200 dark:border-slate-600 pt-3">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-user text-slate-400 mr-2"></i>
                                <span class="text-slate-600 dark:text-slate-300">Prodavac:</span>
                                <span
                                    class="ml-2 font-medium text-slate-900 dark:text-slate-100">{{ $auction->seller->name }}</span>
                                {!! $auction->seller->verified_icon !!}
                            </div>
                            <div class="flex items-center text-sm mt-1">
                                <i class="fas fa-map-marker-alt text-slate-400 mr-2"></i>
                                <span class="text-slate-600 dark:text-slate-300">Lokacija:</span>
                                <span
                                    class="ml-2 text-slate-700 dark:text-slate-200">{{ $auction->listing->location }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer with actions -->
                    <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
                        <div class="flex space-x-3">
                            <button type="button" @click="showBuyNowModal = false"
                                class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Otkaži
                            </button>
                            <button type="button" wire:click="confirmBuyNow" @click="showBuyNowModal = false"
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                                <i class="fas fa-check mr-2"></i>
                                Potvrdi kupovinu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Remove from Auction Confirmation Modal -->
    <div x-data="{ showRemoveAuctionModal: false }"
        @if (auth()->check() && auth()->id() === $auction->user_id) x-show="showRemoveAuctionModal"
            x-on:open-remove-auction-modal.window="showRemoveAuctionModal = true"
            x-on:close-remove-auction-modal.window="showRemoveAuctionModal = false"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                @click="showRemoveAuctionModal = false"></div>

            <!-- Modal content -->
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <!-- Modal header with warning icon -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-white">Uklanjanje aukcije</h3>
                            </div>
                            <button @click="showRemoveAuctionModal = false" class="text-white hover:text-slate-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="px-6 py-5">
                        <!-- Warning message -->
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                Da li ste sigurni?
                            </h4>
                            <p class="text-slate-600 dark:text-slate-400">
                                Ova akcija će ukloniti vašu uslugu iz aukcija i vratiti je u obične oglase.
                            </p>
                        </div>

                        <!-- Auction info -->
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Naziv:</span>
                                    <span
                                        class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $auction->listing->title }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Trenutna cena:</span>
                                    <span
                                        class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ number_format($auction->current_price, 0, ',', '.') }}
                                        RSD</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Broj ponuda:</span>
                                    <span
                                        class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $auction->total_bids }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Important notice -->
                        @if ($auction->total_bids > 0 && $auction->current_price > $auction->starting_price)
                            <div
                                class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-amber-600 dark:text-amber-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-800 dark:text-amber-200">
                                            <strong>Napomena:</strong> Ova aukcija već ima ponude veće od početne cene.
                                            Uklanjanje aukcije može razočarati učesnike koji su već licitirali.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($auction->status === 'active' && $auction->starts_at->isFuture())
                            <div
                                class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-700 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-sky-600 dark:text-sky-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-sky-800 dark:text-sky-200">
                                            Ova aukcija je zakazana i još nije počela. Možete je ukloniti bez posledica.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            Aukcija još nema ponude ili je trenutna cena jednaka početnoj. Možete je
                                            bezbedno ukloniti.
                                        </p>
                                    </div>
                                </div>
                            </div> @endif
        </div>

        <!-- Modal footer with actions -->
        <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
            <div class="flex space-x-3">
                <button type="button" @click="showRemoveAuctionModal = false"
                    class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Otkaži
                </button>
                <button type="button" wire:click="removeFromAuction" @click="showRemoveAuctionModal = false"
                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                    <i class="fas fa-trash mr-2"></i>
                    Ukloni aukciju
                </button>
            </div>
        </div>
    </div>
</div>
@else
<!-- Empty div when not authorized -->
@endif
</div>

</div> <!-- Close wrapper div from line 3 -->

<!-- Real-time countdown script using Carbon data -->
<script>
    // Image gallery function
    function changeMainImage(src, element) {
        // Set main image
        document.getElementById('mainImage').src = src;

        // Remove previous border
        document.querySelectorAll('.border-amber-500').forEach(item => {
            item.classList.remove('border-amber-500');
            item.classList.add('border-slate-200');
        });

        // Add border to selected image
        if (element && element.parentElement) {
            element.parentElement.classList.remove('border-slate-200');
            element.parentElement.classList.add('border-amber-500');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElement = document.querySelector('.auction-countdown');

        if (countdownElement) {
            // Koristimo Carbon izračunat broj sekundi
            let totalSecondsLeft = parseInt(countdownElement.dataset.totalSeconds);

            function updateCountdown() {
                if (totalSecondsLeft <= 0) {
                    countdownElement.textContent = 'ZAVRŠENO';
                    // Refresh page when auction ends to show final results
                    setTimeout(() => location.reload(), 1000);
                    return;
                }

                // Izračunavamo vreme iz preostajućih sekundi
                const days = Math.floor(totalSecondsLeft / (24 * 60 * 60));
                const hours = Math.floor((totalSecondsLeft % (24 * 60 * 60)) / (60 * 60));
                const minutes = Math.floor((totalSecondsLeft % (60 * 60)) / 60);
                const seconds = totalSecondsLeft % 60;

                // Formatiramo prikaz kao što Carbon radi
                if (days > 0) {
                    countdownElement.textContent = `${days}d ${hours}h`;
                } else if (hours > 0) {
                    countdownElement.textContent =
                        `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }

                // Smanjujemo broj sekundi za sledeći ciklus
                totalSecondsLeft--;
            }

            // Pokretamo countdown odmah i onda svakih sekund
            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);

            // Čišćenje interval-a kad se komponenta uništi
            window.addEventListener('beforeunload', () => {
                clearInterval(countdownInterval);
            });
        }
    });

    // Refresh auction data after placing a bid (without full page reload)
    document.addEventListener('livewire:init', () => {
        Livewire.on('bid-placed', (event) => {
            // Update current price and bid count without full refresh
            setTimeout(() => {
                Livewire.dispatch('refreshAuction');
            }, 500);
        });
    });

    // Share functionality
    function shareAuction() {
        const url = window.location.href;
        const title = {{ Js::from($auction->listing->title) }};
        const text = title + ' - Aukcija na PazAriO';

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
        heading.textContent = 'Podeli aukciju';

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
        fbLink.className =
            'flex items-center p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors';
        fbLink.innerHTML = '<i class="fab fa-facebook-f w-6"></i><span class="ml-3">Podeli na Facebook-u</span>';

        // WhatsApp
        const waLink = document.createElement('a');
        waLink.href = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
        waLink.target = '_blank';
        waLink.className =
            'flex items-center p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors';
        waLink.innerHTML = '<i class="fab fa-whatsapp w-6"></i><span class="ml-3">Podeli na WhatsApp-u</span>';

        // Email
        const emailLink = document.createElement('a');
        emailLink.href = 'mailto:?subject=' + encodeURIComponent(title) + '&body=' + encodeURIComponent(text + '\n\n' +
            url);
        emailLink.className =
            'flex items-center p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors';
        emailLink.innerHTML = '<i class="fas fa-envelope w-6"></i><span class="ml-3">Pošalji Email</span>';

        // Copy link button
        const copyBtn = document.createElement('button');
        copyBtn.className =
            'w-full flex items-center p-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors';
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
            message.className =
                'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';

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
</script>
</div> <!-- Close root div from line 1 -->
