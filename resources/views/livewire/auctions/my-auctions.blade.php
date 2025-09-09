<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Moje aukcije</h1>
        <p class="text-gray-600 mt-2">Upravljajte svojim aukcijama</p>
    </div>

    <!-- Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div></div> <!-- Empty div for spacing -->
        
        <!-- Filter -->
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">Prikaži:</label>
            <select wire:model.live="filter" 
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">Sve aukcije</option>
                <option value="active">Aktivne aukcije</option>
                <option value="ended">Završene aukcije</option>
            </select>
        </div>
    </div>

    <!-- Desktop Tabela aukcija -->
    @if ($auctions->count() > 0)
        <div class="hidden lg:block bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oglas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Početna/Trenutna cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ponude</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum/Vreme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
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
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($auction->listing->title, 30) }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $auction->listing->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-gray-600">Početna: {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                    <div class="text-gray-900 font-bold">Trenutna: {{ number_format($auction->current_price, 0, ',', '.') }} RSD</div>
                                    @if($auction->buy_now_price)
                                        <div class="text-green-600 text-xs">Kupi odmah: {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-gray-900 font-semibold">{{ $auction->total_bids }} ponuda</div>
                                    @if($auction->winningBid)
                                        <div class="text-xs text-gray-500">Vodi: {{ $auction->winningBid->user->name }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    @if($auction->isActive())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mb-1">Aktivna</span>
                                        @if($auction->time_left)
                                            <span class="text-xs text-gray-500">{{ $auction->time_left['formatted'] }} ostalo</span>
                                        @endif
                                    @elseif($auction->hasEnded())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mb-1">Završena</span>
                                        @if($auction->winner)
                                            <span class="text-xs text-green-600">Pobednik: {{ $auction->winner->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">Bez ponuda</span>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($auction->status) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>Počinje: {{ $auction->starts_at->format('d.m.Y H:i') }}</span>
                                    <span>Završava: {{ $auction->ends_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('auction.show', $auction) }}"
                                        class="inline-flex items-center px-2 py-1 text-blue-600 hover:text-blue-900 rounded">
                                        <i class="fas fa-eye mr-1"></i> Pregled
                                    </a>
                                    
                                    <a href="{{ route('listings.show', $auction->listing) }}"
                                        class="inline-flex items-center px-2 py-1 text-indigo-600 hover:text-indigo-900 rounded">
                                        <i class="fas fa-list mr-1"></i> Oglas
                                    </a>
                                    
                                    @if(!$auction->isActive() || $auction->total_bids == 0)
                                        <button x-data
                                            x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovu aukciju?')) { $wire.deleteAuction({{ $auction->id }}) }"
                                            class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-900 rounded">
                                            <i class="fas fa-trash mr-1"></i> Obriši
                                        </button>
                                    @endif
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

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($auctions as $auction)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-gray-200">
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
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $auction->listing->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ $auction->listing->category->name }}</p>
                                    <div class="text-sm">
                                        <div class="text-gray-600">Početna: {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
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
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status aukcije</div>
                            <div class="flex flex-col space-y-2">
                                @if($auction->isActive())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 w-fit">Aktivna</span>
                                    @if($auction->time_left)
                                        <span class="text-xs text-gray-500">{{ $auction->time_left['formatted'] }} ostalo</span>
                                    @endif
                                @elseif($auction->hasEnded())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 w-fit">Završena</span>
                                    @if($auction->winner)
                                        <span class="text-xs text-green-600">Pobednik: {{ $auction->winner->name }}</span>
                                    @else
                                        <span class="text-xs text-gray-500">Bez ponuda</span>
                                    @endif
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 w-fit">{{ ucfirst($auction->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Bids Info -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Ponude</div>
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900">{{ $auction->total_bids }} ponuda</div>
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
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Vreme</div>
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900">Počinje: {{ $auction->starts_at->format('d.m.Y H:i') }}</div>
                                <div class="text-sm text-gray-900">Završava: {{ $auction->ends_at->format('d.m.Y H:i') }}</div>
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
                            
                            @if(!$auction->isActive() || $auction->total_bids == 0)
                                <button x-data
                                    x-on:click.prevent="if (confirm('Da li ste sigurni da želite da obrišete ovu aukciju?')) { $wire.deleteAuction({{ $auction->id }}) }"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Obriši
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
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-gavel text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nemate nijednu aukciju</h3>
            <p class="text-gray-600 mb-4">Prvo kreirajte oglas, a zatim možete postaviti aukciju.</p>
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