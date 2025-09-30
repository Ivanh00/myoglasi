<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Upravljanje uslugama</h1>
        <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje svim uslugama u sistemu</p>
    </div>

    <!-- Filteri -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <!-- Pretraga -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Pretraga</label>
                <input type="text" wire:model.live="search" placeholder="Pretraži usluge..."
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                <select wire:model.live="filters.status"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg">
                    <option value="">Svi statusi</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategorija -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Kategorija</label>
                <select wire:model.live="filters.service_category_id"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg">
                    <option value="">Sve kategorije</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Dugmad -->
        <div class="flex justify-between items-center">
            <div class="flex space-x-2">
                <select wire:model.live="perPage"
                    class="px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg">
                    <option value="10">10 po stranici</option>
                    <option value="25">25 po stranici</option>
                    <option value="50">50 po stranici</option>
                </select>
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Ukupno usluga: {{ $services->total() }}
            </div>
        </div>
    </div>

    <!-- Desktop Tabela usluga -->
    <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('title')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider hover:text-slate-700 dark:hover:text-slate-200">
                                <span>Naslov</span>
                                @if ($sortField === 'title')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Pružalac usluge</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('price')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider hover:text-slate-700 dark:hover:text-slate-200">
                                <span>Cena</span>
                                @if ($sortField === 'price')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Kategorija</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider hover:text-slate-700 dark:hover:text-slate-200">
                                <span>Kreirana</span>
                                @if ($sortField === 'created_at')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($services as $service)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 border-t border-slate-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($service->images->count() > 0)
                                        <img src="{{ $service->images->first()->url }}" alt="{{ $service->title }}"
                                            class="w-12 h-12 rounded-lg object-cover mr-4">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-lg bg-slate-200 dark:bg-slate-600 flex items-center justify-center mr-4">
                                            <i class="fas fa-tools text-slate-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                            {{ Str::limit($service->title, 50) }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-300">{{ $service->location }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($service->user->avatar)
                                        <img src="{{ $service->user->avatar_url }}" alt="{{ $service->user->name }}"
                                            class="w-8 h-8 rounded-full object-cover mr-3">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm mr-3">
                                            {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                            {{ $service->user->name }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-300">
                                            {{ $service->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                    {{ number_format($service->price, 2) }} RSD</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $service->category->name ?? 'N/A' }}</div>
                                @if ($service->subcategory)
                                    <div class="text-xs text-slate-500 dark:text-slate-300">
                                        {{ $service->subcategory->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @switch($service->status)
                                    @case('active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktivan
                                        </span>
                                    @break

                                    @case('inactive')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            Neaktivan
                                        </span>
                                    @break

                                    @case('expired')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Istekao
                                        </span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-500 dark:text-slate-300">
                                    {{ $service->created_at->format('d.m.Y') }}</div>
                            </td>
                        </tr>
                        <!-- Actions Row -->
                        <tr class="border-t border-slate-200">
                            <td colspan="6" class="px-4 py-2 bg-slate-50">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('services.show', $service) }}" target="_blank"
                                        class="inline-flex items-center px-2 py-1 text-sky-600 hover:text-sky-800 rounded">
                                        <i class="fas fa-eye mr-1"></i> Pregled
                                    </a>

                                    <button wire:click="editService({{ $service->id }})"
                                        class="inline-flex items-center px-2 py-1 text-indigo-600 hover:text-indigo-800 rounded">
                                        <i class="fas fa-edit mr-1"></i> Uredi
                                    </button>

                                    @if ($service->status === 'active')
                                        <button wire:click="deactivateService({{ $service->id }})"
                                            class="inline-flex items-center px-2 py-1 text-orange-600 hover:text-orange-800 rounded">
                                            <i class="fas fa-ban mr-1"></i> Deaktiviraj
                                        </button>
                                    @else
                                        <button wire:click="activateService({{ $service->id }})"
                                            class="inline-flex items-center px-2 py-1 text-green-600 hover:text-green-800 rounded">
                                            <i class="fas fa-check-circle mr-1"></i> Aktiviraj
                                        </button>
                                    @endif

                                    <button wire:click="confirmDeleteService({{ $service->id }})"
                                        class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-800 rounded">
                                        <i class="fas fa-trash mr-1"></i> Obriši
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-300">
                                    Nema usluga koji odgovaraju kriterijumima pretrage.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-600">
                {{ $services->links() }}
            </div>
        </div>

    <!-- Mobile Services Cards -->
    <div class="lg:hidden space-y-4">
        @forelse($services as $service)
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-4">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">{{ Str::limit($service->title, 40) }}
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-slate-500 dark:text-slate-300">
                            <span><i class="fas fa-tools mr-1"></i>{{ $service->category->name ?? 'N/A' }}</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $service->location }}</span>
                        </div>
                        <div class="mt-2">
                            <span
                                class="text-lg font-bold text-green-600">{{ number_format($service->price, 2) }}
                                RSD</span>
                        </div>
                    </div>

                    <!-- Image -->
                    @if ($service->images->count() > 0)
                        <div class="flex-shrink-0 ml-4">
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ $service->images->first()->url }}"
                                alt="{{ $service->title }}">
                        </div>
                    @else
                        <div class="flex-shrink-0 ml-4">
                            <div class="h-16 w-16 rounded-lg bg-slate-200 dark:bg-slate-600 flex items-center justify-center">
                                <i class="fas fa-tools text-slate-400"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Pružalac usluge</div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            @if ($service->user->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $service->user->avatar_url }}"
                                    alt="{{ $service->user->name }}">
                            @else
                                <div
                                    class="h-8 w-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-xs">
                                    {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $service->user->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ $service->user->email }}</div>
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
                            @if ($service->status === 'active') bg-green-100 text-green-800
                            @elseif($service->status === 'inactive') bg-slate-100 text-slate-800
                            @elseif($service->status === 'expired') bg-red-100 text-red-800 @endif">
                            @switch($service->status)
                                @case('active') Aktivan @break
                                @case('inactive') Neaktivan @break
                                @case('expired') Istekao @break
                                @default {{ ucfirst($service->status) }}
                            @endswitch
                        </span>
                    </div>

                    <div>
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Datum</div>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $service->created_at->format('d.m.Y') }}</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('services.show', $service) }}" target="_blank"
                        class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Pregled
                    </a>

                    <button wire:click="editService({{ $service->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Uredi
                    </button>

                    @if ($service->status === 'active')
                        <button wire:click="deactivateService({{ $service->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                            <i class="fas fa-ban mr-1"></i>
                            Deaktiviraj
                        </button>
                    @else
                        <button wire:click="activateService({{ $service->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aktiviraj
                        </button>
                    @endif

                    <button wire:click="confirmDeleteService({{ $service->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Obriši
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-8 text-center">
                <i class="fas fa-tools text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-2">Nema usluga</h3>
                <p class="text-slate-600 dark:text-slate-400">Nema usluga koji odgovaraju kriterijumima pretrage.</p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>

        <!-- Edit Service Modal -->
        @if ($showEditModal)
            <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div
                    class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-slate-800">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Uredi uslugu</h3>
                            <button wire:click="closeModals"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form wire:submit="updateService">
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Naslov</label>
                                    <input type="text" wire:model="editState.title"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md">
                                    @error('editState.title')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Opis</label>
                                    <textarea wire:model="editState.description" rows="4"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md"></textarea>
                                    @error('editState.description')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                            (RSD)</label>
                                        <input type="number" step="0.01" wire:model="editState.price"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md">
                                        @error('editState.price')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
                                        <select wire:model="editState.status"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md">
                                            @foreach ($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('editState.status')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kategorija</label>
                                    <select wire:model="editState.service_category_id"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md">
                                        <option value="">Odaberite kategoriju</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editState.service_category_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200">Lokacija</label>
                                    <input type="text" wire:model="editState.location"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md">
                                    @error('editState.location')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2 mt-6">
                                <button type="button" wire:click="closeModals"
                                    class="px-4 py-2 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700">
                                    Otkaži
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                    Sačuvaj
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
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-slate-800">
                    <div class="mt-3 text-center">
                        <div
                            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mt-2">Obriši uslugu</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-slate-500 dark:text-slate-300">
                                Da li ste sigurni da želite da obrišete uslugu "{{ $selectedService?->title }}"?
                                Ova akcija ne može biti poništena.
                            </p>
                        </div>
                        <div class="flex justify-center space-x-2 mt-4">
                            <button wire:click="closeModals"
                                class="px-4 py-2 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700">
                                Otkaži
                            </button>
                            <button wire:click="deleteService"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Obriši
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
