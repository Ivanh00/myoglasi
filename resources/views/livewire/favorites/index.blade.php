<div class="max-w-7xl mx-auto py-6 px-1 sm:px-6 lg:px-8">
    <!-- Filters and sorting row -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <!-- Count -->
        <div class="text-slate-600 dark:text-slate-300">
            Ukupno: <span class="font-semibold">{{ $favorites->total() }}</span> omiljenih stavki
        </div>

        <!-- Filter tabs -->
        <div class="flex rounded-lg overflow-hidden border border-slate-300 dark:border-slate-600">
            <button wire:click="setFilterType('all')"
                class="px-4 py-2 flex items-center gap-2 transition-colors font-medium text-sm
                {{ $filterType === 'all' ? 'bg-sky-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                Sve
            </button>
            <button wire:click="setFilterType('listings')"
                class="px-4 py-2 flex items-center gap-2 transition-colors font-medium text-sm border-l border-slate-300 dark:border-slate-600
                {{ $filterType === 'listings' ? 'bg-sky-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14-8H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
                </svg>
                Oglasi
            </button>
            <button wire:click="setFilterType('services')"
                class="px-4 py-2 flex items-center gap-2 transition-colors font-medium text-sm border-l border-slate-300 dark:border-slate-600
                {{ $filterType === 'services' ? 'bg-slate-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                <i class="fas fa-tools w-4"></i>
                Usluge
            </button>
            <button wire:click="setFilterType('businesses')"
                class="px-4 py-2 flex items-center gap-2 transition-colors font-medium text-sm border-l border-slate-300 dark:border-slate-600
                {{ $filterType === 'businesses' ? 'bg-purple-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                <i class="fas fa-briefcase w-4"></i>
                Biznis kartice
            </button>
        </div>

        <!-- Sortiranje -->
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Sortiraj po:</label>
            <div class="w-60" x-data="{ open: false }" x-init="open = false">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-sm text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-sky-500 dark:focus:border-sky-400 transition-colors flex items-center justify-between">
                        <span>
                            @switch($sortBy)
                                @case('oldest')
                                    Najstarije dodato
                                @break

                                @case('price_asc')
                                    Cena ↑
                                @break

                                @case('price_desc')
                                    Cena ↓
                                @break

                                @default
                                    Najnovije dodato
                            @endswitch
                        </span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg">
                        <button @click="$wire.set('sortBy', 'newest'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-t-lg {{ $sortBy === 'newest' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Najnovije dodato
                        </button>
                        <button @click="$wire.set('sortBy', 'oldest'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $sortBy === 'oldest' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Najstarije dodato
                        </button>
                        <button @click="$wire.set('sortBy', 'price_asc'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 {{ $sortBy === 'price_asc' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Cena ↑
                        </button>
                        <button @click="$wire.set('sortBy', 'price_desc'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-b-lg {{ $sortBy === 'price_desc' ? 'bg-sky-50 dark:bg-slate-600 text-sky-700 dark:text-sky-300' : '' }}">
                            Cena ↓
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash poruke -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Desktop Lista -->
    @if ($favorites->count() > 0)
        <div class="hidden lg:block space-y-1">
            <!-- Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
                <div class="grid grid-cols-[35%_20%_20%_25%] bg-slate-50 dark:bg-slate-700">
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Stavka</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Cena</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Status</div>
                    <div
                        class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        Dodato</div>
                </div>
            </div>

            <!-- Data Rows -->
            @foreach ($favorites as $item)
                <div
                    class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden border-l-4 {{ $item->item_type === 'business' ? 'border-purple-500' : ($item->item_type === 'service' ? 'border-slate-500' : (method_exists($item, 'isGiveaway') && $item->isGiveaway() ? 'border-green-500' : 'border-sky-500')) }}">
                    <div class="grid grid-cols-[35%_20%_20%_25%] hover:bg-slate-50 dark:hover:bg-slate-700">
                        <!-- Stavka Column -->
                        <div class="px-4 py-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($item->images->count() > 0)
                                        <img class="h-10 w-10 rounded-lg object-cover"
                                            src="{{ $item->images->first()->url }}" alt="{{ $item->title }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-slate-200 flex items-center justify-center">
                                            <i
                                                class="fas {{ $item->item_type === 'service' ? 'fa-tools' : 'fa-image' }} text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100 break-words">
                                        {{ Str::limit($item->title, 40) }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300">
                                        @if ($item->item_type === 'business')
                                            {{ $item->category->name ?? 'Biznis' }}
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 ml-1">BIZNIS
                                                KARTICA</span>
                                        @elseif($item->item_type === 'service')
                                            {{ $item->category->name ?? 'Usluga' }}
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-600 text-slate-800 dark:text-slate-200 ml-1">USLUGA</span>
                                        @else
                                            {{ $item->category->name }}
                                            @if (method_exists($item, 'isGiveaway') && $item->isGiveaway())
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 ml-1">POKLON</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Cena Column -->
                        <div class="px-4 py-2">
                            <div class="text-sm font-bold whitespace-nowrap">
                                @if ($item->item_type === 'business')
                                    <span class="text-slate-500 dark:text-slate-400">-</span>
                                @elseif($item->item_type === 'service')
                                    <span class="text-slate-600 dark:text-slate-400">
                                        @if ($item->price_type === 'fixed')
                                            {{ number_format($item->price, 2) }} RSD
                                        @elseif($item->price_type === 'hourly')
                                            {{ number_format($item->price, 2) }} RSD/sat
                                        @elseif($item->price_type === 'daily')
                                            {{ number_format($item->price, 2) }} RSD/dan
                                        @elseif($item->price_type === 'per_m2')
                                            {{ number_format($item->price, 2) }} RSD/m²
                                        @else
                                            Po dogovoru
                                        @endif
                                    </span>
                                @elseif(method_exists($item, 'isGiveaway') && $item->isGiveaway())
                                    <span class="text-green-600 dark:text-green-400">BESPLATNO</span>
                                @else
                                    <span class="text-sky-600 dark:text-sky-400">{{ number_format($item->price, 2) }}
                                        RSD</span>
                                @endif
                            </div>
                        </div>
                        <!-- Status Column -->
                        <div class="px-4 py-2">
                            @if ($item->status === 'active')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200">
                                    Aktivan
                                </span>
                            @elseif($item->status === 'expired')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                                    Istekao
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </div>
                        <!-- Datum Column -->
                        <div class="px-4 py-2 text-sm text-slate-500 dark:text-slate-300">
                            {{ $item->pivot->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <!-- Actions Row -->
                    <div
                        class="border-t border-slate-200 dark:border-slate-600 px-4 py-2 bg-slate-50 dark:bg-slate-700/50">
                        <div class="flex flex-wrap gap-2">
                            @if ($item->item_type === 'business')
                                <a href="{{ route('businesses.show', $item) }}"
                                    class="inline-flex items-center px-2 py-1 text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>
                            @elseif($item->item_type === 'service')
                                <a href="{{ route('services.show', $item) }}"
                                    class="inline-flex items-center px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>
                            @else
                                <a href="{{ route('listings.show', $item) }}"
                                    class="inline-flex items-center px-2 py-1 text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 rounded">
                                    <i class="fas fa-eye mr-1"></i> Pregled
                                </a>
                            @endif

                            <button
                                onclick="shareItem('{{ $item->item_type === 'business' ? route('businesses.show', $item) : ($item->item_type === 'service' ? route('services.show', $item) : route('listings.show', $item)) }}', '{{ addslashes($item->title) }}', '{{ $item->item_type }}')"
                                class="inline-flex items-center px-2 py-1 text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded">
                                <i class="fas fa-share-alt mr-1"></i> Podeli
                            </button>

                            <button x-data
                                @click="$dispatch('open-remove-modal', { itemId: {{ $item->id }}, itemType: '{{ $item->item_type }}' })"
                                class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                <i class="fas fa-heart-broken mr-1"></i> Ukloni
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Paginacija -->
        <div class="hidden lg:block mt-6">
            {{ $favorites->links() }}
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($favorites as $item)
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($item->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover"
                                            src="{{ $item->images->first()->url }}" alt="{{ $item->title }}">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center">
                                            <i
                                                class="fas {{ $item->item_type === 'service' ? 'fa-tools' : 'fa-image' }} text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Item Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-1">
                                        {{ $item->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-1">
                                        @if ($item->item_type === 'business')
                                            {{ $item->category->name ?? 'Biznis' }}
                                        @elseif($item->item_type === 'service')
                                            {{ $item->category->name ?? 'Usluga' }}
                                        @else
                                            {{ $item->category->name }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400 mb-2">{{ $item->location }}</p>
                                    @if ($item->item_type !== 'business')
                                        <p
                                            class="text-xl font-bold {{ $item->item_type === 'service' ? 'text-slate-600 dark:text-slate-400' : 'text-sky-600 dark:text-sky-400' }}">
                                            @if ($item->item_type === 'service')
                                                @if ($item->price_type === 'fixed')
                                                    {{ number_format($item->price, 2) }} RSD
                                                @elseif($item->price_type === 'hourly')
                                                    {{ number_format($item->price, 2) }} RSD/sat
                                                @elseif($item->price_type === 'daily')
                                                    {{ number_format($item->price, 2) }} RSD/dan
                                                @elseif($item->price_type === 'per_m2')
                                                    {{ number_format($item->price, 2) }} RSD/m²
                                                @else
                                                    Po dogovoru
                                                @endif
                                            @else
                                                {{ number_format($item->price, 2) }} RSD
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Badge -->
                            @if ($item->status === 'active')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200">
                                    Aktivan
                                </span>
                            @elseif($item->status === 'expired')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                                    Istekao
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Date Added -->
                        <div class="mb-4">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Dodato u omiljene</div>
                            <div class="text-sm text-slate-900 dark:text-slate-100">
                                {{ $item->pivot->created_at->format('d.m.Y H:i') }}</div>
                        </div>

                        @if ($item->user)
                            <!-- Seller Info -->
                            <div class="mb-4">
                                <div
                                    class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    {{ $item->item_type === 'service' ? 'Pružalac usluge' : 'Prodavac' }}</div>
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $item->user->name }}
                                    @if ($item->user->is_banned)
                                        <span class="text-red-600 dark:text-red-400 text-xs ml-1">BLOKIRAN</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            @if ($item->item_type === 'business')
                                <a href="{{ route('businesses.show', $item) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-200 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Pregled
                                </a>
                            @elseif($item->item_type === 'service')
                                <a href="{{ route('services.show', $item) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Pregled
                                </a>
                            @else
                                <a href="{{ route('listings.show', $item) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Pregled
                                </a>
                            @endif

                            <button
                                onclick="shareItem('{{ $item->item_type === 'business' ? route('businesses.show', $item) : ($item->item_type === 'service' ? route('services.show', $item) : route('listings.show', $item)) }}', '{{ addslashes($item->title) }}', '{{ $item->item_type }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                                <i class="fas fa-share-alt mr-1"></i>
                                Podeli
                            </button>

                            <button x-data
                                @click="$dispatch('open-remove-modal', { itemId: {{ $item->id }}, itemType: '{{ $item->item_type }}' })"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-heart-broken mr-1"></i>
                                Ukloni
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Pagination -->
        <div class="lg:hidden mt-6">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-heart text-slate-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Nemate omiljenih stavki</h3>
            <p class="text-slate-600 dark:text-slate-300 mb-4">Počnite da čuvate oglase i usluge koje volite klikom na
                srce.</p>
            <a href="{{ route('home') }}"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                Pretraži oglase i usluge
            </a>
        </div>
    @endif

    <!-- Remove from Favorites Modal -->
    <div x-data="{
        showRemoveModal: false,
        selectedItem: null,
        removeFromFavorites() {
            if (this.selectedItem) {
                @this.removeFromFavorites(this.selectedItem.id, this.selectedItem.type);
                this.showRemoveModal = false;
            }
        }
    }"
        @open-remove-modal.window="
            showRemoveModal = true;
            selectedItem = {
                id: $event.detail.itemId,
                type: $event.detail.itemType,
                data: $favorites.find(f => f.id === $event.detail.itemId && f.item_type === $event.detail.itemType)
            };
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

                <!-- Modal header with heart-broken icon -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-heart-broken text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Uklanjanje iz omiljenih</h3>
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
                            <span x-text="selectedItem?.type === 'service' ? 'Ova usluga' : 'Ovaj oglas'"></span>
                            će biti uklonjen iz vaše liste omiljenih stavki.
                        </p>
                    </div>

                    <!-- Item info -->
                    <template x-if="selectedItem?.data">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-start">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Naziv:</span>
                                    <span
                                        class="text-sm font-medium text-slate-900 dark:text-slate-100 text-right ml-2"
                                        x-text="selectedItem?.data?.title || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Kategorija:</span>
                                    <span
                                        class="text-sm font-medium text-slate-900 dark:text-slate-100 text-right ml-2"
                                        x-text="selectedItem?.data?.category?.name || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Tip:</span>
                                    <span class="text-sm font-medium"
                                        :class="{
                                            'text-slate-600 dark:text-slate-400': selectedItem?.type === 'service',
                                            'text-sky-600 dark:text-sky-400': selectedItem?.type !== 'service'
                                        }"
                                        x-text="selectedItem?.type === 'service' ? 'Usluga' : 'Oglas'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Dodato:</span>
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"
                                        x-text="selectedItem?.data?.pivot?.created_at ? new Date(selectedItem.data.pivot.created_at).toLocaleDateString('sr-RS') : 'N/A'"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Info notice -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    <strong>Napomena:</strong> Možete ponovo dodati ovu stavku u omiljene klikom na srce
                                    na stranici
                                    <span x-text="selectedItem?.type === 'service' ? 'usluge' : 'oglasa'"></span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
                    <div class="flex space-x-3">
                        <button type="button" @click="showRemoveModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button" @click="removeFromFavorites()"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                            <i class="fas fa-heart-broken mr-2"></i>
                            Ukloni iz omiljenih
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.$favorites = @json($favorites->items());

        function shareItem(url, title, type) {
            const text = type === 'service' ? 'Pogledaj ovu uslugu: ' + title : 'Pogledaj ovaj oglas: ' + title;

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
            heading.textContent = 'Podeli stavku';

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
</div>
