<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Naslov -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Moji oglasi</h1>
        <p class="text-gray-600 mt-2">Upravljajte svojim oglasima</p>
    </div>

    <!-- Dugme za dodavanje novog oglasa i filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <a href="{{ route('listings.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Dodaj novi oglas
        </a>
        
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
                                    Aktivne oglase
                                    @break
                                @case('expired')
                                    Istekle oglase
                                    @break
                                @default
                                    Sve oglase
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
                            Sve oglase
                        </button>
                        <button @click="$wire.set('filter', 'active'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $filter === 'active' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Aktivne oglase
                        </button>
                        <button @click="$wire.set('filter', 'expired'); open = false" type="button"
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-b-lg {{ $filter === 'expired' ? 'bg-blue-50 dark:bg-gray-600 text-blue-700 dark:text-blue-300' : '' }}">
                            Istekle oglase
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Tabela oglasa -->
    @if ($listings->count() > 0)
        <div class="hidden lg:block space-y-1">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="grid grid-cols-[30%_15%_15%_15%_25%] bg-gray-50 dark:bg-gray-700">
                    <div class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Oglas</div>
                    <div class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cena</div>
                    <div class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</div>
                    <div class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Datum</div>
                    <div class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Akcije</div>
                </div>
            </div>

            <!-- Data Rows -->
            @foreach ($listings as $listing)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                    <div class="grid grid-cols-[30%_15%_15%_15%_25%] hover:bg-gray-50 dark:hover:bg-gray-700">
                        <!-- Oglas Column -->
                        <div class="px-6 py-2">
                            <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($listing->images->count() > 0)
                                            <img class="h-10 w-10 rounded-lg object-cover"
                                                src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">
                                            {{ Str::limit($listing->title, 40) }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $listing->category->name }}</div>
                                    </div>
                            </div>
                        </div>
                        <!-- Cena Column -->
                        <div class="px-6 py-2">
                            <div class="text-sm text-gray-900 dark:text-gray-100 font-bold whitespace-nowrap">{{ number_format($listing->price, 2) }} RSD
                            </div>
                        </div>
                        <!-- Status Column -->
                        <div class="px-6 py-2">
                                <div class="flex flex-col">
                                    @if ($listing->isExpired() || $listing->status == 'expired')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 mb-1">Istekao</span>
                                        @if($listing->expires_at)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Istekao {{ $listing->expires_at->format('d.m.Y') }}</span>
                                        @endif
                                    @elseif ($listing->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 mb-1">Aktivan</span>
                                        @if($listing->expires_at)
                                            @php
                                                $daysLeft = now()->diffInDays($listing->expires_at, false);
                                                $daysLeft = max(0, (int)$daysLeft); // Ensure positive number
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($daysLeft <= 5) bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200
                                                @elseif($daysLeft <= 10) bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200
                                                @elseif($daysLeft > 10) bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200
                                                @else bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                                @endif">
                                                @if($daysLeft > 1)
                                                    Ističe za {{ $daysLeft }} dana
                                                @elseif($daysLeft == 1)
                                                    Ističe sutra
                                                @elseif($daysLeft == 0)
                                                    Ističe danas
                                                @else
                                                    Istekao pre {{ abs($daysLeft) }} dana
                                                @endif
                                            </span>
                                        @endif
                                    @elseif($listing->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200">Na čekanju</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">{{ ucfirst($listing->status) }}</span>
                                    @endif
                            </div>
                        </div>
                        <!-- Datum Column -->
                        <div class="px-6 py-2 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col">
                                <span>{{ $listing->created_at->format('d.m.Y') }}</span>
                                @if($listing->renewed_at)
                                    <span class="text-xs text-blue-500">Obnovljen {{ $listing->renewed_at->format('d.m.Y') }}</span>
                                @endif
                                @if($listing->renewal_count > 0)
                                    <span class="text-xs text-gray-400">({{ $listing->renewal_count }}x obnovljen)</span>
                                @endif
                            </div>
                        </div>
                        <!-- Akcije Column -->
                        <div class="px-6 py-2 text-sm font-medium">
                                <div class="space-y-2">
                                    <!-- First row: Primary actions -->
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('listings.show', $listing) }}"
                                            class="inline-flex items-center px-2 py-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 rounded">
                                            <i class="fas fa-eye mr-1"></i> Pregled
                                        </a>
                                        
                                        @if($listing->isActive())
                                            <a href="{{ route('listings.edit', $listing) }}"
                                                class="inline-flex items-center px-2 py-1 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 rounded">
                                                <i class="fas fa-edit mr-1"></i> Izmeni
                                            </a>
                                        @endif
                                        
                                        @if($listing->canBeRenewed())
                                            <button wire:click="renewListing({{ $listing->id }})" 
                                                class="inline-flex items-center px-2 py-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 rounded"
                                                onclick="return confirm('Da li želite da obnovite ovaj oglas? {{ auth()->user()->payment_plan === 'per_listing' ? 'Biće naplaćeno ' . \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD.' : 'Besplatno jer imate aktivan plan.' }}')">
                                                <i class="fas fa-redo mr-1"></i> Obnovi
                                            </button>
                                        @endif

                                        <button class="inline-flex items-center px-2 py-1 text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded"
                                            onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')">
                                            <i class="fas fa-share-alt mr-1"></i> Podeli
                                        </button>
                                        
                                        @if($listing->isActive())
                                            <button wire:click="$dispatch('openPromotionModal', { listingId: {{ $listing->id }} })" 
                                                class="inline-flex items-center px-2 py-1 {{ $listing->hasActivePromotion() ? 'text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300' : 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300' }} rounded">
                                                <i class="fas fa-bullhorn mr-1"></i> 
                                                Promocija
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Second row: Delete action -->
                                    <div class="flex items-center space-x-2">
                                        <button x-data
                                            @click="$dispatch('open-delete-modal', { listingId: {{ $listing->id }} })"
                                            class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded">
                                            <i class="fas fa-trash mr-1"></i> Obriši
                                        </button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Paginacija -->
        <div class="mt-6">
            {{ $listings->links() }}
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach ($listings as $listing)
                <div class="bg-white dark:bg-gray-800 border-l-4 border-blue-500 shadow rounded-lg overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-16 w-16 mr-3">
                                    @if ($listing->images->count() > 0)
                                        <img class="h-16 w-16 rounded-lg object-cover" 
                                             src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}">
                                    @else
                                        <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Listing Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $listing->title }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $listing->category->name }}</p>
                                    <p class="text-xl font-bold text-blue-600">{{ number_format($listing->price, 2) }} RSD</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Status Section -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status oglasa</div>
                            <div class="flex flex-col space-y-2">
                                @if ($listing->isExpired() || $listing->status == 'expired')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 w-fit">Istekao</span>
                                    @if($listing->expires_at)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Istekao {{ $listing->expires_at->format('d.m.Y') }}</span>
                                    @endif
                                @elseif ($listing->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 w-fit">Aktivan</span>
                                    @if($listing->expires_at)
                                        @php
                                            $daysLeft = now()->diffInDays($listing->expires_at, false);
                                            $daysLeft = max(0, (int)$daysLeft);
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit
                                            @if($daysLeft <= 5) bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200
                                            @elseif($daysLeft <= 10) bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200
                                            @elseif($daysLeft > 10) bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200
                                            @else bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                            @endif">
                                            @if($daysLeft > 1)
                                                Ističe za {{ $daysLeft }} dana
                                            @elseif($daysLeft == 1)
                                                Ističe sutra
                                            @elseif($daysLeft == 0)
                                                Ističe danas
                                            @else
                                                Istekao pre {{ abs($daysLeft) }} dana
                                            @endif
                                        </span>
                                    @endif
                                @elseif($listing->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 w-fit">Na čekanju</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-fit">{{ ucfirst($listing->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Informacije o datumu</div>
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900 dark:text-gray-100">Kreiran: {{ $listing->created_at->format('d.m.Y') }}</div>
                                @if($listing->renewed_at)
                                    <div class="text-xs text-blue-500">Obnovljen: {{ $listing->renewed_at->format('d.m.Y') }}</div>
                                @endif
                                @if($listing->renewal_count > 0)
                                    <div class="text-xs text-gray-400">Obnovljen {{ $listing->renewal_count }}x</div>
                                @endif
                            </div>
                        </div>


                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('listings.show', $listing) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Pregled
                            </a>
                            
                            @if($listing->isActive())
                                <a href="{{ route('listings.edit', $listing) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Izmeni
                                </a>
                            @endif
                            
                            @if($listing->canBeRenewed())
                                <button wire:click="renewListing({{ $listing->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors"
                                    onclick="return confirm('Da li želite da obnovite ovaj oglas? {{ auth()->user()->payment_plan === 'per_listing' ? 'Biće naplaćeno ' . \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD.' : 'Besplatno jer imate aktivan plan.' }}')">
                                    <i class="fas fa-redo mr-1"></i>
                                    Obnovi
                                </button>
                            @endif
                            
                            <button class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors"
                                onclick="navigator.clipboard.writeText('{{ route('listings.show', $listing) }}'); alert('Link kopiran!')">
                                <i class="fas fa-share-alt mr-1"></i>
                                Podeli
                            </button>

                            
                            <button x-data
                                @click="$dispatch('open-delete-modal', { listingId: {{ $listing->id }} })"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-1"></i>
                                Obriši
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Mobile Pagination -->
        <div class="lg:hidden mt-6">
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-list-alt text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Nemate nijedan oglas</h3>
            <p class="text-gray-600 mb-4">Kreirajte svoj prvi oglas i počnite da prodajete.</p>
            <a href="{{ route('listings.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Kreiraj prvi oglas
            </a>
        </div>
    @endif
    
    <!-- Single Promotion Manager Modal -->
    @livewire('listings.promotion-manager')

    <!-- Delete Listing Modal -->
    <div x-data="{
            showDeleteModal: false,
            selectedListing: null,
            deleteListing() {
                if (this.selectedListing) {
                    @this.deleteListing(this.selectedListing.id);
                    this.showDeleteModal = false;
                }
            }
        }"
        @open-delete-modal.window="
            showDeleteModal = true;
            selectedListing = $listings.find(l => l.id === $event.detail.listingId);
        "
        x-show="showDeleteModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto">

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
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <!-- Modal header with delete icon -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <i class="fas fa-trash text-white text-xl"></i>
                            </div>
                            <h3 class="ml-3 text-xl font-bold text-white">Brisanje oglasa</h3>
                        </div>
                        <button @click="showDeleteModal = false" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="px-6 py-5">
                    <!-- Warning message -->
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Da li ste sigurni?
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400">
                            Ovaj oglas će biti trajno obrisan. Ova akcija se ne može poništiti.
                        </p>
                    </div>

                    <!-- Listing info -->
                    <template x-if="selectedListing">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Naziv:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="selectedListing?.title || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Kategorija:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="selectedListing?.category?.name || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Cena:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <span x-text="new Intl.NumberFormat('sr-RS').format(selectedListing?.price || 0)"></span> RSD
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Status:</span>
                                    <span class="text-sm font-medium" :class="{
                                        'text-green-600 dark:text-green-400': selectedListing?.status === 'active',
                                        'text-red-600 dark:text-red-400': selectedListing?.status === 'expired',
                                        'text-gray-600 dark:text-gray-400': selectedListing?.status !== 'active' && selectedListing?.status !== 'expired'
                                    }" x-text="selectedListing?.status === 'active' ? 'Aktivan' : (selectedListing?.status === 'expired' ? 'Istekao' : 'Neaktivan')"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Warning notice -->
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800 dark:text-red-200">
                                    <strong>Upozorenje:</strong> Brisanjem oglasa gubite sve podatke vezane za njega, uključujući slike i statistiku pregleda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer with actions -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-1">
                    <div class="flex space-x-3">
                        <button type="button"
                                @click="showDeleteModal = false"
                                class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        <button type="button"
                                @click="deleteListing()"
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                            <i class="fas fa-trash mr-2"></i>
                            Obriši oglas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.$listings = @json($listings->items());
    </script>
</div>
