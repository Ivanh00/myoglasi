<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Upravljanje aukcijama</h1>
        <p class="mt-2 text-sm text-gray-600">Pregled i upravljanje svim aukcijama u sistemu</p>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Pretraga</label>
                <input type="text" wire:model.live="search" id="search" 
                    placeholder="Naslov, opis ili prodavac..." 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status-filter" class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model.live="filters.status" id="status-filter" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Svi statusi</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bids Filter -->
            <div>
                <label for="bids-filter" class="block text-sm font-medium text-gray-700">Ponude</label>
                <select wire:model.live="filters.has_bids" id="bids-filter" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Sve aukcije</option>
                    <option value="1">Sa ponudama</option>
                    <option value="0">Bez ponuda</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label for="per-page" class="block text-sm font-medium text-gray-700">Po strani</label>
                <select wire:model.live="perPage" id="per-page" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Ukupno aukcija: {{ $auctions->total() }}
            </div>
            <button wire:click="resetFilters" 
                class="text-sm text-indigo-600 hover:text-indigo-900">
                Resetuj filtere
            </button>
        </div>
    </div>

    <!-- Auctions Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        ID
                        @if($sortField === 'id')
                            <span class="text-indigo-500">
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            </span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Oglas
                    </th>
                    <th wire:click="sortBy('current_price')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        Cene
                        @if($sortField === 'current_price')
                            <span class="text-indigo-500">
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            </span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ponude
                    </th>
                    <th wire:click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        Status
                        @if($sortField === 'status')
                            <span class="text-indigo-500">
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            </span>
                        @endif
                    </th>
                    <th wire:click="sortBy('ends_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        Završava
                        @if($sortField === 'ends_at')
                            <span class="text-indigo-500">
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            </span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Prodavac
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Akcije
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($auctions as $auction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $auction->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($auction->listing->images->count() > 0)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                            src="{{ $auction->listing->images->first()->url }}" 
                                            alt="{{ $auction->listing->title }}">
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
                                    <div class="text-sm text-gray-500">
                                        {{ $auction->listing->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div>Trenutna: <strong>{{ number_format($auction->current_price, 0, ',', '.') }} RSD</strong></div>
                                <div class="text-gray-500">Početna: {{ number_format($auction->starting_price, 0, ',', '.') }} RSD</div>
                                @if($auction->buy_now_price)
                                    <div class="text-green-600">Kupi odmah: {{ number_format($auction->buy_now_price, 0, ',', '.') }} RSD</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $auction->total_bids }} ponuda</div>
                            @if($auction->winner)
                                <div class="text-sm text-green-600">Pobednik: {{ $auction->winner->name }}</div>
                            @elseif($auction->total_bids > 0)
                                @php
                                    $winningBid = $auction->bids()->where('is_winning', true)->first();
                                @endphp
                                @if($winningBid)
                                    <div class="text-sm text-blue-600">Vodi: {{ $winningBid->user->name }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($auction->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Na čekanju
                                    </span>
                                    @break
                                @case('active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktivna
                                    </span>
                                    @break
                                @case('ended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Završena
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Otkazana
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $auction->ends_at->format('d.m.Y H:i') }}
                            @if($auction->isActive())
                                <div class="text-xs text-gray-500">
                                    @php
                                        $timeLeft = $auction->time_left;
                                    @endphp
                                    @if($timeLeft)
                                        {{ $timeLeft['formatted'] }} ostalo
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $auction->seller->name }}</div>
                            <div class="text-sm text-gray-500">{{ $auction->seller->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('auction.show', $auction) }}" target="_blank"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button wire:click="editAuction({{ $auction->id }})" 
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($auction->isActive())
                                    <button x-data
                                        x-on:click.prevent="if (confirm('Da li ste sigurni da želite da završite ovu aukciju?')) { $wire.endAuction({{ $auction->id }}) }"
                                        class="text-orange-600 hover:text-orange-900">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                @endif
                                <button wire:click="confirmDelete({{ $auction->id }})" 
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
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
    <x-modal wire:model="showEditModal" name="edit-auction" max-width="2xl">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Uredi aukciju #{{ $selectedAuction->id ?? '' }}
            </h3>

            @if($selectedAuction)
                <form wire:submit="updateAuction">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Starting Price -->
                        <div>
                            <label for="starting_price" class="block text-sm font-medium text-gray-700">Početna cena (RSD)</label>
                            <input type="number" wire:model="editState.starting_price" id="starting_price" min="1" step="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('editState.starting_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Buy Now Price -->
                        <div>
                            <label for="buy_now_price" class="block text-sm font-medium text-gray-700">Kupi odmah cena (RSD)</label>
                            <input type="number" wire:model="editState.buy_now_price" id="buy_now_price" min="1" step="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('editState.buy_now_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Current Price -->
                        <div>
                            <label for="current_price" class="block text-sm font-medium text-gray-700">Trenutna cena (RSD)</label>
                            <input type="number" wire:model="editState.current_price" id="current_price" min="1" step="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('editState.current_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="editState.status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($statusOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('editState.status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Starts At -->
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700">Počinje</label>
                            <input type="datetime-local" wire:model="editState.starts_at" id="starts_at"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('editState.starts_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ends At -->
                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700">Završava</label>
                            <input type="datetime-local" wire:model="editState.ends_at" id="ends_at"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('editState.ends_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showEditModal', false)"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Otkaži
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Sačuvaj izmene
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal wire:model="showDeleteModal" name="delete-auction">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Potvrdi brisanje aukcije
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Da li ste sigurni da želite da obrišete ovu aukciju? Ova akcija se ne može poništiti.
                @if($selectedAuction && $selectedAuction->total_bids > 0)
                    <br><strong class="text-red-600">Upozorenje: Ova aukcija ima {{ $selectedAuction->total_bids }} ponuda!</strong>
                @endif
            </p>
            <div class="flex items-center justify-end space-x-3">
                <button wire:click="$set('showDeleteModal', false)"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Otkaži
                </button>
                <button wire:click="deleteAuction"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Obriši aukciju
                </button>
            </div>
        </div>
    </x-modal>
</div>