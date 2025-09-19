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
                <label for="search" class="block text-sm font-medium text-slate-700">Pretraga</label>
                <input type="text" wire:model.live="search" id="search" placeholder="Naslov, opis ili prodavac..."
                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status-filter" class="block text-sm font-medium text-slate-700">Status</label>
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
                <label for="bids-filter" class="block text-sm font-medium text-slate-700">Ponude</label>
                <select wire:model.live="filters.has_bids" id="bids-filter"
                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Sve aukcije</option>
                    <option value="1">Sa ponudama</option>
                    <option value="0">Bez ponuda</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label for="per-page" class="block text-sm font-medium text-slate-700">Po strani</label>
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

    <!-- Auctions Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
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
                    <th
                        class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Akcije
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($auctions as $auction)
                    <tr class="hover:bg-slate-50">
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
                            <div class="text-sm text-slate-900 dark:text-slate-700">{{ $auction->seller->name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-300">{{ $auction->seller->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('auction.show', $auction) }}" target="_blank"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                <button wire:click="editAuction({{ $auction->id }})"
                                    class="text-sky-600 hover:text-sky-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                @if ($auction->isActive())
                                    <button x-data
                                        x-on:click.prevent="if (confirm('Da li ste sigurni da želite da završite ovu aukciju?')) { $wire.endAuction({{ $auction->id }}) }"
                                        class="text-orange-600 hover:text-orange-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 10h6v6H9z"></path>
                                        </svg>
                                    </button>
                                @endif
                                <button wire:click="confirmDelete({{ $auction->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-300 text-center">
                                Nema aukcija koje odgovaraju filterima.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $auctions->links() }}
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
                                            class="block text-sm font-medium text-slate-700">Početna cena (RSD)</label>
                                        <input type="number" wire:model="editState.starting_price" id="starting_price"
                                            min="1" step="1"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.starting_price')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Buy Now Price -->
                                    <div>
                                        <label for="buy_now_price" class="block text-sm font-medium text-slate-700">Kupi
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
                                            class="block text-sm font-medium text-slate-700">Trenutna cena (RSD)</label>
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
                                            class="block text-sm font-medium text-slate-700">Status</label>
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
                                            class="block text-sm font-medium text-slate-700">Počinje</label>
                                        <input type="datetime-local" wire:model="editState.starts_at" id="starts_at"
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('editState.starts_at')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Ends At -->
                                    <div>
                                        <label for="ends_at"
                                            class="block text-sm font-medium text-slate-700">Završava</label>
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
