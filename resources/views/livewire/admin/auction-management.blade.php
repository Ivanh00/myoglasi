<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Upravljanje aukcijama</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Pregled i upravljanje svim aukcijama u sistemu</p>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Pretraga</label>
                <input type="text" wire:model.live="search" id="search" placeholder="Naslov, opis ili prodavac..."
                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status-filter" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
                <select wire:model.live="filters.status" id="status-filter"
                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Svi statusi</option>
                    @foreach ($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bids Filter -->
            <div>
                <label for="bids-filter" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Ponude</label>
                <select wire:model.live="filters.has_bids" id="bids-filter"
                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Sve aukcije</option>
                    <option value="1">Sa ponudama</option>
                    <option value="0">Bez ponuda</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label for="per-page" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Po strani</label>
                <select wire:model.live="perPage" id="per-page"
                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Ukupno aukcija: {{ $auctions->total() }}
            </div>
            <button wire:click="resetFilters" class="text-sm text-indigo-600 hover:text-indigo-900">
                Resetuj filtere
            </button>
        </div>
    </div>

    <!-- Desktop Auctions Table -->
    <div class="hidden lg:block bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th wire:click="sortBy('id')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100">
                        ID
                        @if ($sortField === 'id')
                            <span class="text-indigo-500">
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            </span>
                        @endif
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Oglas
                    </th>
                    <th wire:click="sortBy('current_price')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100">
                        Cene
                        @if ($sortField === 'current_price')
                            <span class="text-indigo-500">
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            </span>
                        @endif
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Ponude
                    </th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100">
                        Status
                        @if ($sortField === 'status')
                            <span class="text-indigo-500">
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            </span>
                        @endif
                    </th>
                    <th wire:click="sortBy('ends_at')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100">
                        Završava
                        @if ($sortField === 'ends_at')
                            <span class="text-indigo-500">
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            </span>
                        @endif
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Prodavac
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($auctions as $auction)
                    <tr class="hover:bg-slate-50 border-t border-slate-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            #{{ $auction->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($auction->listing->images->count() > 0)
                                        <img class="h-10 w-10 rounded object-cover"
                                            src="{{ $auction->listing->images->first()->url }}"
                                            alt="{{ $auction->listing->title }}">
                                    @else
                                        <div class="h-10 w-10 rounded bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-gavel text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ Str::limit($auction->listing->title, 30) }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300">
                                        {{ $auction->listing->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900">
                                <div>Trenutna: <strong>{{ number_format($auction->current_price, 0, ',', '.') }}
                                        RSD</strong></div>
                                <div class="text-slate-500 dark:text-slate-300">Početna:
                                    {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                @if ($auction->buy_now_price)
                                    <div class="text-green-600">Kupi odmah:
                                        {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900">{{ $auction->total_bids }} ponuda</div>
                            @if ($auction->winner)
                                <div class="text-sm text-green-600">Pobednik: {{ $auction->winner->name }}</div>
                            @elseif($auction->total_bids > 0)
                                @php
                                    $winningBid = $auction->bids()->where('is_winning', true)->first();
                                @endphp
                                @if ($winningBid)
                                    <div class="text-sm text-sky-600 dark:text-sky-400">Vodi:
                                        {{ $winningBid->user->name }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($auction->deleted_at)
                                <div class="flex flex-col space-y-1">
                                    @if ($auction->status === 'ended')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 w-fit">
                                            Završena
                                        </span>
                                    @endif
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 w-fit">
                                        Obrisana
                                    </span>
                                </div>
                            @else
                                @switch($auction->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Na čekanju
                                        </span>
                                    @break

                                    @case('active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktivna
                                        </span>
                                    @break

                                    @case('ended')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            Završena
                                        </span>
                                    @break

                                    @case('cancelled')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Otkazana
                                        </span>
                                    @break
                                @endswitch
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                            {{ $auction->ends_at->format('d.m.Y H:i') }}
                            @if ($auction->isActive())
                                <div class="text-xs text-slate-500 dark:text-slate-300">
                                    @php
                                        $timeLeft = $auction->time_left;
                                    @endphp
                                    @if ($timeLeft)
                                        {{ $timeLeft['formatted'] }} ostalo
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-slate-100">{{ $auction->seller->name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-300">{{ $auction->seller->email }}</div>
                        </td>
                    </tr>
                    <!-- Actions Row -->
                    <tr class="border-t border-slate-200">
                        <td colspan="7" class="px-4 py-2 bg-slate-50">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('auction.show', $auction) }}" target="_blank"
                                    class="inline-flex items-center px-2 py-1 text-indigo-600 hover:text-indigo-800 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>

                                <button wire:click="editAuction({{ $auction->id }})"
                                    class="inline-flex items-center px-2 py-1 text-sky-600 hover:text-sky-800 rounded">
                                    <i class="fas fa-edit mr-1"></i> Uredi
                                </button>

                                @if ($auction->isActive())
                                    <button x-data
                                        x-on:click.prevent="if (confirm('Da li ste sigurni da želite da završite ovu aukciju?')) { $wire.endAuction({{ $auction->id }}) }"
                                        class="inline-flex items-center px-2 py-1 text-orange-600 hover:text-orange-800 rounded">
                                        <i class="fas fa-stop-circle mr-1"></i> Završi aukciju
                                    </button>
                                @endif

                                <button wire:click="confirmDelete({{ $auction->id }})"
                                    class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-800 rounded">
                                    <i class="fas fa-trash mr-1"></i> Obriši
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-300 text-center">
                                Nema aukcija koje odgovaraju filterima.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        <!-- Desktop Pagination -->
        <div class="mt-6">
            {{ $auctions->links() }}
        </div>
    </div>

    <!-- Mobile Auctions Cards -->
    <div class="lg:hidden space-y-4">
        @forelse($auctions as $auction)
            <div class="bg-white shadow rounded-lg p-4">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="text-sm text-slate-500 dark:text-slate-300 mb-1">Aukcija #{{ $auction->id }}</div>
                        <div class="text-lg font-semibold text-slate-900 mb-2">{{ Str::limit($auction->listing->title, 40) }}
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-slate-500 dark:text-slate-300 mb-2">
                            <span><i class="fas fa-tag mr-1"></i>{{ $auction->listing->category->name ?? 'N/A' }}</span>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Trenutna:</span>
                                <span class="font-bold text-green-600">{{ number_format($auction->current_price, 0, ',', '.') }} RSD</span>
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-300">
                                Početna: {{ number_format($auction->starting_price, 0, ',', '.') }} RSD
                            </div>
                            @if ($auction->buy_now_price)
                                <div class="text-xs text-green-600">
                                    Kupi odmah: {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Image -->
                    @if ($auction->listing->images->count() > 0)
                        <div class="flex-shrink-0 ml-4">
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ $auction->listing->images->first()->url }}"
                                alt="{{ $auction->listing->title }}">
                        </div>
                    @else
                        <div class="flex-shrink-0 ml-4">
                            <div class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center">
                                <i class="fas fa-gavel text-slate-400"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Seller Info -->
                <div class="bg-slate-50 p-3 rounded-lg mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Prodavac</div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            @if ($auction->seller->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $auction->seller->avatar_url }}"
                                    alt="{{ $auction->seller->name }}">
                            @else
                                <div
                                    class="h-8 w-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-xs">
                                    {{ strtoupper(substr($auction->seller->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-slate-900">{{ $auction->seller->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ $auction->seller->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Bids and Status Info -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Ponude</div>
                        <div class="text-sm text-slate-900">{{ $auction->total_bids }} ponuda</div>
                        @if ($auction->winner)
                            <div class="text-xs text-green-600 mt-1">Pobednik: {{ $auction->winner->name }}</div>
                        @elseif($auction->total_bids > 0)
                            @php
                                $winningBid = $auction->bids()->where('is_winning', true)->first();
                            @endphp
                            @if ($winningBid)
                                <div class="text-xs text-sky-600 dark:text-sky-400 mt-1">Vodi: {{ $winningBid->user->name }}</div>
                            @endif
                        @endif
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Status</div>
                        @if ($auction->deleted_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Obrisana
                            </span>
                        @else
                            @switch($auction->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Na čekanju
                                    </span>
                                @break
                                @case('active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktivna
                                    </span>
                                @break
                                @case('ended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        Završena
                                    </span>
                                @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Otkazana
                                    </span>
                                @break
                            @endswitch
                        @endif
                    </div>
                </div>

                <!-- End Time -->
                <div class="mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Završava</div>
                    <div class="text-sm text-slate-900">{{ $auction->ends_at->format('d.m.Y H:i') }}</div>
                    @if ($auction->isActive())
                        @php
                            $timeLeft = $auction->time_left;
                        @endphp
                        @if ($timeLeft)
                            <div class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                {{ $timeLeft['formatted'] }} ostalo
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('auction.show', $auction) }}" target="_blank"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Pregled
                    </a>

                    <button wire:click="editAuction({{ $auction->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Uredi
                    </button>

                    @if ($auction->isActive())
                        <button x-data
                            x-on:click.prevent="if (confirm('Da li ste sigurni da želite da završite ovu aukciju?')) { $wire.endAuction({{ $auction->id }}) }"
                            class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                            <i class="fas fa-stop-circle mr-1"></i>
                            Završi
                        </button>
                    @endif

                    <button wire:click="confirmDelete({{ $auction->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Obriši
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-gavel text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema aukcija</h3>
                <p class="text-slate-600 dark:text-slate-400">Nema aukcija koje odgovaraju filterima.</p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-6">
            {{ $auctions->links() }}
        </div>
    </div>

        <!-- Edit Modal -->
        @if ($showEditModal)
            <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class=" font-medium text-slate-900 mb-4">
                            Uredi aukciju #{{ $selectedAuction->id ?? '' }}
                        </h3>

                        @if ($selectedAuction)
                            <form wire:submit.prevent="updateAuction">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Starting Price -->
                                    <div>
                                        <label for="starting_price"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">Početna cena (RSD)</label>
                                        <input type="number" wire:model="editState.starting_price" id="starting_price"
                                            min="1" step="1"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.starting_price')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Buy Now Price -->
                                    <div>
                                        <label for="buy_now_price" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kupi
                                            odmah cena (RSD)</label>
                                        <input type="number" wire:model="editState.buy_now_price" id="buy_now_price"
                                            min="1" step="1"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.buy_now_price')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Current Price -->
                                    <div>
                                        <label for="current_price"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trenutna cena (RSD)</label>
                                        <input type="number" wire:model="editState.current_price" id="current_price"
                                            min="1" step="1"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.current_price')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
                                        <select wire:model="editState.status" id="status"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            @foreach ($statusOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('editState.status')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Starts At -->
                                    <div>
                                        <label for="starts_at"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">Počinje</label>
                                        <input type="datetime-local" wire:model="editState.starts_at" id="starts_at"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.starts_at')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Ends At -->
                                    <div>
                                        <label for="ends_at"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">Završava</label>
                                        <input type="datetime-local" wire:model="editState.ends_at" id="ends_at"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.ends_at')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-end space-x-3">
                                    <button type="button" wire:click="$set('showEditModal', false)"
                                        class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                                        Otkaži
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700">
                                        Sačuvaj izmene
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Modal -->
        @if ($showDeleteModal)
            <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class=" font-medium text-slate-900 mb-4">Potvrda brisanja aukcije</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
                            Da li ste sigurni da želite da obrišete aukciju za
                            "{{ $selectedAuction->listing->title ?? '' }}"?
                            <br>Ovo će obrisati samo aukciju, oglas će ostati netaknut.
                            @if ($selectedAuction && $selectedAuction->total_bids > 0)
                                <br><strong class="text-red-600 dark:text-red-400">Upozorenje: Ova aukcija ima
                                    {{ $selectedAuction->total_bids }} ponuda!</strong>
                            @endif
                        </p>

                        <div class="flex justify-center space-x-3">
                            <button wire:click="$set('showDeleteModal', false)"
                                class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                                Otkaži
                            </button>
                            <button wire:click="deleteAuction"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Obriši aukciju
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
