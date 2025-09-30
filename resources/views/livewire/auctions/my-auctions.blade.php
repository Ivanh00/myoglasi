<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Header with background -->
    <div class="bg-amber-100 dark:bg-amber-900/50 rounded-t-lg px-4 py-4 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold text-amber-900 dark:text-amber-100">Moje aukcije</h1>
            <a href="{{ route('listings.create') }}?type=auction"
                class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                <i class="fas fa-plus mr-2"></i> Dodaj novu aukciju
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="flex justify-end mb-6">

        <!-- Filter -->
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Prikaži:</label>
            <div class="w-60" x-data="{ open: false }" x-init="open = false">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-sky-500 dark:focus:border-sky-400 transition-colors flex items-center justify-between">
                        <span>
                            @switch($filter)
                                @case('active')
                                    Aktivne aukcije
                                @break

                                @case('ended')
                                    Sve završene aukcije
                                @break

                                @case('ended_with_bids')
                                    Završene sa ponudama
                                @break

                                @case('ended_without_bids')
                                    Završene bez ponuda
                                @break

                                @default
                                    Sve aukcije
                            @endswitch
                        </span>
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                        <button @click="$wire.set('filter', 'all'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $filter === 'all' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Sve aukcije
                        </button>
                        <button @click="$wire.set('filter', 'active'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $filter === 'active' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Aktivne aukcije
                        </button>
                        <button @click="$wire.set('filter', 'ended'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $filter === 'ended' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Sve završene aukcije
                        </button>
                        <button @click="$wire.set('filter', 'ended_with_bids'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $filter === 'ended_with_bids' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Završene sa ponudama
                        </button>
                        <button @click="$wire.set('filter', 'ended_without_bids'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg {{ $filter === 'ended_without_bids' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Završene bez ponuda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Tabela aukcija -->
    @if ($auctions->count() > 0)
        <div class="hidden lg:block space-y-1">
            <!-- Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
                <div class="grid grid-cols-[25%_20%_15%_15%_25%] bg-slate-50 dark:bg-slate-700">
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Oglas</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Početna/Trenutna cena</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Ponude</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Status</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Datum/Vreme</div>
                </div>
            </div>

            <!-- Data Rows -->
            @foreach ($auctions as $auction)
                <div
                    class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden border-l-4 border-amber-500">
                    <div class="grid grid-cols-[25%_20%_15%_15%_25%] hover:bg-slate-50 dark:hover:bg-slate-700">
                        <!-- Oglas Column -->
                        <div class="px-4 py-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($auction->listing->images->count() > 0)
                                        <img class="h-10 w-10 rounded-lg object-cover"
                                            src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-gavel text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100 break-words">
                                        {{ Str::limit($auction->listing->title, 40) }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300">
                                        {{ $auction->listing->category->name }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Početna/Trenutna cena Column -->
                        <div class="px-4 py-2">
                            <div class="text-sm">
                                <div class="text-slate-600 dark:text-slate-300">Početna:
                                    {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                <div class="text-slate-900 dark:text-slate-100 font-bold">Trenutna:
                                    {{ number_format($auction->current_price, 0, ',', '.') }} RSD</div>
                                @if ($auction->buy_now_price)
                                    <div class="text-green-600 text-xs">Kupi odmah:
                                        {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                @endif
                            </div>
                        </div>
                        <!-- Ponude Column -->
                        <div class="px-4 py-2">
                            <div class="text-sm">
                                <div class="text-slate-900 dark:text-slate-100 font-semibold">
                                    {{ $auction->total_bids }} ponuda</div>
                                @if ($auction->winningBid)
                                    <div class="text-xs text-slate-500 dark:text-slate-300">Vodi:
                                        {{ $auction->winningBid->user->name }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Status Column -->
                        <div class="px-4 py-2">
                            <div class="flex flex-col items-start">
                                @if ($auction->isActive())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 mb-1 w-fit">Aktivna</span>
                                    @if ($auction->time_left)
                                        <span
                                            class="text-xs text-slate-500 dark:text-slate-300">{{ $auction->time_left['formatted'] }}
                                            ostalo</span>
                                    @endif
                                @elseif($auction->hasEnded())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1 w-fit">Završena</span>
                                    @if ($auction->winner)
                                        <span class="text-xs text-green-600">Pobednik:
                                            {{ $auction->winner->name }}</span>
                                    @else
                                        <span class="text-xs text-slate-500 dark:text-slate-300">Bez ponuda</span>
                                    @endif
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 w-fit">
                                        @if($auction->status === 'active' && $auction->starts_at->isFuture())
                                            Zakazana
                                        @elseif($auction->status === 'active')
                                            Aktivna
                                        @elseif($auction->status === 'ended')
                                            Završena
                                        @elseif($auction->status === 'cancelled')
                                            Otkazana
                                        @else
                                            {{ ucfirst($auction->status) }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Datum/Vreme Column -->
                        <div class="px-4 py-2 text-sm text-slate-500 dark:text-slate-300">
                            <div class="flex flex-col space-y-1">
                                <div>Počinje: {{ $auction->starts_at->format('d.m.Y') }}
                                    {{ $auction->starts_at->format('H:i') }}</div>
                                <div>Završava: {{ $auction->ends_at->format('d.m.Y') }}
                                    {{ $auction->ends_at->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Actions Row -->
                    <div class="border-t border-slate-200 dark:border-slate-600 px-4 py-2 bg-slate-50 dark:bg-slate-700/50">
                        <div class="flex flex-wrap gap-2">
                                <a href="{{ route('auction.show', $auction) }}"
                                    class="inline-flex items-center px-2 py-1 text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>

                                @if ($auction->total_bids == 0)
                                    <a href="{{ route('listings.edit', $auction->listing) }}"
                                        class="inline-flex items-center px-2 py-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 rounded">
                                        <i class="fas fa-edit mr-1"></i> Uredi
                                    </a>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-slate-500 dark:text-slate-300 rounded cursor-not-allowed"
                                        title="Ne možete uređivati aukciju koja ima ponude">
                                        <i class="fas fa-edit mr-1"></i> Uredi
                                    </span>
                                @endif

                                @if ($auction->listing && $auction->status === 'active')
                                    <button
                                        wire:click="$dispatch('openPromotionModal', { listingId: {{ $auction->listing->id }} })"
                                        class="inline-flex items-center px-2 py-1 {{ $auction->listing->hasActivePromotion() ? 'text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300' : 'text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300' }} rounded">
                                        <i class="fas fa-bullhorn mr-1"></i>
                                        Promocija
                                    </button>
                                @endif

                                @php
                                    $canRemove = !auth()->user()->is_admin ? $auction->total_bids == 0 : true;
                                @endphp
                                @if ($auction->hasEnded())
                                    <button x-data
                                        @click="$dispatch('open-delete-modal', { auctionId: {{ $auction->id }} })"
                                        class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                        <i class="fas fa-trash mr-1"></i> Obriši
                                    </button>
                                @elseif($canRemove)
                                    <button x-data
                                        @click="$dispatch('open-remove-modal', { auctionId: {{ $auction->id }} })"
                                        class="inline-flex items-center px-2 py-1 text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded">
                                        <i class="fas fa-times mr-1"></i> Ukloni iz aukcije
                                    </button>
                                @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Paginacija -->
        <div class="hidden lg:block mt-6">
            {{ $auctions->links() }}
        </div>

        <!-- Auction Rules Section - Desktop only (after desktop table) -->
        <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6 mt-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mr-2"></i>
                Pravila za upravljanje aukcijama
            </h3>

            <div class="p-4 bg-sky-100 dark:bg-sky-900 border border-sky-300 dark:border-sky-700 rounded-lg">
                <div class="text-sm text-sky-800 dark:text-sky-200">
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 dark:text-red-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Uklanjanje aukcije:</strong> Aukcija se može ukloniti samo ukoliko nema
                                ponuda</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-edit text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Uređivanje aukcije:</strong> Aukcija se ne može uređivati nakon što primi prvu
                                ponudu</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-handshake text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Obaveza prodavca:</strong> Nakon što aukcija premaši početnu cenu, prodavac se
                                obavezuje da proda predmet kupcu sa najboljom ponudom</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-star text-amber-600 dark:text-amber-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Posledice nepoštovanja:</strong> Ukoliko prodavac ne ispoštuje obavezu,
                                suočava se sa negativnom ocenom od strane člana</span>
                        </li>
                        <li class="flex items-start">
                            <i
                                class="fas fa-shield-alt text-purple-600 dark:text-purple-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Zaštita kupaca:</strong> Ova pravila štite kupce od nepoštenih prodavaca i
                                osiguravaju fer trgovinu</span>
                        </li>
                        <li class="flex items-start">
                            <i
                                class="fas fa-user-shield text-slate-600 dark:text-slate-300 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Napomena za administratore:</strong> Administratori mogu ukloniti aukciju u
                                bilo kom trenutku radi rešavanja sporova</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($auctions as $auction)
                <div class="bg-white dark:bg-slate-800 border-l-4 border-amber-500 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($auction->listing->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover"
                                            src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-gavel text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Auction Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-1">
                                        {{ $auction->listing->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
                                        {{ $auction->listing->category->name }}</p>
                                    <div class="text-sm">
                                        <div class="text-slate-600 dark:text-slate-300">Početna:
                                            {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                        <div class="text-lg font-bold text-red-600 dark:text-red-400">
                                            {{ number_format($auction->current_price, 0, ',', '.') }} RSD</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Status Section -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Status aukcije</div>
                            <div class="flex flex-col space-y-2">
                                @if ($auction->isActive())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 w-fit">Aktivna</span>
                                    @if ($auction->time_left)
                                        <span
                                            class="text-xs text-slate-500 dark:text-slate-300">{{ $auction->time_left['formatted'] }}
                                            ostalo</span>
                                    @endif
                                @elseif($auction->hasEnded())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 w-fit">Završena</span>
                                    @if ($auction->winner)
                                        <span class="text-xs text-green-600">Pobednik:
                                            {{ $auction->winner->name }}</span>
                                    @else
                                        <span class="text-xs text-slate-500 dark:text-slate-300">Bez ponuda</span>
                                    @endif
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 w-fit">{{ ucfirst($auction->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Bids Info -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Ponude</div>
                            <div class="space-y-1">
                                <div class="text-sm text-slate-900 dark:text-slate-100">{{ $auction->total_bids }}
                                    ponuda</div>
                                @if ($auction->winningBid)
                                    <div class="text-xs text-slate-500 dark:text-slate-300">Vodi:
                                        {{ $auction->winningBid->user->name }}
                                    </div>
                                @endif
                                @if ($auction->buy_now_price)
                                    <div class="text-xs text-green-600">Kupi odmah:
                                        {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                @endif
                            </div>
                        </div>

                        <!-- Time Info -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Vreme</div>
                            <div class="space-y-1">
                                <div class="text-sm text-slate-900 dark:text-slate-100">Počinje:
                                    {{ $auction->starts_at->format('d.m.Y H:i') }}</div>
                                <div class="text-sm text-slate-900 dark:text-slate-100">Završava:
                                    {{ $auction->ends_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('auction.show', $auction) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled aukcije
                            </a>

                            @if ($auction->total_bids == 0)
                                <a href="{{ route('listings.edit', $auction->listing) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Uredi
                                </a>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-medium rounded-lg cursor-not-allowed"
                                    title="Ne možete uređivati aukciju koja ima ponude">
                                    <i class="fas fa-edit mr-1"></i>
                                    Uredi
                                </span>
                            @endif

                            @php
                                $canRemove = !auth()->user()->is_admin ? $auction->total_bids == 0 : true;
                            @endphp
                            @if ($auction->hasEnded())
                                <button x-data
                                    @click="$dispatch('open-delete-modal', { auctionId: {{ $auction->id }} })"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Obriši
                                </button>
                            @elseif($canRemove)
                                <button x-data
                                    @click="$dispatch('open-remove-modal', { auctionId: {{ $auction->id }} })"
                                    class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                                    <i class="fas fa-times mr-1"></i>
                                    Ukloni iz aukcije
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Pagination -->
        <div class="lg:hidden mt-6">
            {{ $auctions->links() }}
        </div>

        <!-- Auction Rules Section - Mobile only (after mobile cards) -->
        <div class="lg:hidden bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6 mt-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mr-2"></i>
                Pravila za upravljanje aukcijama
            </h3>

            <div class="p-4 bg-sky-100 dark:bg-sky-900 border border-sky-300 dark:border-sky-700 rounded-lg">
                <div class="text-sm text-sky-800 dark:text-sky-200">
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 dark:text-red-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Uklanjanje aukcije:</strong> Aukcija se može ukloniti samo ukoliko nema
                                ponuda</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-edit text-sky-600 dark:text-sky-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Uređivanje aukcije:</strong> Aukcija se ne može uređivati nakon što primi prvu
                                ponudu</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-handshake text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Obaveza prodavca:</strong> Nakon što aukcija premaši početnu cenu, prodavac se
                                obavezuje da proda predmet kupcu sa najboljom ponudom</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-star text-amber-600 dark:text-amber-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Posledice nepoštovanja:</strong> Ukoliko prodavac ne ispoštuje obavezu,
                                suočava se sa negativnom ocenom od strane člana</span>
                        </li>
                        <li class="flex items-start">
                            <i
                                class="fas fa-shield-alt text-purple-600 dark:text-purple-400 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Zaštita kupaca:</strong> Ova pravila štite kupce od nepoštenih prodavaca i
                                osiguravaju fer trgovinu</span>
                        </li>
                        <li class="flex items-start">
                            <i
                                class="fas fa-user-shield text-slate-600 dark:text-slate-300 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Napomena za administratore:</strong> Administratori mogu ukloniti aukciju u
                                bilo kom trenutku radi rešavanja sporova</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gavel text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Nemate nijednu aukciju</h3>
            <p class="text-slate-600 dark:text-slate-300 mb-4">Prvo kreirajte oglas, a zatim možete postaviti aukciju.
            </p>
            @php
                $hasListings = auth()->user()->listings()->count() > 0;
            @endphp
            @if ($hasListings)
                <a href="{{ route('listings.my') }}"
                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors mr-2">
                    Moji oglasi
                </a>
            @else
                <a href="{{ route('listings.create') }}"
                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                    Kreiraj oglas
                </a>
            @endif
        </div>
    @endif

    <!-- Remove from Auction Modal -->
    <div x-data="{
        showRemoveModal: false,
        selectedAuction: null,
        removeFromAuction() {
            if (this.selectedAuction) {
                @this.removeFromAuction(this.selectedAuction.id);
                this.showRemoveModal = false;
            }
        }
    }"
        @open-remove-modal.window="
            showRemoveModal = true;
            selectedAuction = $auctions.find(a => a.id === $event.detail.auctionId);
        "
        x-show="showRemoveModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showRemoveModal = false"></div>

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
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Uklanjanje aukcije</h3>
                        </div>
                        <button @click="showRemoveModal = false" class="text-white hover:text-slate-200">
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
                            Ova akcija će ukloniti vaš oglas iz aukcija i vratiti ga u obične oglase.
                        </p>
                    </div>

                    <!-- Auction info -->
                    <template x-if="selectedAuction">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Naziv:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedAuction.listing?.title || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Trenutna cena:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                        <span
                                            x-text="new Intl.NumberFormat('sr-RS').format(selectedAuction?.current_price || 0)"></span>
                                        RSD
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Broj ponuda:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedAuction?.total_bids || 0"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Important notice based on auction state -->
                    <template
                        x-if="selectedAuction && selectedAuction.total_bids > 0 && selectedAuction.current_price > selectedAuction.starting_price">
                        <div
                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-800 dark:text-red-200">
                                        <strong>Upozorenje:</strong> Ne možete ukloniti aukciju jer je trenutna cena
                                        veća od početne cene. Ova zaštita obezbeđuje fer trgovinu.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template
                        x-if="selectedAuction && (selectedAuction.total_bids === 0 || selectedAuction.current_price <= selectedAuction.starting_price)">
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
                        </div>
                    </template>
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-1">
                    <div class="flex space-x-3">
                        <button type="button" @click="showRemoveModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button" @click="removeFromAuction()"
                            :disabled="selectedAuction && selectedAuction.current_price > selectedAuction.starting_price"
                            :class="selectedAuction && selectedAuction.current_price > selectedAuction.starting_price ?
                                'flex-1 px-4 py-2.5 bg-slate-300 text-slate-500 font-medium rounded-lg cursor-not-allowed' :
                                'flex-1 px-4 py-2.5 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-medium rounded-lg hover:from-orange-700 hover:to-orange-800 transition-all transform hover:scale-105'">
                            <i class="fas fa-trash mr-2"></i>
                            Ukloni aukciju
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.$auctions = @json($auctions->items());
    </script>

    <!-- Delete Auction Modal -->
    <div x-data="{
        showDeleteModal: false,
        selectedAuction: null,
        deleteAuction() {
            if (this.selectedAuction) {
                @this.deleteAuction(this.selectedAuction.id);
                this.showDeleteModal = false;
            }
        }
    }"
        @open-delete-modal.window="
            showDeleteModal = true;
            selectedAuction = $auctions.find(a => a.id === $event.detail.auctionId);
        "
        x-show="showDeleteModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showDeleteModal = false"></div>

        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <!-- Modal header with delete icon -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-trash text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Brisanje završene aukcije</h3>
                        </div>
                        <button @click="showDeleteModal = false" class="text-white hover:text-slate-200">
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
                            Ova završena aukcija će biti obrisana iz vaše liste. Aukcija će ostati vidljiva
                            administratorima sa statusom "Obrisana".
                        </p>
                    </div>

                    <!-- Auction info -->
                    <template x-if="selectedAuction">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Naziv:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedAuction.listing?.title || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Završna cena:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                        <span
                                            x-text="new Intl.NumberFormat('sr-RS').format(selectedAuction?.current_price || 0)"></span>
                                        RSD
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Broj ponuda:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedAuction?.total_bids || 0"></span>
                                </div>
                                <template x-if="selectedAuction?.winner">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-slate-600 dark:text-slate-300">Pobednik:</span>
                                        <span class="text-sm font-medium text-green-600 dark:text-green-400"
                                            x-text="selectedAuction.winner?.name || 'N/A'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Notice -->
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-amber-600 dark:text-amber-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-800 dark:text-amber-200">
                                    <strong>Napomena:</strong> Završene aukcije možete obrisati radi bolje preglednosti
                                    vaše liste. Brisanje neće uticati na istoriju transakcija.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-1">
                    <div class="flex space-x-3">
                        <button type="button" @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button" @click="deleteAuction()"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                            <i class="fas fa-trash mr-2"></i>
                            Obriši aukciju
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotion Manager Modal -->
    @livewire('listings.promotion-manager')
</div>
