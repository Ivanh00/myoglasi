<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Vaš plan</h1>
                <a href="{{ route('balance.index') }}" 
                    class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Nazad na balans
                </a>
            </div>
            
            <!-- Current Plan Status -->
            <div class="mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900">Trenutni plan</h3>
                            <p class="text-blue-800">
                                {{ auth()->user()->plan_status }}
                            </p>
                            @if(auth()->user()->plan_expires_at && auth()->user()->plan_expires_at->isFuture())
                                <p class="text-sm text-blue-600 mt-1">
                                    Ističe: {{ auth()->user()->plan_expires_at->format('d.m.Y H:i') }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-blue-700 text-sm">Trenutni kredit:</p>
                            <p class="text-blue-900 font-bold text-xl">{{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(!auth()->user()->payment_enabled)
                <!-- Payment Disabled Notice -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <i class="fas fa-gift text-green-600 text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-green-900 mb-2">Besplatan pristup</h3>
                    <p class="text-green-800">
                        Administrator je isključio naplaćivanje za vaš nalog. Možete postavljati oglase besplatno!
                    </p>
                </div>
            @else
                <!-- Plan Selection -->
                <form wire:submit.prevent="purchasePlan">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Per Listing Plan -->
                        @if($planPrices['per_listing']['enabled'])
                            <div class="border rounded-lg p-6 {{ $selectedPlan === 'per_listing' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} transition-all">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="selectedPlan" value="per_listing" class="sr-only">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-receipt text-blue-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $planPrices['per_listing']['title'] }}</h3>
                                        <div class="text-2xl font-bold text-blue-600 mb-2">
                                            {{ number_format($planPrices['per_listing']['price'], 0, ',', '.') }} RSD
                                        </div>
                                        <p class="text-sm text-gray-600 mb-4">po oglasu</p>
                                        <p class="text-xs text-gray-500">{{ $planPrices['per_listing']['description'] }}</p>
                                        
                                        @if($selectedPlan === 'per_listing')
                                            <div class="mt-4">
                                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endif

                        <!-- Monthly Plan -->
                        @if($planPrices['monthly']['enabled'])
                            <div class="border rounded-lg p-6 {{ $selectedPlan === 'monthly' ? 'border-green-500 bg-green-50' : 'border-gray-200' }} transition-all">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="selectedPlan" value="monthly" class="sr-only">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-calendar-alt text-green-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $planPrices['monthly']['title'] }}</h3>
                                        <div class="text-2xl font-bold text-green-600 mb-2">
                                            {{ number_format($planPrices['monthly']['price'], 0, ',', '.') }} RSD
                                        </div>
                                        <p class="text-sm text-gray-600 mb-4">na mesec</p>
                                        <p class="text-xs text-gray-500 mb-2">{{ $planPrices['monthly']['description'] }}</p>
                                        
                                        @if(auth()->user()->balance >= $planPrices['monthly']['price'])
                                            <p class="text-xs text-green-600">✓ Dovoljno kredita</p>
                                        @else
                                            <p class="text-xs text-red-600">✗ Potrebno još {{ number_format($planPrices['monthly']['price'] - auth()->user()->balance, 0, ',', '.') }} RSD</p>
                                        @endif
                                        
                                        @if($selectedPlan === 'monthly')
                                            <div class="mt-4">
                                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endif

                        <!-- Yearly Plan -->
                        @if($planPrices['yearly']['enabled'])
                            <div class="border rounded-lg p-6 {{ $selectedPlan === 'yearly' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }} transition-all">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="selectedPlan" value="yearly" class="sr-only">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-calendar text-purple-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $planPrices['yearly']['title'] }}</h3>
                                        <div class="text-2xl font-bold text-purple-600 mb-2">
                                            {{ number_format($planPrices['yearly']['price'], 0, ',', '.') }} RSD
                                        </div>
                                        <p class="text-sm text-gray-600 mb-4">na godinu</p>
                                        <p class="text-xs text-gray-500 mb-2">{{ $planPrices['yearly']['description'] }}</p>
                                        
                                        @if(auth()->user()->balance >= $planPrices['yearly']['price'])
                                            <p class="text-xs text-green-600">✓ Dovoljno kredita</p>
                                        @else
                                            <p class="text-xs text-red-600">✗ Potrebno još {{ number_format($planPrices['yearly']['price'] - auth()->user()->balance, 0, ',', '.') }} RSD</p>
                                        @endif
                                        
                                        @if($selectedPlan === 'yearly')
                                            <div class="mt-4">
                                                <i class="fas fa-check-circle text-purple-500 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endif
                    </div>

                    <!-- Plan Comparison -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Poređenje planova</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                @if($planPrices['per_listing']['enabled'])
                                    <div class="text-center">
                                        <h4 class="font-semibold text-blue-600 mb-2">Po oglasu</h4>
                                        <p>{{ number_format($planPrices['per_listing']['price'], 0, ',', '.') }} RSD po oglasu</p>
                                        <p class="text-gray-500 text-xs mt-1">Idealno za retko postavljanje</p>
                                    </div>
                                @endif
                                
                                @if($planPrices['monthly']['enabled'])
                                    <div class="text-center">
                                        <h4 class="font-semibold text-green-600 mb-2">Mesečni</h4>
                                        <p>{{ number_format($planPrices['monthly']['price'], 0, ',', '.') }} RSD mesečno</p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            Isplativo od {{ ceil($planPrices['monthly']['price'] / $planPrices['per_listing']['price']) }} oglasa mesečno
                                        </p>
                                    </div>
                                @endif
                                
                                @if($planPrices['yearly']['enabled'])
                                    <div class="text-center">
                                        <h4 class="font-semibold text-purple-600 mb-2">Godišnji</h4>
                                        <p>{{ number_format($planPrices['yearly']['price'], 0, ',', '.') }} RSD godišnje</p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            Isplativo od {{ ceil($planPrices['yearly']['price'] / $planPrices['per_listing']['price']) }} oglasa godišnje
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center">
                        <button type="button" wire:click="cancelPlanSelection"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>
                        
                        @if($selectedPlan !== $currentPlan || !auth()->user()->hasActivePlan())
                            <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                @if($selectedPlan === 'per_listing')
                                    <i class="fas fa-check mr-2"></i>
                                    Promeni na plaćanje po oglasu
                                @else
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Kupi {{ $planPrices[$selectedPlan]['title'] ?? '' }} 
                                    ({{ number_format($planPrices[$selectedPlan]['price'] ?? 0, 0, ',', '.') }} RSD)
                                @endif
                            </button>
                        @else
                            <div class="text-green-600 font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>
                                Trenutno aktivan plan
                            </div>
                        @endif
                    </div>

                    @error('selectedPlan') 
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>

                <!-- Plan Benefits -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Prednosti planova</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="space-y-2">
                            <h4 class="font-semibold text-blue-600">Plaćanje po oglasu</h4>
                            <ul class="space-y-1 text-gray-600">
                                <li>• Plaćate samo kad postavite oglas</li>
                                <li>• Nema mesečnih obaveza</li>
                                <li>• Idealno za povremeno korišćenje</li>
                            </ul>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-semibold text-green-600">Mesečni plan</h4>
                            <ul class="space-y-1 text-gray-600">
                                <li>• Neograničeno oglasa 30 dana</li>
                                <li>• Fiksna mesečna cena</li>
                                <li>• Idealno za aktivne prodavce</li>
                            </ul>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-semibold text-purple-600">Godišnji plan</h4>
                            <ul class="space-y-1 text-gray-600">
                                <li>• Neograničeno oglasa 365 dana</li>
                                <li>• Najbolja cena po danu</li>
                                <li>• Idealno za profesionalne prodavce</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>