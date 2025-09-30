<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Upravljanje oglasima</h1>
        <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje svim oglasima u sistemu</p>
    </div>

    <!-- Filteri -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Pretraga -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Pretraga</label>
                <input type="text" wire:model.live="search" placeholder="Pretraži oglase..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Status</label>
                <select wire:model.live="filters.status" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi statusi</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategorija -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Kategorija</label>
                <select wire:model.live="filters.category_id"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Sve kategorije</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Istaknuti -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Istaknuti</label>
                <select wire:model.live="filters.is_featured"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi oglasi</option>
                    <option value="1">Istaknuti</option>
                    <option value="0">Neistaknuti</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Pronađeno: {{ $listings->total() }} oglasa
            </div>
            <div>
                <button wire:click="resetFilters"
                    class="px-3 py-1 text-sm text-slate-600 border border-slate-300 rounded hover:bg-slate-50">
                    Resetuj filtere
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Tabela oglasa -->
    <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('title')">
                            Oglas
                            @if ($sortField === 'title')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Kategorija</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('price')">
                            Cena
                            @if ($sortField === 'price')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Korisnik</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            Datum
                            @if ($sortField === 'created_at')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($listings as $listing)
                        <tr class="hover:bg-slate-50 border-t border-slate-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($listing->images->count() > 0)
                                        <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                            class="w-12 h-12 object-cover rounded-md mr-3">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-slate-200 rounded-md mr-3 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ Str::limit($listing->title, 40) }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ Str::limit($listing->location, 20) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $listing->category->name }}</div>
                                @if ($listing->subcategory)
                                    <div class="text-xs text-slate-500 dark:text-slate-300">
                                        {{ $listing->subcategory->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-green-600">
                                    {{ number_format($listing->price, 2) }} RSD
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $listing->user->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">{{ $listing->user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full 
                                @if ($listing->status === 'active') bg-green-100 text-green-800
                                @elseif($listing->status === 'sold') bg-sky-100 text-sky-800
                                @elseif($listing->status === 'expired') bg-red-100 text-red-800
                                @else bg-slate-100 text-slate-800 @endif">
                                    {{ $statusOptions[$listing->status] }}
                                </span>
                                @if ($listing->is_featured)
                                    <span
                                        class="ml-1 px-2 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded-full">Istaknut</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $listing->created_at->format('d.m.Y.') }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">
                                    {{ $listing->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                        <!-- Actions Row -->
                        <tr class="border-t border-slate-200">
                            <td colspan="6" class="px-4 py-2 bg-slate-50">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('listings.show', $listing) }}" target="_blank"
                                        class="inline-flex items-center px-2 py-1 text-indigo-600 hover:text-indigo-800 rounded">
                                        <i class="fas fa-eye mr-1"></i> Pregled
                                    </a>

                                    <button wire:click="editListing({{ $listing->id }})"
                                        class="inline-flex items-center px-2 py-1 text-sky-600 hover:text-sky-800 rounded">
                                        <i class="fas fa-edit mr-1"></i> Izmeni
                                    </button>

                                    <button wire:click="toggleFeatured({{ $listing->id }})"
                                        class="inline-flex items-center px-2 py-1 text-amber-600 hover:text-amber-800 rounded">
                                        <i class="fas fa-star mr-1"></i> {{ $listing->is_featured ? 'Ukloni isticanje' : 'Istakni' }}
                                    </button>

                                    <!-- Status promene -->
                                    @foreach (['active', 'inactive', 'sold'] as $status)
                                        @if ($listing->status !== $status)
                                            <button
                                                wire:click="updateStatus({{ $listing->id }}, '{{ $status }}')"
                                                class="inline-flex items-center px-2 py-1 text-slate-600 hover:text-slate-800 rounded">
                                                <i class="fas fa-circle mr-1 text-xs"></i> {{ $statusOptions[$status] }}
                                            </button>
                                        @endif
                                    @endforeach

                                    <button wire:click="confirmDelete({{ $listing->id }})"
                                        class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-800 rounded">
                                        <i class="fas fa-trash mr-1"></i> Obriši
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $listings->links() }}
        </div>
    </div>

    <!-- Mobile Listings Cards -->
    <div class="lg:hidden space-y-4">
        @forelse($listings as $listing)
            <div class="bg-white shadow rounded-lg p-4">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="text-lg font-semibold text-slate-900 mb-2">{{ Str::limit($listing->title, 40) }}
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-slate-500 dark:text-slate-300">
                            <span><i class="fas fa-tag mr-1"></i>{{ $listing->category->name }}</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $listing->location }}</span>
                        </div>
                        <div class="mt-2">
                            <span
                                class="text-lg font-bold text-green-600">{{ number_format($listing->price, 0, ',', '.') }}
                                RSD</span>
                        </div>
                    </div>

                    <!-- Image -->
                    @if ($listing->images->count() > 0)
                        <div class="flex-shrink-0 ml-4">
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ $listing->images->first()->url }}"
                                alt="{{ $listing->title }}">
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="bg-slate-50 p-3 rounded-lg mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Korisnik</div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            @if ($listing->user->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $listing->user->avatar_url }}"
                                    alt="{{ $listing->user->name }}">
                            @else
                                <div
                                    class="h-8 w-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-xs">
                                    {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-slate-900">{{ $listing->user->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ $listing->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Status and Date Info -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Status</div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($listing->status === 'active') bg-green-100 text-green-800
                            @elseif($listing->status === 'pending') bg-amber-100 text-amber-800
                            @elseif($listing->status === 'expired') bg-red-100 text-red-800
                            @else bg-slate-100 text-slate-800 @endif">
                            {{ ucfirst($listing->status) }}
                        </span>
                    </div>

                    <div>
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Datum</div>
                        <div class="text-sm text-slate-900">{{ $listing->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="viewListing({{ $listing->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Pregled
                    </button>

                    <button wire:click="editListing({{ $listing->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Uredi
                    </button>

                    @if ($listing->status === 'pending')
                        <button wire:click="approveListing({{ $listing->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-check mr-1"></i>
                            Odobri
                        </button>
                    @endif

                    @if ($listing->status === 'active')
                        <button wire:click="blockListing({{ $listing->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                            <i class="fas fa-ban mr-1"></i>
                            Blokiraj
                        </button>
                    @endif

                    <button wire:click="deleteListing({{ $listing->id }})"
                        wire:confirm="Da li ste sigurni da želite da obrišete ovaj oglas?"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Obriši
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-list-alt text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema oglasa</h3>
                <p class="text-slate-600 dark:text-slate-400">Nema oglasa koji odgovaraju kriterijumima pretrage.</p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-6">
            {{ $listings->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Izmeni oglas</h3>

                    <form wire:submit.prevent="updateListing">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Leva kolona -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Naslov
                                        *</label>
                                    <input type="text" wire:model="editState.title"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                    @error('editState.title')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                        (RSD) *</label>
                                    <input type="number" wire:model="editState.price" step="0.01" min="0"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                    @error('editState.price')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kategorija
                                        *</label>
                                    <select wire:model="editState.category_id"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                        <option value="">Izaberi kategoriju</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editState.category_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Podkategorija</label>
                                    <select wire:model="editState.subcategory_id"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                        <option value="">Izaberi podkategoriju</option>
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Stanje
                                        *</label>
                                    <select wire:model="editState.condition_id"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                        <option value="">Izaberi stanje</option>
                                        @foreach ($conditions as $condition)
                                            <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editState.condition_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Desna kolona -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status
                                        *</label>
                                    <select wire:model="editState.status"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                        @foreach ($statusOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('editState.status')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Lokacija
                                        *</label>
                                    <input type="text" wire:model="editState.location"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                    @error('editState.location')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kontakt
                                        telefon</label>
                                    <input type="text" wire:model="editState.contact_phone"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Opis
                                        *</label>
                                    <textarea wire:model="editState.description" rows="4"
                                        class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2"></textarea>
                                    @error('editState.description')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="editState.is_featured"
                                        class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                    <label class="ml-2 text-sm text-slate-600 dark:text-slate-400">Istaknuti
                                        oglas</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
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
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Potvrda brisanja</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
                        Da li ste sigurni da želite da obrišete oglas "{{ $selectedListing->title }}"?
                        <br>Ova akcija je nepovratna.
                    </p>

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Otkaži
                        </button>
                        <button wire:click="deleteListing"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Obriši
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
