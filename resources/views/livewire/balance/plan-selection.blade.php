<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Vaš plan</h1>
                <a href="{{ route('balance.index') }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Nazad na balans
                </a>
            </div>

            <!-- Current Plan Status -->
            <div class="mb-8">
                <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-600 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-sky-900 dark:text-sky-100">Trenutni plan</h3>
                            <p class="text-sky-800 dark:text-sky-200">
                                {{ auth()->user()->plan_status }}
                            </p>
                            @if (auth()->user()->plan_expires_at &&
                                    auth()->user()->plan_expires_at->isFuture() &&
                                    auth()->user()->payment_plan !== 'per_listing')
                                <p class="text-sm text-sky-600 dark:text-sky-400 mt-1">
                                    Ističe: {{ auth()->user()->plan_expires_at->format('d.m.Y H:i') }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sky-700 dark:text-sky-300 text-sm">Trenutni kredit:</p>
                            <p class="text-sky-900 dark:text-sky-100 font-bold text-xl">
                                {{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (!auth()->user()->payment_enabled)
                <!-- Payment Disabled Notice -->
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-600 rounded-lg p-6 text-center mb-6">
                    <i class="fas fa-gift text-green-600 text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-2">Trenutno imate besplatan pristup</h3>
                    <p class="text-green-800 dark:text-green-200 mb-4">
                        Možete postavljati oglase besplatno! Ako želite da aktivirate neki od plaćenih planova,
                        odaberite opciju ispod.
                    </p>
                </div>
            @endif

            @if (!auth()->user()->payment_enabled || true)
                <!-- Plan Selection -->
                <form wire:submit.prevent="purchasePlan">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Per Listing Plan -->
                        @if ($planPrices['per_listing']['enabled'])
                            <div class="border rounded-lg p-6 {{ $selectedPlan === 'per_listing' ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/20' : 'border-slate-200 dark:border-slate-600' }} transition-all cursor-pointer"
                                wire:click="$set('selectedPlan', 'per_listing')">
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-sky-100 dark:bg-sky-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-receipt text-sky-600 dark:text-sky-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                        {{ $planPrices['per_listing']['title'] }}</h3>
                                    <div class="text-2xl font-bold text-sky-600 dark:text-sky-400 mb-2">
                                        {{ number_format($planPrices['per_listing']['price'], 0, ',', '.') }} RSD
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">po oglasu</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-300">
                                        {{ $planPrices['per_listing']['description'] }}
                                    </p>

                                    @if ($selectedPlan === 'per_listing')
                                        <div class="mt-4">
                                            <i class="fas fa-check-circle text-sky-500 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Monthly Plan -->
                        @if ($planPrices['monthly']['enabled'])
                            <div class="border rounded-lg p-6 {{ $selectedPlan === 'monthly' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-slate-200 dark:border-slate-600' }} transition-all cursor-pointer"
                                wire:click="$set('selectedPlan', 'monthly')">
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calendar-alt text-green-600 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                        {{ $planPrices['monthly']['title'] }}</h3>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                                        {{ number_format($planPrices['monthly']['price'], 0, ',', '.') }} RSD
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">na mesec</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mb-2">
                                        {{ $planPrices['monthly']['description'] }}
                                    </p>

                                    @if (auth()->user()->balance >= $planPrices['monthly']['price'])
                                        <p class="text-xs text-green-600 dark:text-green-400">✓ Dovoljno kredita</p>
                                    @else
                                        <p class="text-xs text-red-600 dark:text-red-400">✗ Potrebno još
                                            {{ number_format($planPrices['monthly']['price'] - auth()->user()->balance, 0, ',', '.') }}
                                            RSD</p>
                                    @endif

                                    @if ($selectedPlan === 'monthly')
                                        <div class="mt-4">
                                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Yearly Plan -->
                        @if ($planPrices['yearly']['enabled'])
                            <div class="border rounded-lg p-6 {{ $selectedPlan === 'yearly' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-slate-200 dark:border-slate-600' }} transition-all cursor-pointer"
                                wire:click="$set('selectedPlan', 'yearly')">
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calendar text-purple-600 dark:text-purple-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                        {{ $planPrices['yearly']['title'] }}</h3>
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                                        {{ number_format($planPrices['yearly']['price'], 0, ',', '.') }} RSD
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">na godinu</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-300 mb-2">
                                        {{ $planPrices['yearly']['description'] }}
                                    </p>

                                    @if (auth()->user()->balance >= $planPrices['yearly']['price'])
                                        <p class="text-xs text-green-600 dark:text-green-400">✓ Dovoljno kredita</p>
                                    @else
                                        <p class="text-xs text-red-600 dark:text-red-400">✗ Potrebno još
                                            {{ number_format($planPrices['yearly']['price'] - auth()->user()->balance, 0, ',', '.') }}
                                            RSD</p>
                                    @endif

                                    @if ($selectedPlan === 'yearly')
                                        <div class="mt-4">
                                            <i class="fas fa-check-circle text-purple-500 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Plan Comparison -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Poređenje planova</h3>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                @if ($planPrices['per_listing']['enabled'])
                                    <div class="text-center">
                                        <h4 class="font-semibold text-sky-600 dark:text-sky-400 mb-2">Po oglasu</h4>
                                        <p>{{ number_format($planPrices['per_listing']['price'], 0, ',', '.') }} RSD po
                                            oglasu</p>
                                        <p class="text-slate-500 dark:text-slate-300 text-xs mt-1">Idealno za retko
                                            postavljanje</p>
                                    </div>
                                @endif

                                @if ($planPrices['monthly']['enabled'])
                                    <div class="text-center">
                                        <h4 class="font-semibold text-green-600 dark:text-green-400 mb-2">Mesečni</h4>
                                        <p>{{ number_format($planPrices['monthly']['price'], 0, ',', '.') }} RSD
                                            mesečno</p>
                                        <p class="text-slate-500 dark:text-slate-300 text-xs mt-1">
                                            Isplativo od
                                            {{ ceil($planPrices['monthly']['price'] / $planPrices['per_listing']['price']) }}
                                            oglasa mesečno
                                        </p>
                                    </div>
                                @endif

                                @if ($planPrices['yearly']['enabled'])
                                    <div class="text-center">
                                        <h4 class="font-semibold text-purple-600 dark:text-purple-400 mb-2">Godišnji
                                        </h4>
                                        <p>{{ number_format($planPrices['yearly']['price'], 0, ',', '.') }} RSD
                                            godišnje</p>
                                        <p class="text-slate-500 dark:text-slate-300 text-xs mt-1">
                                            Isplativo od
                                            {{ ceil($planPrices['yearly']['price'] / $planPrices['per_listing']['price']) }}
                                            oglasa godišnje
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center">
                        <button type="button" wire:click="cancelPlanSelection"
                            class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Otkaži
                        </button>

                        <!-- Always show the button if a plan is selected -->
                        <button type="submit"
                            class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                            @if ($selectedPlan === 'per_listing')
                                <i class="fas fa-check mr-2"></i>
                                {{ !auth()->user()->payment_enabled ? 'Aktiviraj' : 'Promeni na' }} plaćanje po oglasu
                            @else
                                <i class="fas fa-shopping-cart mr-2"></i>
                                {{ !auth()->user()->payment_enabled ? 'Aktiviraj' : 'Kupi' }}
                                {{ $planPrices[$selectedPlan]['title'] ?? '' }}
                                @if (isset($planPrices[$selectedPlan]['price']))
                                    ({{ number_format($planPrices[$selectedPlan]['price'], 0, ',', '.') }} RSD)
                                @endif
                            @endif
                        </button>
                    </div>

                    @error('selectedPlan')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>

                <!-- Plan Benefits -->
                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-600">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Prednosti planova</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="space-y-2">
                            <h4 class="font-semibold text-sky-600 dark:text-sky-400">Plaćanje po oglasu</h4>
                            <ul class="space-y-1 text-slate-600 dark:text-slate-400">
                                <li>• Plaćate samo kad postavite oglas</li>
                                <li>• Nema mesečnih obaveza</li>
                                <li>• Idealno za povremeno korišćenje</li>
                            </ul>
                        </div>

                        <div class="space-y-2">
                            <h4 class="font-semibold text-green-600 dark:text-green-400">Mesečni plan</h4>
                            <ul class="space-y-1 text-slate-600 dark:text-slate-400">
                                <li>• Neograničeno oglasa 30 dana</li>
                                <li>• Fiksna mesečna cena</li>
                                <li>• Idealno za aktivne prodavce</li>
                            </ul>
                        </div>

                        <div class="space-y-2">
                            <h4 class="font-semibold text-purple-600 dark:text-purple-400">Godišnji plan</h4>
                            <ul class="space-y-1 text-slate-600 dark:text-slate-400">
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
