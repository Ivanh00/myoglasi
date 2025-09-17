<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Moje aukcije</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">Upravljajte svojim aukcijama</p>
    </div>

    <!-- Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div></div> <!-- Empty div for spacing -->

        <!-- Filter -->
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Prikaži:</label>
            <div class="w-60" x-data="{ open: false }" x-init="open = false">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-gray-700 dark:text-gray-200 text-sm text-left hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 transition-colors flex items-center justify-between">
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
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg">
                        <button @click="$wire.set('filter', 'all'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-t-lg {{ $filter === 'all' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Sve aukcije
                        </button>
                        <button @click="$wire.set('filter', 'active'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $filter === 'active' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Aktivne aukcije
                        </button>
                        <button @click="$wire.set('filter', 'ended'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $filter === 'ended' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Sve završene aukcije
                        </button>
                        <button @click="$wire.set('filter', 'ended_with_bids'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $filter === 'ended_with_bids' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Završene sa ponudama
                        </button>
                        <button @click="$wire.set('filter', 'ended_without_bids'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-b-lg {{ $filter === 'ended_without_bids' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Završene bez ponuda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Tabela aukcija -->
    @if ($auctions->count() > 0)
        <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Oglas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Početna/Trenutna cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ponude</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Datum/Vreme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($auctions as $auction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($auction->listing->images->count() > 0)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $auction->listing->images->first()->url }}" alt="{{ $auction->listing->title }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-gavel text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ Str::limit($auction->listing->title, 30) }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $auction->listing->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-gray-600 dark:text-gray-300">Početna: {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                    <div class="text-gray-900 dark:text-gray-100 font-bold">Trenutna: {{ number_format($auction->current_price, 0, ',', '.') }} RSD</div>
                                    @if($auction->buy_now_price)
                                        <div class="text-green-600 text-xs">Kupi odmah: {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-gray-900 dark:text-gray-100 font-semibold">{{ $auction->total_bids }} ponuda</div>
                                    @if($auction->winningBid)
                                        <div class="text-xs text-gray-500">Vodi: {{ $auction->winningBid->user->name }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    @if($auction->isActive())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 mb-1">Aktivna</span>
                                        @if($auction->time_left)
                                            <span class="text-xs text-gray-500">{{ $auction->time_left['formatted'] }} ostalo</span>
                                        @endif
                                    @elseif($auction->hasEnded())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1">Završena</span>
                                        @if($auction->winner)
                                            <span class="text-xs text-green-600">Pobednik: {{ $auction->winner->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">Bez ponuda</span>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200">{{ ucfirst($auction->status) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>Počinje: {{ $auction->starts_at->format('d.m.Y H:i') }}</span>
                                    <span>Završava: {{ $auction->ends_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('auction.show', $auction) }}"
                                            class="inline-flex items-center px-2 py-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 rounded">
                                            <i class="fas fa-eye mr-1"></i> Pregled
                                        </a>

                                        <a href="{{ route('listings.show', $auction->listing) }}"
                                            class="inline-flex items-center px-2 py-1 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 rounded">
                                            <i class="fas fa-list mr-1"></i> Oglas
                                        </a>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('listings.edit', $auction->listing) }}"
                                            class="inline-flex items-center px-2 py-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 rounded">
                                            <i class="fas fa-edit mr-1"></i> Uredi
                                        </a>

                                        @php
                                            $canRemove = !auth()->user()->is_admin ?
                                                $auction->current_price <= $auction->starting_price :
                                                true;
                                        @endphp
                                        @if($auction->hasEnded())
                                            <button x-data
                                                x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovu završenu aukciju iz vaše liste?')) { $wire.deleteAuction({{ $auction->id }}) }"
                                                class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                                <i class="fas fa-trash mr-1"></i> Obriši
                                            </button>
                                        @elseif($canRemove)
                                            <button x-data
                                                x-on:click.prevent="if (confirm('Da li ste sigurni da želite da uklonite ovaj oglas iz aukcije?')) { $wire.removeFromAuction({{ $auction->id }}) }"
                                                class="inline-flex items-center px-2 py-1 text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded">
                                                <i class="fas fa-times mr-1"></i> Ukloni iz aukcije
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Desktop Paginacija -->
        <div class="mt-6">
            {{ $auctions->links() }}
        </div>

        <!-- Auction Rules Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Pravila za upravljanje aukcijama
            </h3>
            
            <div class="p-4 bg-blue-100 dark:bg-blue-900 border border-blue-300 dark:border-blue-700 rounded-lg">
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Uklanjanje aukcije:</strong> Aukcija se može ukloniti samo ukoliko trenutna cena nije premašila početnu cenu aukcije</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-handshake text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Obaveza prodavca:</strong> Nakon što aukcija premaši početnu cenu, prodavac se obavezuje da proda predmet kupcu sa najboljom ponudom</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-star text-yellow-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Posledice nepoštovanja:</strong> Ukoliko prodavac ne ispoštuje obavezu, suočava se sa negativnom ocenom od strane člana</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-shield-alt text-purple-600 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Zaštita kupaca:</strong> Ova pravila štite kupce od nepoštenih prodavaca i osiguravaju fer trgovinu</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-user-shield text-gray-600 dark:text-gray-300 mt-1 mr-2 flex-shrink-0"></i>
                            <span><strong>Napomena za administratore:</strong> Administratori mogu ukloniti aukciju u bilo kom trenutku radi rešavanja sporova</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($auctions as $auction)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($auction->listing->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover" 
                                             src="{{ $auction->listing->images->first()->url }}" alt="{{ $auction->listing->title }}">
                                    @else
                                        <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-gavel text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Auction Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $auction->listing->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ $auction->listing->category->name }}</p>
                                    <div class="text-sm">
                                        <div class="text-gray-600 dark:text-gray-300">Početna: {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                        <div class="text-lg font-bold text-red-600">{{ number_format($auction->current_price, 0, ',', '.') }} RSD</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Status Section -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status aukcije</div>
                            <div class="flex flex-col space-y-2">
                                @if($auction->isActive())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 w-fit">Aktivna</span>
                                    @if($auction->time_left)
                                        <span class="text-xs text-gray-500">{{ $auction->time_left['formatted'] }} ostalo</span>
                                    @endif
                                @elseif($auction->hasEnded())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 w-fit">Završena</span>
                                    @if($auction->winner)
                                        <span class="text-xs text-green-600">Pobednik: {{ $auction->winner->name }}</span>
                                    @else
                                        <span class="text-xs text-gray-500">Bez ponuda</span>
                                    @endif
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 w-fit">{{ ucfirst($auction->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Bids Info -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Ponude</div>
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $auction->total_bids }} ponuda</div>
                                @if($auction->winningBid)
                                    <div class="text-xs text-gray-500">Vodi: {{ $auction->winningBid->user->name }}</div>
                                @endif
                                @if($auction->buy_now_price)
                                    <div class="text-xs text-green-600">Kupi odmah: {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                @endif
                            </div>
                        </div>

                        <!-- Time Info -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Vreme</div>
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900 dark:text-gray-100">Počinje: {{ $auction->starts_at->format('d.m.Y H:i') }}</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">Završava: {{ $auction->ends_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('auction.show', $auction) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled aukcije
                            </a>

                            <a href="{{ route('listings.show', $auction->listing) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-list mr-1"></i>
                                Oglas
                            </a>

                            <a href="{{ route('listings.edit', $auction->listing) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Uredi
                            </a>

                            @php
                                $canRemove = !auth()->user()->is_admin ?
                                    $auction->current_price <= $auction->starting_price :
                                    true;
                            @endphp
                            @if($auction->hasEnded())
                                <button x-data
                                    x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovu završenu aukciju iz vaše liste?')) { $wire.deleteAuction({{ $auction->id }}) }"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Obriši
                                </button>
                            @elseif($canRemove)
                                <button x-data
                                    x-on:click.prevent="if (confirm('Da li ste sigurni da želite da uklonite ovaj oglas iz aukcije?')) { $wire.removeFromAuction({{ $auction->id }}) }"
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
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gavel text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nemate nijednu aukciju</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Prvo kreirajte oglas, a zatim možete postaviti aukciju.</p>
            @php
                $hasListings = auth()->user()->listings()->count() > 0;
            @endphp
            @if($hasListings)
                <a href="{{ route('listings.my') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors mr-2">
                    Moji oglasi
                </a>
            @else
                <a href="{{ route('listings.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Kreiraj oglas
                </a>
            @endif
        </div>
    @endif
</div>