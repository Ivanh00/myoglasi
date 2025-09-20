<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-slate-900">Plaćanje karticom</h1>
                <div class="flex items-center space-x-2">
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCA0MCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzAwNTZBNSIvPgo8cGF0aCBkPSJNMTQuNSAxNi41SDE2LjVWNy41SDE0LjVWMTYuNU0yMy41IDhIMjEuNUMxOSA4IDE4IDkgMTggMTEuNUMxOCAxNCAxOSAxNSAyMS41IDE1SDIzLjVWMTZIMjQuNVY4SDIzLjVaTTIxLjUgMTQuSDIzLjVWOS41SDIxLjVDMjAuNSA5LjUgMTkuNSAxMCAxOS41IDExLjVDMTkuNSAxMyAyMC41IDEzLjUgMjEuNSAxNFoiIGZpbGw9IndoaXRlIi8+Cjx0ZXh0IHg9IjMiIHk9IjE0IiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iNiIgZmlsbD0id2hpdGUiPklOVEVTQTwvdGV4dD4KPC9zdmc+"
                        alt="Intesa Banka" class="h-8">
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sky-700 text-sm">Iznos za dopunu:</span>
                        <p class="text-sky-900 text-xl font-bold">{{ number_format($transaction->amount, 0, ',', '.') }}
                            RSD</p>
                    </div>
                    <div>
                        <span class="text-sky-700 text-sm">ID transakcije:</span>
                        <p class="text-sky-900 font-mono text-sm">{{ $transaction->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form wire:submit.prevent="processPayment">
                <div class="space-y-6">
                    <!-- Card Number -->
                    <div>
                        <label for="card_number"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Broj kartice
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="paymentData.card_number" wire:keyup="formatCardNumber"
                                id="card_number" placeholder="1234 5678 9012 3456" maxlength="19"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 font-mono text-lg">
                            <div class="absolute right-3 top-3 flex space-x-1">
                                <i class="fab fa-cc-visa text-sky-600 dark:text-sky-400 text-lg"></i>
                                <i class="fab fa-cc-mastercard text-red-500 text-lg"></i>
                            </div>
                        </div>
                        @error('paymentData.card_number')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiry and CVV -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Datum isteka
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <select wire:model="paymentData.expiry_month"
                                    class="px-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                                    <option value="">MM</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <select wire:model="paymentData.expiry_year"
                                    class="px-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                                    <option value="">YYYY</option>
                                    @for ($i = date('Y'); $i <= date('Y') + 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('paymentData.expiry_month')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('paymentData.expiry_year')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cvv"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                CVV
                            </label>
                            <input type="text" wire:model="paymentData.cvv" id="cvv" placeholder="123"
                                maxlength="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 font-mono text-center">
                            @error('paymentData.cvv')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cardholder Name -->
                    <div>
                        <label for="cardholder_name"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Ime vlasnika kartice
                        </label>
                        <input type="text" wire:model="paymentData.cardholder_name" id="cardholder_name"
                            placeholder="PETAR PETROVIĆ"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 uppercase">
                        @error('paymentData.cardholder_name')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                            <div>
                                <p class="text-green-800 text-sm font-semibold">Vaši podaci su bezbedni</p>
                                <p class="text-green-700 text-xs">SSL enkripcija • PCI DSS sertifikovano • Intesa Banka
                                    zaštićeno</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button type="button" wire:click="cancelPayment"
                            class="flex-1 px-6 py-3 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Nazad
                        </button>

                        <button type="submit" wire:loading.attr="disabled" wire:target="processPayment"
                            class="flex-1 px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 disabled:opacity-50 transition-colors">
                            <div wire:loading wire:target="processPayment" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Obrađujem plaćanje...
                            </div>
                            <div wire:loading.remove wire:target="processPayment"
                                class="flex items-center justify-center">
                                <i class="fas fa-credit-card mr-2"></i>
                                Plati {{ number_format($transaction->amount, 0, ',', '.') }} RSD
                            </div>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Processing Overlay -->
            <div wire:loading wire:target="processPayment"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-md mx-4">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sky-600 mx-auto mb-4"></div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Obrađujem plaćanje</h3>
                        <p class="text-slate-600 dark:text-slate-400">Molimo sačekajte, povezujemo se sa Intesa
                            bankom...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
