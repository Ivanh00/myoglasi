<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Upravljanje biznisom</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Pregled i upravljanje svim business oglasima u sistemu</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Pretraži</label>
                <input type="text" wire:model.live="search"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-sky-500 dark:bg-slate-700 dark:text-slate-200"
                    placeholder="Pretraži business...">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Status</label>
                <select wire:model.live="filters.status"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-sky-500 dark:bg-slate-700 dark:text-slate-200">
                    <option value="">Svi statusi</option>
                    <option value="active">Aktivni</option>
                    <option value="inactive">Neaktivni</option>
                    <option value="expired">Istekli</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Kategorija</label>
                <select wire:model.live="filters.category"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-sky-500 dark:bg-slate-700 dark:text-slate-200">
                    <option value="">Sve kategorije</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Po stranici</label>
                <select wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-sky-500 dark:bg-slate-700 dark:text-slate-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Pronađeno: {{ $businesses->total() }} business oglasa
            </div>
            <button wire:click="resetFilters"
                class="px-3 py-1 text-sm text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-700">
                Resetuj filtere
            </button>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-700">
                <tr>
                    <th wire:click="sortBy('name')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-600">
                        Naziv
                        @if ($sortField === 'name')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Korisnik
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Kategorija
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Lokacija
                    </th>
                    <th wire:click="sortBy('views')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-600">
                        Pregledi
                        @if ($sortField === 'views')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('created_at')"
                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-600">
                        Datum
                        @if ($sortField === 'created_at')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800">
                @forelse ($businesses as $business)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 border-t border-slate-200 dark:border-slate-700">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if ($business->logo)
                                    <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}"
                                        class="h-10 w-10 rounded-lg object-cover mr-3">
                                @else
                                    <div
                                        class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-briefcase text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ Str::limit($business->name, 30) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-slate-100">{{ $business->user->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $business->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-slate-100">
                                {{ $business->category->name ?? 'N/A' }}
                            </div>
                            @if ($business->subcategory)
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $business->subcategory->name }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                            {{ $business->location }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                            {{ $business->views }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                            {{ $business->created_at->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-1">
                                @if ($business->status === 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 w-fit">
                                        Aktivan
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 w-fit">
                                        {{ ucfirst($business->status) }}
                                    </span>
                                @endif

                                @if ($business->is_from_business_plan)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 w-fit">
                                        <i class="fas fa-briefcase mr-1"></i>Biznis plan
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-200 dark:bg-orange-800 text-orange-800 dark:text-orange-200 w-fit">
                                        <i class="fas fa-credit-card mr-1"></i>Plaćen
                                    </span>
                                @endif

                                @if ($business->expires_at)
                                    <span class="text-xs text-slate-600 dark:text-slate-400">
                                        <i class="fas fa-calendar-times mr-1"></i>Ističe: {{ $business->expires_at->format('d.m.Y') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <!-- Actions Row -->
                    <tr class="border-t border-slate-200 dark:border-slate-700">
                        <td colspan="7" class="px-4 py-2 bg-slate-50 dark:bg-slate-700/50">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('businesses.show', $business->slug) }}" target="_blank"
                                    class="inline-flex items-center px-2 py-1 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>

                                <button wire:click="editBusiness({{ $business->id }})"
                                    class="inline-flex items-center px-2 py-1 text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300 rounded">
                                    <i class="fas fa-edit mr-1"></i> Uredi
                                </button>

                                <button wire:click="toggleStatus({{ $business->id }})"
                                    class="inline-flex items-center px-2 py-1 text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 rounded">
                                    <i class="fas fa-toggle-on mr-1"></i> Promeni status
                                </button>

                                <button wire:click="deleteBusiness({{ $business->id }})"
                                    wire:confirm="Da li ste sigurni da želite da obrišete ovaj business?"
                                    class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 rounded">
                                    <i class="fas fa-trash mr-1"></i> Obriši
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            Nema business oglasa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Desktop Pagination -->
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $businesses->links() }}
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="lg:hidden space-y-4">
        @forelse($businesses as $business)
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-4">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            {{ Str::limit($business->name, 40) }}
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-slate-500 dark:text-slate-300">
                            <span><i class="fas fa-folder mr-1"></i>{{ $business->category->name ?? 'N/A' }}</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($business->location, 20) }}</span>
                        </div>
                    </div>

                    <!-- Logo -->
                    @if ($business->logo)
                        <div class="flex-shrink-0 ml-4">
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ Storage::url($business->logo) }}"
                                alt="{{ $business->name }}">
                        </div>
                    @else
                        <div class="flex-shrink-0 ml-4">
                            <div class="h-16 w-16 rounded-lg bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                <i class="fas fa-briefcase text-purple-600 dark:text-purple-400 text-2xl"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Korisnik</div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            @if ($business->user->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $business->user->avatar_url }}"
                                    alt="{{ $business->user->name }}">
                            @else
                                <div
                                    class="h-8 w-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-xs">
                                    {{ strtoupper(substr($business->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $business->user->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ $business->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Status and Date Info -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Status</div>
                        <div class="flex flex-col space-y-1">
                            @if ($business->status === 'active')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 w-fit">
                                    Aktivan
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 w-fit">
                                    {{ ucfirst($business->status) }}
                                </span>
                            @endif

                            @if ($business->is_from_business_plan)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 w-fit">
                                    <i class="fas fa-briefcase mr-1"></i>Biznis plan
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-200 dark:bg-orange-800 text-orange-800 dark:text-orange-200 w-fit">
                                    <i class="fas fa-credit-card mr-1"></i>Plaćen
                                </span>
                            @endif

                            @if ($business->expires_at)
                                <span class="text-xs text-slate-600 dark:text-slate-400">
                                    <i class="fas fa-calendar-times mr-1"></i>Ističe: {{ $business->expires_at->format('d.m.Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Pregledi</div>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $business->views }}</div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Datum</div>
                    <div class="text-sm text-slate-900 dark:text-slate-100">{{ $business->created_at->format('d.m.Y H:i') }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('businesses.show', $business->slug) }}" target="_blank"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 text-xs font-medium rounded-lg hover:bg-indigo-200 dark:hover:bg-indigo-900/50 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Pregled
                    </a>

                    <button wire:click="editBusiness({{ $business->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300 text-xs font-medium rounded-lg hover:bg-sky-200 dark:hover:bg-sky-900/50 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Uredi
                    </button>

                    <button wire:click="toggleStatus({{ $business->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 text-xs font-medium rounded-lg hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors">
                        <i class="fas fa-toggle-on mr-1"></i>
                        Promeni status
                    </button>

                    <button wire:click="deleteBusiness({{ $business->id }})"
                        wire:confirm="Da li ste sigurni da želite da obrišete ovaj business?"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 text-xs font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Obriši
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-8 text-center">
                <i class="fas fa-briefcase text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-2">Nema business oglasa</h3>
                <p class="text-slate-600 dark:text-slate-400">Nema business oglasa koji odgovaraju filterima.</p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-6">
            {{ $businesses->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-slate-800">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">
                        Uredi business: {{ $selectedBusiness->name ?? '' }}
                    </h3>

                    @if ($selectedBusiness)
                        <form wire:submit.prevent="updateBusiness">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Naziv -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Naziv *</label>
                                    <input type="text" wire:model="editState.name"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Slogan -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Slogan</label>
                                    <input type="text" wire:model="editState.slogan"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                </div>

                                <!-- Kategorija -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kategorija *</label>
                                    <select wire:model.live="editState.business_category_id"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                        <option value="">Izaberi kategoriju</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editState.business_category_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Podkategorija -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Podkategorija</label>
                                    <select wire:model="editState.subcategory_id"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                        <option value="">Bez podkategorije</option>
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status *</label>
                                    <select wire:model="editState.status"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                        <option value="active">Aktivan</option>
                                        <option value="inactive">Neaktivan</option>
                                        <option value="expired">Istekao</option>
                                    </select>
                                    @error('editState.status')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Godina osnivanja -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Godina osnivanja</label>
                                    <input type="number" wire:model="editState.established_year" min="1900" max="{{ date('Y') }}"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.established_year')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Adresa 1 -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Adresa 1</label>
                                    <input type="text" wire:model="editState.address_1" placeholder="Ulica i broj"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.address_1')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Adresa 2 -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Adresa 2</label>
                                    <input type="text" wire:model="editState.address_2" placeholder="Dodatne informacije o adresi"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.address_2')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Kontakt ime 2 -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kontakt ime 2</label>
                                    <input type="text" wire:model="editState.contact_name_2"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.contact_name_2')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Kontakt telefon 2 -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kontakt telefon 2</label>
                                    <input type="text" wire:model="editState.contact_phone_2"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.contact_phone_2')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Kontakt ime 3 -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kontakt ime 3</label>
                                    <input type="text" wire:model="editState.contact_name_3"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.contact_name_3')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Kontakt telefon 3 -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kontakt telefon 3</label>
                                    <input type="text" wire:model="editState.contact_phone_3"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                    @error('editState.contact_phone_3')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Website URL -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Website URL</label>
                                    <input type="url" wire:model="editState.website_url"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                </div>

                                <!-- Facebook URL -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Facebook URL</label>
                                    <input type="url" wire:model="editState.facebook_url"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                </div>

                                <!-- Instagram URL -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Instagram URL</label>
                                    <input type="url" wire:model="editState.instagram_url"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200">
                                </div>

                                <!-- Opis - full width -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Opis *</label>
                                    <textarea wire:model="editState.description" rows="4"
                                        class="mt-1 block w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 dark:bg-slate-700 dark:text-slate-200"></textarea>
                                    @error('editState.description')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
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
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
