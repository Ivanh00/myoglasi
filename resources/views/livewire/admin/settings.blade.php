<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            <i class="fas fa-cogs mr-3"></i>
            Sistemska podešavanja
        </h2>
        
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="switchTab('payments')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'payments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-credit-card mr-1"></i>
                    Plaćanja
                </button>
                <button wire:click="switchTab('general')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-cog mr-1"></i>
                    Opšta
                </button>
                <button wire:click="switchTab('email')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'email' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-envelope mr-1"></i>
                    Email
                </button>
                <button wire:click="switchTab('banking')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'banking' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-university mr-1"></i>
                    Bankovna
                </button>
                <button wire:click="switchTab('auctions')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'auctions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-gavel mr-1"></i>
                    Aukcije
                </button>
            </nav>
        </div>

        <!-- Payment Settings Tab -->
        @if($activeTab === 'payments')
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Listing Fees -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-list-alt mr-2 text-blue-600"></i>
                            Naplaćivanje oglasa
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="listing_fee_enabled" wire:model="listingFeeEnabled" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="listing_fee_enabled" class="ml-2 text-sm text-gray-700">
                                    Uključi naplaćivanje po oglasu
                                </label>
                            </div>
                            
                            @if($listingFeeEnabled)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cena po oglasu (RSD)</label>
                                    <input type="number" wire:model="listingFeeAmount" min="1" max="10000"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    @error('listingFeeAmount') 
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Besplatni oglasi mesečno</label>
                                <input type="number" wire:model="freeListingsPerMonth" min="0" max="100"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-gray-500 mt-1">0 = nema besplatnih oglasa</p>
                                @error('freeListingsPerMonth') 
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Plan -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                            Mesečni plan
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="monthly_plan_enabled" wire:model="monthlyPlanEnabled" 
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="monthly_plan_enabled" class="ml-2 text-sm text-gray-700">
                                    Uključi mesečni plan
                                </label>
                            </div>
                            
                            @if($monthlyPlanEnabled)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cena mesečnog plana (RSD)</label>
                                    <input type="number" wire:model="monthlyPlanPrice" min="100" max="50000"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    <p class="text-xs text-gray-500 mt-1">Korisnici mogu postavljati neograničeno oglasa mesečno</p>
                                    @error('monthlyPlanPrice') 
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Yearly Plan -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-calendar mr-2 text-purple-600"></i>
                            Godišnji plan
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="yearly_plan_enabled" wire:model="yearlyPlanEnabled" 
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="yearly_plan_enabled" class="ml-2 text-sm text-gray-700">
                                    Uključi godišnji plan
                                </label>
                            </div>
                            
                            @if($yearlyPlanEnabled)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cena godišnjeg plana (RSD)</label>
                                    <input type="number" wire:model="yearlyPlanPrice" min="1000" max="500000"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">Korisnici mogu postavljati neograničeno oglasa godišnje</p>
                                    @error('yearlyPlanPrice') 
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Pregled planiranih cena
                        </h3>
                        
                        <div class="space-y-2 text-sm">
                            @if($listingFeeEnabled)
                                <div class="flex justify-between">
                                    <span>Po oglasu:</span>
                                    <span class="font-semibold">{{ number_format($listingFeeAmount, 0, ',', '.') }} RSD</span>
                                </div>
                            @endif
                            
                            @if($monthlyPlanEnabled)
                                <div class="flex justify-between">
                                    <span>Mesečni plan:</span>
                                    <span class="font-semibold">{{ number_format($monthlyPlanPrice, 0, ',', '.') }} RSD</span>
                                </div>
                            @endif
                            
                            @if($yearlyPlanEnabled)
                                <div class="flex justify-between">
                                    <span>Godišnji plan:</span>
                                    <span class="font-semibold">{{ number_format($yearlyPlanPrice, 0, ',', '.') }} RSD</span>
                                </div>
                            @endif
                            
                            @if($freeListingsPerMonth > 0)
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
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sačuvaj podešavanja plaćanja
                    </button>
                </div>
            </div>
        @endif

        <!-- General Settings Tab -->
        @if($activeTab === 'general')
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Naziv sajta</label>
                        <input type="text" wire:model="siteName"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('siteName') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maksimalno slika po oglasu</label>
                        <input type="number" wire:model="maxImagesPerListing" min="1" max="50"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('maxImagesPerListing') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Oglasi ističu posle (dana)</label>
                        <input type="number" wire:model="listingAutoExpireDays" min="7" max="365"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">
                            Važi za sve nove oglase i obnove postojećih oglasa (trenutno: {{ $listingAutoExpireDays }} dana)
                        </p>
                        @error('listingAutoExpireDays') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="maintenance_mode" wire:model="maintenanceMode" 
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="maintenance_mode" class="ml-2 text-sm text-gray-700">
                            Režim održavanja
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button wire:click="saveGeneralSettings" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sačuvaj opšta podešavanja
                    </button>
                </div>
            </div>
        @endif

        <!-- Email Settings Tab -->
        @if($activeTab === 'email')
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin email</label>
                        <input type="email" wire:model="adminEmail"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('adminEmail') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Support email</label>
                        <input type="email" wire:model="supportEmail"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('supportEmail') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button wire:click="saveEmailSettings" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sačuvaj email podešavanja
                    </button>
                </div>
            </div>
        @endif

        <!-- Banking Settings Tab -->
        @if($activeTab === 'banking')
            <div class="space-y-6">
                <!-- Company Info -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-building mr-2 text-blue-600"></i>
                        Podaci o kompaniji
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Naziv kompanije</label>
                            <input type="text" wire:model="companyName"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('companyName') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">PIB kompanije</label>
                            <input type="text" wire:model="companyPib"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('companyPib') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Adresa kompanije</label>
                            <input type="text" wire:model="companyAddress"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('companyAddress') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bank Info -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-university mr-2 text-green-600"></i>
                        Bankarski podaci
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Naziv banke</label>
                            <input type="text" wire:model="bankName"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            @error('bankName') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Broj računa</label>
                            <input type="text" wire:model="bankAccountNumber"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            @error('bankAccountNumber') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Slip Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-receipt mr-2 text-purple-600"></i>
                        Podešavanja uplatnice
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Šifra plaćanja - fizička lica</label>
                            <input type="text" wire:model="paymentCodePhysical" maxlength="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Za nalog za uplatu (289 = ostale uplate)</p>
                            @error('paymentCodePhysical') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Šifra plaćanja - pravna lica</label>
                            <input type="text" wire:model="paymentCodeLegal" maxlength="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Za nalog za prenos (221 = ostali transferi)</p>
                            @error('paymentCodeLegal') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model broj - fizička lica</label>
                            <input type="text" wire:model="modelNumberPhysical" maxlength="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Standardno 97</p>
                            @error('modelNumberPhysical') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model broj - pravna lica</label>
                            <input type="text" wire:model="modelNumberLegal" maxlength="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Standardno 97</p>
                            @error('modelNumberLegal') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Template poziva na broj (odobrenja)</label>
                            <input type="text" wire:model="referenceNumberTemplate"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Koristite {user_id} za ID korisnika, npr: 20-10-{user_id}</p>
                            @error('referenceNumberTemplate') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button wire:click="saveBankingSettings" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sačuvaj bankovna podešavanja
                    </button>
                </div>
            </div>
        @endif

        <!-- Auction Settings Tab -->
        @if($activeTab === 'auctions')
            <div class="space-y-6">
                <!-- Bid Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-gavel mr-2 text-red-600"></i>
                        Podešavanja licitiranja
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Minimalni korak povećanja ponude (RSD)</label>
                            <input type="number" wire:model="auctionDefaultBidIncrement" min="10" max="10000"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            <p class="text-xs text-gray-500 mt-1">Minimalna vrednost za povećanje ponude u aukciji</p>
                            @error('auctionDefaultBidIncrement') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Extension Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-clock mr-2 text-orange-600"></i>
                        Podešavanja produžavanja aukcije
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maksimalno produžavanja</label>
                            <input type="number" wire:model="auctionMaxExtensions" min="1" max="20"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                            <p class="text-xs text-gray-500 mt-1">Koliko puta se aukcija može produžiti</p>
                            @error('auctionMaxExtensions') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vreme produžavanja (minuti)</label>
                            <input type="number" wire:model="auctionExtensionTime" min="1" max="10"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                            <p class="text-xs text-gray-500 mt-1">Za koliko minuta se aukcija produžava</p>
                            @error('auctionExtensionTime') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Aktivacija produžavanja (minuti)</label>
                            <input type="number" wire:model="auctionExtensionTriggerTime" min="1" max="10"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                            <p class="text-xs text-gray-500 mt-1">Ponuda u poslednje X minuta produžava aukciju</p>
                            @error('auctionExtensionTriggerTime') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                            <div class="text-sm text-blue-800">
                                <strong>Kako funkcioniše automatsko produžavanje:</strong>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Ako se postavi ponuda u poslednje {{ $auctionExtensionTriggerTime ?? 3 }} minuta aukcije</li>
                                    <li>Aukcija se automatski produžava za {{ $auctionExtensionTime ?? 3 }} minuta</li>
                                    <li>Ovo se može desiti maksimalno {{ $auctionMaxExtensions ?? 10 }} puta po aukciji</li>
                                    <li>Sprečava "last second sniping" i omogućava fer nadmetanje</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button wire:click="saveAuctionSettings" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sačuvaj podešavanja aukcija
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>