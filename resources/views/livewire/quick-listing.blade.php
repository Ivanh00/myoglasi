<div>
    <!-- Modal Overlay -->
    @if ($show)
        <div class="fixed inset-0 z-[100] overflow-y-auto" x-data="{ show: @entangle('show') }">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

            <!-- Modal Container - Desktop: centered popup, Mobile: fullscreen -->
            <div class="flex min-h-full items-center justify-center p-0 md:p-4">
                <div
                    class="relative w-full h-screen md:h-auto md:max-w-2xl md:rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden">

                    <!-- Header -->
                    <div
                        class="sticky top-0 z-10 flex items-center justify-between px-4 md:px-6 py-4 bg-sky-600 dark:bg-sky-700 text-white">
                        <h3 class="text-lg md:text-xl font-semibold">Brzo postavljanje - Korak {{ $step }}/5</h3>
                        <button wire:click="closeModal" class="text-white hover:text-slate-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-200 dark:bg-slate-700 h-2">
                        <div class="bg-sky-600 h-2 transition-all duration-300"
                            style="width: {{ ($step / 5) * 100 }}%"></div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 md:p-6 overflow-y-auto" style="max-height: calc(100vh - 180px);">
                        @if (session()->has('error'))
                            <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-200 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-200 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Step 1: Choose Type -->
                        @if ($step === 1)
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Odaberite tip
                                </h4>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Oglas -->
                                    <button wire:click="$set('listingType', 'listing')" type="button"
                                        class="p-6 border-2 rounded-lg transition-all {{ $listingType === 'listing' ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/30' : 'border-slate-300 dark:border-slate-600 hover:border-sky-300' }}">
                                        <i class="fas fa-box text-3xl mb-2 text-sky-600"></i>
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">Oglas</div>
                                    </button>

                                    <!-- Aukcija -->
                                    <button wire:click="$set('listingType', 'auction')" type="button"
                                        class="p-6 border-2 rounded-lg transition-all {{ $listingType === 'auction' ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/30' : 'border-slate-300 dark:border-slate-600 hover:border-amber-300' }}">
                                        <i class="fas fa-gavel text-3xl mb-2 text-amber-600"></i>
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">Aukcija</div>
                                    </button>

                                    <!-- Usluga -->
                                    <button wire:click="$set('listingType', 'service')" type="button"
                                        class="p-6 border-2 rounded-lg transition-all {{ $listingType === 'service' ? 'border-slate-500 bg-slate-50 dark:bg-slate-700' : 'border-slate-300 dark:border-slate-600 hover:border-slate-400' }}">
                                        <i class="fas fa-tools text-3xl mb-2 text-slate-600 dark:text-slate-400"></i>
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">Usluga</div>
                                    </button>

                                    <!-- Poklon -->
                                    <button wire:click="$set('listingType', 'giveaway')" type="button"
                                        class="p-6 border-2 rounded-lg transition-all {{ $listingType === 'giveaway' ? 'border-green-500 bg-green-50 dark:bg-green-900/30' : 'border-slate-300 dark:border-slate-600 hover:border-green-300' }}">
                                        <i class="fas fa-gift text-3xl mb-2 text-green-600"></i>
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">Poklon</div>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Title, Category, Subcategory -->
                        @if ($step === 2)
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Osnovne
                                    informacije</h4>

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Naslov <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="title"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Kategorija <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.live="category_id"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-center">
                                        <option value="">Izaberite kategoriju</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Subcategory -->
                                @if (count($subcategories) > 0)
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                            Podkategorija
                                        </label>
                                        <select wire:model="subcategory_id"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-center">
                                            <option value="">Izaberite podkategoriju</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Step 3: Condition, Price, Auction Options -->
                        @if ($step === 3)
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Cena i stanje
                                </h4>

                                <!-- Condition (not for giveaway or service) -->
                                @if ($listingType !== 'giveaway' && $listingType !== 'service')
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                            Stanje
                                        </label>
                                        <select wire:model="condition_id"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-center">
                                            <option value="">Izaberite stanje</option>
                                            @foreach ($conditions as $condition)
                                                <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Regular Price (not for auction or giveaway) -->
                                @if ($listingType !== 'auction' && $listingType !== 'giveaway')
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                            Cena (RSD) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" wire:model="price" step="0.01"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                                    </div>

                                    <!-- Price Type for Services -->
                                    @if ($listingType === 'service')
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Tip cene
                                            </label>
                                            <select wire:model="price_type"
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-center">
                                                <option value="fixed">Fiksna cena</option>
                                                <option value="hourly">Po satu</option>
                                                <option value="daily">Po danu</option>
                                                <option value="sqm">Po kvadratu</option>
                                                <option value="negotiable">Po dogovoru</option>
                                            </select>
                                        </div>
                                    @endif
                                @endif

                                <!-- Auction Options -->
                                @if ($listingType === 'auction')
                                    <div class="space-y-4">
                                        <!-- Starting Price -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Početna cena (RSD) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" wire:model="starting_price" step="0.01"
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                                        </div>

                                        <!-- Buy Now Price -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Kupi odmah cena (RSD)
                                            </label>
                                            <input type="number" wire:model="buy_now_price" step="0.01"
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100"
                                                placeholder="Opcionalno">
                                        </div>

                                        <!-- Start Type -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Početak aukcije <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model.live="startType"
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-center">
                                                <option value="immediately">Odmah</option>
                                                <option value="scheduled">Zakazano vreme</option>
                                            </select>
                                        </div>

                                        <!-- Scheduled Start DateTime -->
                                        @if ($startType === 'scheduled')
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                        Datum početka <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="date" wire:model="startDate"
                                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100"
                                                        min="{{ date('Y-m-d') }}">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                        Vreme početka <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="time" wire:model="startTime"
                                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Duration -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                Trajanje aukcije (dana) <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model="duration"
                                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-center">
                                                <option value="1">1 dan</option>
                                                <option value="3">3 dana</option>
                                                <option value="5">5 dana</option>
                                                <option value="7">7 dana</option>
                                                <option value="10">10 dana</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <!-- Giveaway Message -->
                                @if ($listingType === 'giveaway')
                                    <div class="p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                                        <p class="text-sm text-green-700 dark:text-green-200">
                                            <i class="fas fa-gift mr-2"></i>
                                            Pokloni su besplatni i ne zahtevaju cenu.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Step 4: Description -->
                        @if ($step === 4)
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Opis</h4>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Opis oglasa <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model="description" rows="8"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100"
                                        placeholder="Unesite detaljan opis..."></textarea>
                                </div>
                            </div>
                        @endif

                        <!-- Step 5: Images -->
                        @if ($step === 5)
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Slike</h4>

                                @include('livewire.components.image-upload', [
                                    'images' => $images,
                                    'maxImages' => \App\Models\Setting::get('max_images_per_listing', 10),
                                    'wireModel' => 'tempImages'
                                ])
                            </div>
                        @endif
                    </div>

                    <!-- Footer Buttons -->
                    <div
                        class="sticky bottom-0 flex items-center justify-between px-4 md:px-6 py-4 bg-slate-100 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600">
                        <button wire:click="previousStep" type="button"
                            class="px-4 py-2 text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-600 border border-slate-300 dark:border-slate-500 rounded-md hover:bg-slate-50 dark:hover:bg-slate-500 {{ $step === 1 ? 'invisible' : '' }}">
                            <i class="fas fa-arrow-left mr-2"></i>Nazad
                        </button>

                        @if ($step < 5)
                            <button wire:click="nextStep" type="button"
                                class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700">
                                Sledeće<i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        @else
                            <button wire:click="createListing" type="button" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="createListing">
                                    <i class="fas fa-check mr-2"></i>Kreiraj oglas
                                </span>
                                <span wire:loading wire:target="createListing" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Kreiranje...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
