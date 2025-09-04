<div>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Dopuna kredita</h1>

                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-800">
                                Trenutni kredit: <strong>{{ auth()->user()->balance }} RSD</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="space-y-4 mb-6">
                    <!-- Mobile Banking -->
                    <div
                        class="border rounded-lg {{ $selectedMethod === 'mobile' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} transition-all">
                        <button wire:click="selectMethod('mobile')"
                            class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-qrcode text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Elektronsko i mobilno bankarstvo
                                    </h3>
                                    <p class="text-gray-600 text-sm">Dopunite kredit putem aplikacije ili web stranice
                                        Vaše banke.</p>
                                    <div class="flex items-center mt-2">
                                        <i class="fas fa-info-circle text-blue-500 text-sm mr-1"></i>
                                        <span class="text-blue-600 text-xs">Uz ovaj način plaćanja možete dobiti oznaku
                                            "Verifikovani bankovni račun".</span>
                                    </div>
                                </div>
                            </div>
                            @if ($selectedMethod === 'mobile')
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            @endif
                        </button>
                    </div>

                    <!-- Bank Transfer -->
                    <div
                        class="border rounded-lg {{ $selectedMethod === 'bank' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} transition-all">
                        <button wire:click="selectMethod('bank')"
                            class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-university text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Nalog za uplatu ili prenos</h3>
                                    <p class="text-gray-600 text-sm">Dopunite kredit u banci, pošti ili na drugom
                                        platežnom mestu.</p>
                                </div>
                            </div>
                            @if ($selectedMethod === 'bank')
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            @endif
                        </button>
                    </div>

                    <!-- Credit Card -->
                    <div
                        class="border rounded-lg {{ $selectedMethod === 'card' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} transition-all">
                        <button wire:click="selectMethod('card')"
                            class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-credit-card text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Platna kartica</h3>
                                    <p class="text-gray-600 text-sm">Dopunite kredit platnom karticom bez odlaska u
                                        banku.</p>
                                </div>
                            </div>
                            @if ($selectedMethod === 'card')
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Amount Selection - Only show if method is selected -->
                @if ($selectedMethod)
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Odaberite iznos</h3>

                        <!-- Payer Type Selection for Bank Transfer -->
                        @if ($selectedMethod === 'bank' && !$showPaymentSlip)
                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Tip uplatnica</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model="payerType" value="physical" name="payerType"
                                            class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-gray-700">
                                            <strong>Fizičko lice</strong> - Nalog za uplatu (šifra 289)
                                        </span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model="payerType" value="legal" name="payerType"
                                            class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-gray-700">
                                            <strong>Pravno lice</strong> - Nalog za prenos (šifra 221)
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Predefined Amounts -->
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-4">
                            @foreach ($predefinedAmounts as $predefAmount)
                                <button wire:click="selectAmount({{ $predefAmount }})"
                                    class="p-3 border rounded-lg text-center font-semibold transition-all
                                {{ $amount == $predefAmount ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-blue-300' }}">
                                    {{ number_format($predefAmount, 0, ',', '.') }} RSD
                                </button>
                            @endforeach
                        </div>

                        <!-- Custom Amount -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ili unesite željeni
                                iznos:</label>
                            <input type="number" wire:model.lazy="customAmount" wire:blur="setCustomAmount"
                                placeholder="Unesite iznos (minimum 100 RSD)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('amount')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selected Amount Display -->
                        @if ($amount)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-green-800 font-semibold">Iznos za dopunu:</span>
                                    <span
                                        class="text-green-900 text-lg font-bold">{{ number_format($amount, 0, ',', '.') }}
                                        RSD</span>
                                </div>
                                <div class="flex items-center justify-between mt-2 text-sm">
                                    <span class="text-green-700">Stanje nakon dopune:</span>
                                    <span
                                        class="text-green-800 font-semibold">{{ number_format(auth()->user()->balance + $amount, 0, ',', '.') }}
                                        RSD</span>
                                </div>
                            </div>
                        @endif

                        <!-- Terms and Conditions - Only for non-bank methods -->
                        @if ($amount && $selectedMethod !== 'bank')
                            <div class="mb-6">
                                <label class="flex items-start">
                                    <input type="checkbox" wire:model="acceptedTerms"
                                        class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        Prihvatam
                                        <a href="#" class="text-blue-600 hover:underline">uslove korišćenja</a>
                                        i
                                        <a href="#" class="text-blue-600 hover:underline">pravila privatnosti</a>
                                        za online plaćanje.
                                    </span>
                                </label>
                                @error('acceptedTerms')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Proceed Button - Only for non-bank methods -->
                            <div class="flex justify-end">
                                <button wire:click="proceedToPayment"
                                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Nastavi sa plaćanjem
                                </button>
                            </div>
                        @endif

                        <!-- Bank Transfer - Show Payment Slip Button -->
                        @if ($amount && $selectedMethod === 'bank' && !$showPaymentSlip)
                            <div class="flex justify-end">
                                <button wire:click="proceedToPayment"
                                    class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-colors">
                                    <i class="fas fa-receipt mr-2"></i>
                                    Prikaži uplatnicu
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($selectedMethod === 'mobile')
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                            <span class="text-yellow-800">
                                <strong>Obaveštenje:</strong> Opcija mobilnog bankarstva će biti dostupna uskoro kada
                                nabavimo NBS IPS QR kod.
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Payment Slip Section -->
                @if ($showPaymentSlip && $this->paymentSlipData)
                    <div class="border-t pt-6 mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $this->paymentSlipData['slip_title'] }}
                            </h3>
                            <button wire:click="printPaymentSlip"
                                class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                <i class="fas fa-print mr-2"></i>
                                Štampaj uplatnicu
                            </button>
                        </div>

                        <!-- Payment Slip -->
                        <div id="payment-slip"
                            class="border-2 border-gray-800 rounded-lg p-6 bg-white print:border-black print:shadow-none">
                            <div class="text-center mb-6 print:mb-4">
                                <h2 class="text-2xl font-bold text-gray-900 print:text-xl">
                                    {{ $this->paymentSlipData['slip_title'] }}</h2>
                                <p class="text-gray-600 text-sm print:text-xs">
                                    {{ $this->paymentSlipData['slip_title'] === 'NALOG ZA PRENOS' ? 'Transfer Order' : 'Payment Order' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:gap-4">
                                <!-- Left Column -->
                                <div class="space-y-4 print:space-y-3">
                                    <!-- Platilac -->
                                    <div class="border border-gray-400 p-3 print:p-2">
                                        <p class="text-xs text-gray-600 mb-1 print:text-[10px]">платилац</p>
                                        <p class="font-semibold print:text-sm">
                                            {{ $this->paymentSlipData['payer_name'] }}</p>
                                    </div>

                                    <!-- Svrha plaćanja -->
                                    <div class="border border-gray-400 p-3 print:p-2">
                                        <p class="text-xs text-gray-600 mb-1 print:text-[10px]">сврха плаћања</p>
                                        <p class="font-semibold print:text-sm">
                                            {{ $this->paymentSlipData['payment_purpose'] }}</p>
                                    </div>

                                    <!-- Primalac -->
                                    <div class="border border-gray-400 p-3 print:p-2">
                                        <p class="text-xs text-gray-600 mb-1 print:text-[10px]">прималац</p>
                                        <p class="font-semibold print:text-sm">
                                            {{ $this->paymentSlipData['company_name'] }}</p>
                                        <p class="font-semibold print:text-sm text-sm mt-1">
                                            {{ $this->paymentSlipData['company_address'] }}</p>
                                    </div>

                                    <!-- Potpis platioca -->
                                    <div class="border-t border-gray-400 pt-4 print:pt-2">
                                        <p class="text-xs text-gray-600 mb-2 print:text-[10px]">потпис
                                            платиоца/примаоца</p>
                                        <div class="h-16 print:h-12 border-b border-gray-300"></div>

                                        <div class="grid grid-cols-2 gap-4 mt-4 print:gap-2 print:mt-2">
                                            <div>
                                                <p class="text-xs text-gray-600 mb-1 print:text-[10px]">место и датум
                                                    пријема</p>
                                                <div class="h-8 print:h-6 border-b border-gray-300"></div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 mb-1 print:text-[10px]">датум извршења
                                                </p>
                                                <div class="h-8 print:h-6 border-b border-gray-300"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-4 print:space-y-3">
                                    <!-- Top row: Šifra, Valuta, Iznos -->
                                    <div class="grid grid-cols-3 gap-2 print:gap-1">
                                        <div class="border border-gray-400 p-2 text-center print:p-1">
                                            <p class="text-xs text-gray-600 print:text-[10px]">шифра<br>плаћања</p>
                                            <p class="font-bold text-lg print:text-sm">
                                                {{ $this->paymentSlipData['payment_code'] }}</p>
                                        </div>
                                        <div class="border border-gray-400 p-2 text-center print:p-1">
                                            <p class="text-xs text-gray-600 print:text-[10px]">валута</p>
                                            <p class="font-bold print:text-sm">DIN</p>
                                        </div>
                                        <div class="border border-gray-400 p-2 text-center print:p-1">
                                            <p class="text-xs text-gray-600 print:text-[10px]">износ</p>
                                            <p class="font-bold print:text-sm">=
                                                {{ number_format($this->paymentSlipData['amount'], 2, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    @if ($payerType === 'legal')
                                        <!-- Račun platioca (samo za pravna lica) -->
                                        <div class="border border-gray-400 p-3 print:p-2">
                                            <p class="text-xs text-gray-600 mb-1 print:text-[10px]">рачун платиоца</p>
                                            <div class="border border-gray-300 p-2 h-12 print:h-8">
                                                <p class="text-gray-400 text-sm print:text-xs">(Popunjava platilac)</p>
                                            </div>
                                        </div>

                                        <!-- Model i poziv na broj zaduženja (samo za pravna lica) -->
                                        <div class="border border-gray-400 p-3 print:p-2">
                                            <p class="text-xs text-gray-600 mb-1 print:text-[10px]">модел и позив на
                                                број (задужење)</p>
                                            <div class="grid grid-cols-2 gap-2 print:gap-1">
                                                <div
                                                    class="border border-gray-300 p-2 text-center h-12 print:h-8 print:p-1">
                                                    <p class="text-gray-400 text-sm print:text-xs">(Popunjava)</p>
                                                </div>
                                                <div class="border border-gray-300 p-2 h-12 print:h-8 print:p-1">
                                                    <p class="text-gray-400 text-sm print:text-xs">(Popunjava)</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Račun primaoca -->
                                    <div class="border border-gray-400 p-3 print:p-2">
                                        <p class="text-xs text-gray-600 mb-1 print:text-[10px]">рачун примаоца</p>
                                        <p class="font-semibold font-mono text-lg print:text-sm">
                                            {{ $this->paymentSlipData['bank_account'] }}</p>
                                    </div>

                                    <!-- Model i poziv na broj (odobrenja) -->
                                    <div class="border border-gray-400 p-3 print:p-2">
                                        <p class="text-xs text-gray-600 mb-1 print:text-[10px]">модел и позив на број
                                            (одобрење)</p>
                                        <div class="grid grid-cols-2 gap-2 print:gap-1">
                                            <div class="border border-gray-300 p-2 text-center print:p-1">
                                                <p class="font-bold print:text-sm">
                                                    {{ $this->paymentSlipData['model_number'] }}</p>
                                            </div>
                                            <div class="border border-gray-300 p-2 print:p-1">
                                                <p class="font-bold print:text-sm">
                                                    {{ $this->paymentSlipData['reference_number'] }}</p>
                                            </div>
                                        </div>
                                    </div>



                                    @if ($payerType === 'physical')
                                        <!-- Hitno checkbox (samo za fizička lica) -->
                                        <div
                                            class="border border-gray-400 p-3 print:p-2 flex items-center justify-end">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 border border-gray-400 mr-2"></div>
                                                <p class="text-xs text-gray-600 print:text-[10px]">хитно</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-6 pt-4 border-t border-gray-400 print:mt-4 print:pt-2">
                                <div class="flex justify-between text-sm text-gray-600 print:text-xs">
                                    <div>
                                        <p>Datum izdavanja: {{ $this->paymentSlipData['date'] }}</p>
                                        <p>Vreme: {{ $this->paymentSlipData['time'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p>MyOglasi.rs</p>
                                        <p class="text-red-600 font-semibold">
                                            {{ $this->paymentSlipData['slip_title'] === 'NALOG ZA PRENOS' ? 'Uplate bez unetog poziva na broj neće biti proknjižene!' : 'Uplate bez unetog poziva na broj neće biti proknjižene!' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4 mt-6 print:hidden">
                            <button wire:click="printPaymentSlip"
                                class="flex-1 min-w-0 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-print mr-2"></i>
                                Odštampaj uplatnicu
                            </button>

                            <button wire:click="markAsPaid"
                                onclick="return confirm('Da li ste sigurni da ste izvršili uplatu?')"
                                class="flex-1 min-w-0 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Označiti kao plaćeno
                            </button>

                            <button wire:click="selectMethod(null)"
                                class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Nazad na opcije
                            </button>
                        </div>

                        <!-- Important Notice -->
                        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg print:hidden">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1"></i>
                                <div>
                                    <h3 class="text-red-900 font-semibold mb-2">Važne napomene</h3>
                                    <ul class="text-red-800 text-sm space-y-1">
                                        <li>• Uplatite tačan iznos sa pozivom na broj</li>
                                        <li>• Kredit će biti uvećan automatski kada sistem verifikuje uplatu</li>
                                        <li>• Verifikacija može da traje 1-2 radna dana</li>
                                        <li>• Za hitne slučajeve kontaktirajte podršku na support@myoglasi.rs</li>
                                        <li>• Uplate bez poziva na broj neće biti proknjižene!</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                @endif
            </div>
        </div>

        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('print-payment-slip', () => {
                    window.print();
                });
            });
        </script>

        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #payment-slip,
                #payment-slip * {
                    visibility: visible;
                }

                #payment-slip {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: 0;
                    padding: 20px;
                }

                .print\:hidden {
                    display: none !important;
                }

                .print\:border-black {
                    border-color: black !important;
                }

                .print\:shadow-none {
                    box-shadow: none !important;
                }

                .print\:text-xs {
                    font-size: 12px !important;
                }

                .print\:text-sm {
                    font-size: 14px !important;
                }

                .print\:text-xl {
                    font-size: 20px !important;
                }

                .print\:text-\[10px\] {
                    font-size: 10px !important;
                }

                .print\:bg-transparent {
                    background-color: transparent !important;
                }

                .print\:text-black {
                    color: black !important;
                }

                .print\:p-2 {
                    padding: 8px !important;
                }

                .print\:mb-4 {
                    margin-bottom: 16px !important;
                }

                .print\:gap-4 {
                    gap: 16px !important;
                }

                .print\:gap-2 {
                    gap: 8px !important;
                }

                .print\:space-y-3>*+* {
                    margin-top: 12px !important;
                }

                .print\:mt-4 {
                    margin-top: 16px !important;
                }

                .print\:pt-2 {
                    padding-top: 8px !important;
                }

                .print\:h-8 {
                    height: 32px !important;
                }
            }
        </style>
    </div>
