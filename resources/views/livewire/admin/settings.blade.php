<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-6">
            <i class="fas fa-cogs mr-3"></i>
            Sistemska podešavanja
        </h2>

        <!-- Tab Navigation -->
        <div class="border-b border-slate-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="switchTab('payments')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'payments' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-credit-card mr-1"></i>
                    Plaćanja
                </button>
                <button wire:click="switchTab('general')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'general' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-cog mr-1"></i>
                    Opšta
                </button>
                <button wire:click="switchTab('email')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'email' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-envelope mr-1"></i>
                    Email
                </button>
                <button wire:click="switchTab('banking')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'banking' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-university mr-1"></i>
                    Bankovna
                </button>
                <button wire:click="switchTab('auctions')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'auctions' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-gavel mr-1"></i>
                    Aukcije
                </button>
                <button wire:click="switchTab('promotions')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'promotions' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-bullhorn mr-1"></i>
                    Promocije
                </button>
                <button wire:click="switchTab('data')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'data' ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    <i class="fas fa-database mr-1"></i>
                    Podaci
                </button>
            </nav>
        </div>

        <!-- Payment Settings Tab -->
        @if ($activeTab === 'payments')
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Listing Fees -->
                    <div class="border border-slate-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">
                            <i class="fas fa-list-alt mr-2 text-sky-600 dark:text-sky-400"></i>
                            Naplaćivanje oglasa
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="listing_fee_enabled" wire:model="listingFeeEnabled"
                                    class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                                <label for="listing_fee_enabled"
                                    class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                    Uključi naplaćivanje po oglasu
                                </label>
                            </div>

                            @if ($listingFeeEnabled)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena po
                                        oglasu (RSD)</label>
                                    <input type="number" wire:model="listingFeeAmount" min="1" max="10000"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                    @error('listingFeeAmount')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Besplatni
                                    oglasi mesečno</label>
                                <input type="number" wire:model="freeListingsPerMonth" min="0" max="100"
                                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">0 = nema besplatnih oglasa
                                </p>
                                @error('freeListingsPerMonth')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Plan -->
                    <div class="border border-slate-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">
                            <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                            Mesečni plan
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="monthly_plan_enabled" wire:model="monthlyPlanEnabled"
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                <label for="monthly_plan_enabled"
                                    class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                    Uključi mesečni plan
                                </label>
                            </div>

                            @if ($monthlyPlanEnabled)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                        mesečnog plana
                                        (RSD)</label>
                                    <input type="number" wire:model="monthlyPlanPrice" min="100" max="50000"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Korisnici mogu
                                        postavljati neograničeno
                                        oglasa mesečno</p>
                                    @error('monthlyPlanPrice')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Yearly Plan -->
                    <div class="border border-slate-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">
                            <i class="fas fa-calendar mr-2 text-purple-600"></i>
                            Godišnji plan
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="yearly_plan_enabled" wire:model="yearlyPlanEnabled"
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-300 rounded">
                                <label for="yearly_plan_enabled"
                                    class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                    Uključi godišnji plan
                                </label>
                            </div>

                            @if ($yearlyPlanEnabled)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                        godišnjeg plana
                                        (RSD)</label>
                                    <input type="number" wire:model="yearlyPlanPrice" min="1000" max="500000"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Korisnici mogu
                                        postavljati neograničeno
                                        oglasa godišnje</p>
                                    @error('yearlyPlanPrice')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="border border-slate-200 rounded-lg p-4 bg-sky-50">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-sky-600 dark:text-sky-400"></i>
                            Pregled planiranih cena
                        </h3>

                        <div class="space-y-2 text-sm">
                            @if ($listingFeeEnabled)
                                <div class="flex justify-between">
                                    <span>Po oglasu:</span>
                                    <span class="font-semibold">{{ number_format($listingFeeAmount, 0, ',', '.') }}
                                        RSD</span>
                                </div>
                            @endif

                            @if ($monthlyPlanEnabled)
                                <div class="flex justify-between">
                                    <span>Mesečni plan:</span>
                                    <span class="font-semibold">{{ number_format($monthlyPlanPrice, 0, ',', '.') }}
                                        RSD</span>
                                </div>
                            @endif

                            @if ($yearlyPlanEnabled)
                                <div class="flex justify-between">
                                    <span>Godišnji plan:</span>
                                    <span class="font-semibold">{{ number_format($yearlyPlanPrice, 0, ',', '.') }}
                                        RSD</span>
                                </div>
                            @endif

                            @if ($freeListingsPerMonth > 0)
                                <div class="flex justify-between">
                                    <span>Besplatno mesečno:</span>
                                    <span class="font-semibold">{{ $freeListingsPerMonth }} oglasa</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button wire:click="savePaymentSettings"
                        class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sačuvaj podešavanja plaćanja
                    </button>
                </div>
            </div>
        @endif

        <!-- General Settings Tab -->
        @if ($activeTab === 'general')
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Naziv sajta</label>
                        <input type="text" wire:model="siteName"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        @error('siteName')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Maksimalno slika po
                            oglasu</label>
                        <input type="number" wire:model="maxImagesPerListing" min="1" max="50"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        @error('maxImagesPerListing')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Oglasi ističu posle
                            (dana)</label>
                        <input type="number" wire:model="listingAutoExpireDays" min="7" max="365"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                            Važi za sve nove oglase i obnove postojećih oglasa (trenutno: {{ $listingAutoExpireDays }}
                            dana)
                        </p>
                        @error('listingAutoExpireDays')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Maksimalno aktivnih
                            oglasa (besplatni
                            korisnici)</label>
                        <input type="number" wire:model="monthlyListingLimit" min="1" max="1000"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                            Koliko aktivnih oglasa mogu istovremeno da imaju korisnici sa isključenim plaćanjem
                            (trenutno: {{ $monthlyListingLimit }} oglasa)
                        </p>
                        <div class="text-xs text-sky-600 mt-2 p-2 bg-sky-50 border border-sky-200 rounded">
                            💡 <strong>Napomena:</strong> Kada oglas istekne ili se obriše, korisnik može postaviti
                            novi. Ograničava se broj aktivnih oglasa, ne ukupan broj postavljenih.
                        </div>
                        @error('monthlyListingLimit')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Minimalni iznos za
                            deljenje kredita
                            (RSD)</label>
                        <input type="number" wire:model="minimumCreditTransfer" min="1" max="10000"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                            Najmanji iznos koji korisnik može da podeli sa drugim korisnicima (trenutno:
                            {{ number_format($minimumCreditTransfer, 0, ',', '.') }} RSD)
                        </p>
                        <div class="text-xs text-sky-600 mt-2 p-2 bg-sky-50 border border-sky-200 rounded">
                            💡 <strong>Napomena:</strong> Ovo postavke sprečavaju da se šalje po nekoliko dinara,
                            čuvajući korisnicima vreme i povećavajući kvalitet transfera.
                        </div>
                        @error('minimumCreditTransfer')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pricing Settings -->
                    <div
                        class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg border border-slate-200 dark:border-slate-600">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                            Podešavanje cena
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Listing Fee -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="listing_fee_enabled" wire:model="listingFeeEnabled"
                                        class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 dark:border-slate-600 rounded">
                                    <label for="listing_fee_enabled"
                                        class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Naplaćivanje oglasa
                                    </label>
                                </div>
                                @if ($listingFeeEnabled)
                                    <input type="number" wire:model="listingFeeAmount" min="1"
                                        max="1000"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                        Cena za postavljanje oglasa:
                                        {{ number_format($listingFeeAmount, 0, ',', '.') }} RSD
                                    </p>
                                @else
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                        Oglasi su besplatni
                                    </p>
                                @endif
                                @error('listingFeeAmount')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Service Fee -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="service_fee_enabled" wire:model="serviceFeeEnabled"
                                        class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 dark:border-slate-600 rounded">
                                    <label for="service_fee_enabled"
                                        class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Naplaćivanje usluga
                                    </label>
                                </div>
                                @if ($serviceFeeEnabled)
                                    <input type="number" wire:model="serviceFeeAmount" min="1"
                                        max="1000"
                                        class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                        Cena za postavljanje usluge:
                                        {{ number_format($serviceFeeAmount, 0, ',', '.') }} RSD
                                    </p>
                                @else
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                        Usluge su besplatne
                                    </p>
                                @endif
                                @error('serviceFeeAmount')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div
                            class="mt-4 p-3 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg">
                            <div class="text-sm text-sky-800 dark:text-sky-200">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Napomena:</strong> Ove cene se naplaćuju korisnicima koji imaju omogućeno
                                plaćanje "po oglasu/usluzi".
                                Korisnici sa mesečnim/godišnjim planovima postavljaju neograničeno.
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="maintenance_mode" wire:model="maintenanceMode"
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded">
                            <label for="maintenance_mode" class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                Režim održavanja
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="show_last_seen" wire:model="showLastSeen"
                                class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                            <label for="show_last_seen" class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                Prikaži poslednju aktivnost korisnika
                            </label>
                        </div>
                        <div class="text-xs text-sky-600 mt-2 p-2 bg-sky-50 border border-sky-200 rounded">
                            💡 <strong>Napomena:</strong> Kada je uključeno, pored imena korisnika će pisati kada je
                            poslednji put bio aktivan (npr. "Online", "Pre 5 min", "Pre 2 sata").
                        </div>

                        <div class="flex items-center mt-4">
                            <input type="checkbox" id="service_fee_enabled" wire:model="serviceFeeEnabled"
                                class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                            <label for="service_fee_enabled" class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                Naplaćuj objavljivanje usluga
                            </label>
                        </div>

                        @if ($serviceFeeEnabled)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena za
                                    objavljivanje usluge
                                    (RSD)</label>
                                <input type="number" wire:model="serviceFeeAmount" min="1" max="10000"
                                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                    Koliko košta objavljivanje jedne usluge (trenutno:
                                    {{ number_format($serviceFeeAmount, 0, ',', '.') }} RSD)
                                </p>
                                @error('serviceFeeAmount')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="text-xs text-sky-600 mt-2 p-2 bg-sky-50 border border-sky-200 rounded">
                            💡 <strong>Napomena:</strong> Pokloni su uvek besplatni za objavljivanje. Ova naknada se
                            odnosi samo na komercijalne usluge.
                        </div>

                        <!-- Credit Earning Settings -->
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="text-sm font-medium text-green-800 mb-3">
                                <i class="fas fa-coins mr-2"></i>
                                Zaradjivanje kredita
                            </h4>

                            <div class="space-y-4">
                                <!-- Game Credits -->
                                <div>
                                    <div class="flex items-center mb-2">
                                        <input type="checkbox" id="game_credit_enabled"
                                            wire:model="gameCreditEnabled"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                        <label for="game_credit_enabled"
                                            class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                            Omogući zaradjivanje kroz igrice
                                        </label>
                                    </div>

                                    @if ($gameCreditEnabled)
                                        <div class="ml-6">
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200">Maksimalno
                                                kredita
                                                dnevno kroz igrice (RSD)</label>
                                            <input type="number" wire:model="gameCreditAmount" min="1"
                                                max="1000"
                                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                                Koliko kredita mogu da zarade kroz igrice dnevno (trenutno:
                                                {{ number_format($gameCreditAmount, 0, ',', '.') }} RSD)
                                            </p>
                                            @error('gameCreditAmount')
                                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>

                                <!-- Daily Contest -->
                                <div>
                                    <div class="flex items-center mb-2">
                                        <input type="checkbox" id="daily_contest_enabled"
                                            wire:model="dailyContestEnabled"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                        <label for="daily_contest_enabled"
                                            class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                            Omogući dnevni konkurs za najviše oglasa
                                        </label>
                                    </div>

                                    @if ($dailyContestEnabled)
                                        <div class="ml-6">
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nagrada
                                                za
                                                pobednika dnevnog konkursa (RSD)</label>
                                            <input type="number" wire:model="dailyContestAmount" min="1"
                                                max="1000"
                                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                                Koliko kredita dobija član koji postavi najviše oglasa u danu (trenutno:
                                                {{ number_format($dailyContestAmount, 0, ',', '.') }} RSD)
                                            </p>
                                            @error('dailyContestAmount')
                                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>

                                <!-- Game Leaderboard Bonus -->
                                <div>
                                    <div class="flex items-center mb-2">
                                        <input type="checkbox" id="game_leaderboard_enabled"
                                            wire:model="gameLeaderboardEnabled"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                        <label for="game_leaderboard_enabled"
                                            class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                                            Omogući bonus za najbolje igrače dnevno
                                        </label>
                                    </div>

                                    @if ($gameLeaderboardEnabled)
                                        <div class="ml-6">
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-200">Bonus
                                                za dnevnog
                                                pobednika po igri (RSD)</label>
                                            <input type="number" wire:model="gameLeaderboardBonus" min="1"
                                                max="1000"
                                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                                Koliko dodatnog kredita dobija najbolji igrač po igri dnevno (trenutno:
                                                {{ number_format($gameLeaderboardBonus, 0, ',', '.') }} RSD)
                                            </p>
                                            @error('gameLeaderboardBonus')
                                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-xs text-green-600 mt-3 p-2 bg-green-100 border border-green-300 rounded">
                                💡 <strong>Napomena:</strong> Ove opcije omogućavaju korisnicima da zarađuju kredit kroz
                                aktivnosti na sajtu, povećavajući angažovanost.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-10">
                <button wire:click="saveGeneralSettings"
                    class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Sačuvaj opšta podešavanja
                </button>
            </div>
    </div>
    @endif

    <!-- Email Settings Tab -->
    @if ($activeTab === 'email')
        <div class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Admin email</label>
                    <input type="email" wire:model="adminEmail"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                    @error('adminEmail')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Support email</label>
                    <input type="email" wire:model="supportEmail"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                    @error('supportEmail')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email Verification Setting -->
            <div class="mt-6 p-4 bg-sky-50 border border-sky-200 rounded-lg">
                <h4 class="text-sm font-medium text-sky-800 mb-3">
                    <i class="fas fa-shield-check mr-2"></i>
                    Email verifikacija
                </h4>

                <div class="flex items-center mb-3">
                    <input type="checkbox" id="email_verification_enabled" wire:model="emailVerificationEnabled"
                        class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                    <label for="email_verification_enabled" class="ml-2 text-sm text-slate-700 dark:text-slate-200">
                        Zahtevaj email verifikaciju za nove korisnike
                    </label>
                </div>

                <div class="text-xs text-sky-600 p-2 bg-sky-100 border border-sky-300 rounded">
                    <p class="mb-2"><strong>⚠️ NAPOMENA:</strong> Za slanje email-ova potreban je SMTP server.</p>
                    <p class="mb-2"><strong>Opcije za email slanje:</strong></p>
                    <ul class="list-disc list-inside ml-2 space-y-1">
                        <li><strong>Gmail SMTP:</strong> Besplatno do 500 email-ova dnevno</li>
                        <li><strong>Mailtrap:</strong> Za testiranje (development)</li>
                        <li><strong>SendGrid:</strong> Profesionalni servis</li>
                        <li><strong>Mailgun:</strong> Scalable email servis</li>
                    </ul>
                    <p class="mt-2"><strong>Bez SMTP-a:</strong> Ostavite verifikaciju isključenu.</p>
                </div>
            </div>

            <!-- Authentication Options -->
            <div class="mt-6 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                <h4 class="text-sm font-medium text-slate-800 mb-4">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Opcije prijavljivanja
                </h4>

                <div class="space-y-3">
                    <!-- Magic Link -->
                    <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-magic text-sky-600 dark:text-sky-400 mr-3"></i>
                            <div>
                                <div class="font-medium text-slate-900">Magic Link Login</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Prijava preko email link-a
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" wire:model="magicLinkEnabled"
                            class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                    </div>

                    <!-- Google Login -->
                    <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Google Login</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Prijava preko Google naloga
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" wire:model="googleLoginEnabled"
                            class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                    </div>

                    <!-- Facebook Login -->
                    <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="#1877F2" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Facebook Login</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Prijava preko Facebook naloga
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" wire:model="facebookLoginEnabled"
                            class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                    </div>
                </div>

                <div
                    class="text-xs text-slate-600 dark:text-slate-400 mt-3 p-2 bg-slate-100 border border-slate-300 rounded">
                    💡 <strong>Napomena:</strong> Za Google i Facebook login potrebna je OAuth konfiguracija. Vidi
                    OAUTH_SETUP.md za instrukcije.
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveEmailSettings"
                    class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Sačuvaj email podešavanja
                </button>
            </div>
        </div>
    @endif

    <!-- Banking Settings Tab -->
    @if ($activeTab === 'banking')
        <div class="space-y-6">
            <!-- Company Info -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-building mr-2 text-sky-600 dark:text-sky-400"></i>
                    Podaci o kompaniji
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Naziv
                            kompanije</label>
                        <input type="text" wire:model="companyName"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        @error('companyName')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">PIB
                            kompanije</label>
                        <input type="text" wire:model="companyPib"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        @error('companyPib')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Adresa
                            kompanije</label>
                        <input type="text" wire:model="companyAddress"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                        @error('companyAddress')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bank Info -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-university mr-2 text-green-600"></i>
                    Bankarski podaci
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Naziv banke</label>
                        <input type="text" wire:model="bankName"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        @error('bankName')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Broj računa</label>
                        <input type="text" wire:model="bankAccountNumber"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        @error('bankAccountNumber')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Slip Settings -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-receipt mr-2 text-purple-600 dark:text-purple-400"></i>
                    Podešavanja uplatnice
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Šifra plaćanja -
                            fizička lica</label>
                        <input type="text" wire:model="paymentCodePhysical" maxlength="3"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Za nalog za uplatu (289 = ostale
                            uplate)</p>
                        @error('paymentCodePhysical')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Šifra plaćanja -
                            pravna lica</label>
                        <input type="text" wire:model="paymentCodeLegal" maxlength="3"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Za nalog za prenos (221 = ostali
                            transferi)</p>
                        @error('paymentCodeLegal')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Model broj -
                            fizička lica</label>
                        <input type="text" wire:model="modelNumberPhysical" maxlength="3"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Standardno 97</p>
                        @error('modelNumberPhysical')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Model broj - pravna
                            lica</label>
                        <input type="text" wire:model="modelNumberLegal" maxlength="3"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Standardno 97</p>
                        @error('modelNumberLegal')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Template poziva na
                            broj
                            (odobrenja)</label>
                        <input type="text" wire:model="referenceNumberTemplate"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Koristite {user_id} za ID korisnika,
                            npr:
                            20-10-{user_id}</p>
                        @error('referenceNumberTemplate')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveBankingSettings"
                    class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Sačuvaj bankovna podešavanja
                </button>
            </div>
        </div>
    @endif

    <!-- Auction Settings Tab -->
    @if ($activeTab === 'auctions')
        <div class="space-y-6">
            <!-- Bid Settings -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-gavel mr-2 text-red-600 dark:text-red-400"></i>
                    Podešavanja licitiranja
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Minimalni korak
                            povećanja ponude
                            (RSD)</label>
                        <input type="number" wire:model="auctionDefaultBidIncrement" min="10" max="10000"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Minimalna vrednost za povećanje
                            ponude u aukciji</p>
                        @error('auctionDefaultBidIncrement')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Extension Settings -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-clock mr-2 text-orange-600"></i>
                    Podešavanja produžavanja aukcije
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Maksimalno
                            produžavanja</label>
                        <input type="number" wire:model="auctionMaxExtensions" min="1" max="20"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Koliko puta se aukcija može
                            produžiti</p>
                        @error('auctionMaxExtensions')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Vreme produžavanja
                            (minuti)</label>
                        <input type="number" wire:model="auctionExtensionTime" min="1" max="10"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Za koliko minuta se aukcija
                            produžava</p>
                        @error('auctionExtensionTime')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Aktivacija
                            produžavanja
                            (minuti)</label>
                        <input type="number" wire:model="auctionExtensionTriggerTime" min="1" max="10"
                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Ponuda u poslednje X minuta
                            produžava aukciju</p>
                        @error('auctionExtensionTriggerTime')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 p-3 bg-sky-50 border border-sky-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mt-0.5 mr-2"></i>
                        <div class="text-sm text-sky-800">
                            <strong>Kako funkcioniše automatsko produžavanje:</strong>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Ako se postavi ponuda u poslednje {{ $auctionExtensionTriggerTime ?? 3 }} minuta
                                    aukcije</li>
                                <li>Aukcija se automatski produžava za {{ $auctionExtensionTime ?? 3 }} minuta</li>
                                <li>Ovo se može desiti maksimalno {{ $auctionMaxExtensions ?? 10 }} puta po aukciji
                                </li>
                                <li>Sprečava "last second sniping" i omogućava fer nadmetanje</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveAuctionSettings"
                    class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Sačuvaj podešavanja aukcija
                </button>
            </div>
        </div>
    @endif

    <!-- Promotions Settings Tab -->
    @if ($activeTab === 'promotions')
        <div class="space-y-6">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-bullhorn text-amber-600 dark:text-amber-400 mt-0.5 mr-2"></i>
                    <div class="text-sm text-amber-800">
                        <strong>Promocije oglasa</strong> omogućavaju korisnicima da plate dodatno da bi njihovi oglasi
                        bili istaknutiji.
                        Ovde možete podesiti cene i trajanje za svaku vrstu promocije.
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Featured Category -->
                <div class="border border-slate-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-arrow-up text-sky-600 dark:text-sky-400 mr-2"></i>
                        Top kategorije
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Oglas se prikazuje na vrhu liste u
                        svojoj kategoriji</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                (RSD)</label>
                            <input type="number" wire:model="promotionFeaturedCategoryPrice" min="1"
                                max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                            @error('promotionFeaturedCategoryPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trajanje
                                (dana)</label>
                            <input type="number" wire:model="promotionFeaturedCategoryDays" min="1"
                                max="365"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                            @error('promotionFeaturedCategoryDays')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Featured Homepage -->
                <div class="border border-slate-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-star text-red-600 dark:text-red-400 mr-2"></i>
                        Top glavne strane
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Oglas se prikazuje na vrhu glavne strane
                        sajta</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                (RSD)</label>
                            <input type="number" wire:model="promotionFeaturedHomepagePrice" min="1"
                                max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            @error('promotionFeaturedHomepagePrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trajanje
                                (dana)</label>
                            <input type="number" wire:model="promotionFeaturedHomepageDays" min="1"
                                max="365"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            @error('promotionFeaturedHomepageDays')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Highlighted -->
                <div class="border border-slate-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-highlighter text-amber-600 dark:text-amber-400 mr-2"></i>
                        Istaknut oglas
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Oglas ima drugačiju boju pozadine i
                        okvir</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                (RSD)</label>
                            <input type="number" wire:model="promotionHighlightedPrice" min="1"
                                max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            @error('promotionHighlightedPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trajanje
                                (dana)</label>
                            <input type="number" wire:model="promotionHighlightedDays" min="1"
                                max="365"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            @error('promotionHighlightedDays')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Auto Refresh -->
                <div class="border border-slate-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-sync text-green-600 mr-2"></i>
                        Automatsko osvežavanje
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Oglas se automatski "podiže" na vrh
                        svaki dan</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                (RSD)</label>
                            <input type="number" wire:model="promotionAutoRefreshPrice" min="1"
                                max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            @error('promotionAutoRefreshPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trajanje
                                (dana)</label>
                            <input type="number" wire:model="promotionAutoRefreshDays" min="1"
                                max="365"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            @error('promotionAutoRefreshDays')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Double Images -->
                <div class="border border-slate-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-images text-purple-600  dark:text-purple-400 mr-2"></i>
                        Dupliraj broj slika
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Udvostručuje dozvoljen broj slika za
                        oglas (trenutno: max
                        {{ \App\Models\Setting::get('max_images_per_listing', 10) }} →
                        {{ \App\Models\Setting::get('max_images_per_listing', 10) * 2 }} slika)</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                (RSD)</label>
                            <input type="number" wire:model="promotionDoubleImagesPrice" min="1"
                                max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            @error('promotionDoubleImagesPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trajanje
                                (dana)</label>
                            <input type="number" wire:model="promotionDoubleImagesDays" min="1"
                                max="365"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            @error('promotionDoubleImagesDays')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Extended Duration -->
                <div class="border border-slate-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-clock text-orange-600 mr-2"></i>
                        Produžena trajnost
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Produžava vreme isteka oglasa</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cena
                                (RSD)</label>
                            <input type="number" wire:model="promotionExtendedDurationPrice" min="1"
                                max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                            @error('promotionExtendedDurationPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Trajanje
                                (dana)</label>
                            <input type="number" wire:model="promotionExtendedDurationDays" min="1"
                                max="365"
                                class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                            @error('promotionExtendedDurationDays')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveGeneralSettings"
                    class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Sačuvaj podešavanja promocija
                </button>
            </div>
        </div>
    @endif

    <!-- Data Backup/Restore Tab -->
    @if ($activeTab === 'data')
        <div class="space-y-6">
            <!-- Database Backup Section -->
            <div class="border border-slate-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-download mr-2 text-green-600 dark:text-green-400"></i>
                    Backup baze podataka
                </h3>

                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    Preuzmite potpunu kopiju baze podataka sa svim tabelama i podacima.
                    Slike koje se čuvaju na S3 servisu će biti sačuvane samo kao linkovi/putanje.
                </p>

                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-sky-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-sky-700 dark:text-sky-200">
                            <p class="font-medium">Backup uključuje:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Sve korisnike i njihove profile</li>
                                <li>Sve oglase, aukcije i usluge</li>
                                <li>Kategorije i podkategorije</li>
                                <li>Poruke i obaveštenja</li>
                                <li>Transakcije i balanse</li>
                                <li>Sistemska podešavanja</li>
                                <li>Linkove ka slikama (S3/lokalne)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button wire:click="exportDatabase" wire:loading.attr="disabled"
                    class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="exportDatabase">
                        <i class="fas fa-download mr-2"></i>
                        Preuzmi backup baze
                    </span>
                    <span wire:loading wire:target="exportDatabase">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Priprema backup-a...
                    </span>
                </button>
            </div>

            <!-- Database Restore Section -->
            <div class="border border-slate-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-upload mr-2 text-orange-600 dark:text-orange-400"></i>
                    Vraćanje baze podataka
                </h3>

                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    Vratite prethodno sačuvanu kopiju baze podataka. Ova opcija će zameniti sve postojeće podatke.
                </p>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-red-700 dark:text-red-200">
                            <p class="font-bold">UPOZORENJE!</p>
                            <p>Vraćanje backup-a će:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Obrisati sve postojeće podatke</li>
                                <li>Zameniti ih podacima iz backup fajla</li>
                                <li>Ova akcija je nepovratna!</li>
                            </ul>
                            <p class="mt-2 font-medium">Preporučujemo da prvo napravite backup trenutnih podataka.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Izaberite backup fajl (SQL ili JSON format)
                    </label>
                    <input type="file" wire:model="backupFile" accept=".sql,.json"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">

                    @error('backupFile')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="backupFile" class="mt-2">
                        <p class="text-sm text-slate-500 dark:text-slate-300">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Učitavanje fajla...
                        </p>
                    </div>
                </div>

                @if ($backupFile)
                    <div class="mt-4">
                        <button wire:click="$set('showRestoreConfirmation', true)"
                            class="px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Vrati backup
                        </button>
                    </div>
                @endif
            </div>

            <!-- Database Info -->
            <div class="border border-slate-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-sky-600 dark:text-sky-400"></i>
                    Informacije o bazi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Tip baze:</p>
                        <p class="font-medium">{{ $databaseType }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Veličina baze:</p>
                        <p class="font-medium">{{ $databaseSize }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Broj tabela:</p>
                        <p class="font-medium">{{ $tableCount }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Poslednji backup:</p>
                        <p class="font-medium">{{ $lastBackupDate ?? 'Nikada' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Restore Confirmation Modal -->
    @if ($showRestoreConfirmation ?? false)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Potvrdite vraćanje backup-a</h3>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-red-800">
                            Da li ste sigurni da želite da vratite backup?
                            <br><br>
                            <strong>Svi trenutni podaci će biti obrisani!</strong>
                        </p>
                    </div>

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showRestoreConfirmation', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Otkaži
                        </button>
                        <button wire:click="importDatabase"
                            class="px-4 py-2 bg-red-600 dark:text-red-400 text-white rounded-md hover:bg-red-700">
                            <i class="fas fa-upload mr-2"></i>
                            Nastavi sa vraćanjem
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- JavaScript for redirect after restore -->
<script>
    window.addEventListener('redirect-after-restore', event => {
        setTimeout(function() {
            window.location.href = '/login';
        }, 3000);
    });
</script>
</div>
