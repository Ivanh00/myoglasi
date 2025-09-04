<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Nalog za uplatu</h1>
                <div class="text-right">
                    <p class="text-sm text-gray-600">ID transakcije:</p>
                    <p class="font-mono font-semibold">{{ $transaction->id }}</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                    <div>
                        <h3 class="text-blue-900 font-semibold mb-2">Uputstvo za uplatu</h3>
                        <ol class="text-blue-800 text-sm space-y-1">
                            <li>1. Odštampajte ili preuzmi ovu uplatnicu</li>
                            <li>2. Odnesite je u bilo koju banku, poštu ili platni salon</li>
                            <li>3. Izvršite uplatu sa tačnim podacima</li>
                            <li>4. Kredit će biti uveličan u roku od 1-2 radna dana</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Payment Slip -->
            <div id="payment-slip" class="border-2 border-gray-300 rounded-lg p-6 mb-6 bg-white print:border-black print:shadow-none">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">NALOG ZA UPLATU</h2>
                    <p class="text-gray-600">Payment Order</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">Naziv primaoca / Beneficiary name:</p>
                            <p class="font-semibold">{{ $bankDetails['recipient_name'] }}</p>
                        </div>

                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">Adresa primaoca / Beneficiary address:</p>
                            <p class="font-semibold">{{ $bankDetails['recipient_address'] }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="border border-gray-300 p-3">
                                <p class="text-xs text-gray-600 mb-1">PIB:</p>
                                <p class="font-semibold">{{ $bankDetails['recipient_pib'] }}</p>
                            </div>
                            <div class="border border-gray-300 p-3">
                                <p class="text-xs text-gray-600 mb-1">Šifra plaćanja:</p>
                                <p class="font-semibold">{{ $bankDetails['payment_code'] }}</p>
                            </div>
                        </div>

                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">Račun primaoca / Beneficiary account:</p>
                            <p class="font-semibold font-mono text-lg">{{ $bankDetails['recipient_account'] }}</p>
                        </div>

                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">Banka primaoca / Beneficiary bank:</p>
                            <p class="font-semibold">{{ $bankDetails['bank_name'] }}</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="border border-gray-300 p-3 bg-yellow-50">
                            <p class="text-xs text-gray-600 mb-1">Iznos / Amount:</p>
                            <p class="font-bold text-2xl text-green-700">{{ number_format($transaction->amount, 2, ',', '.') }} RSD</p>
                        </div>

                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">Poziv na broj / Reference number:</p>
                            <p class="font-semibold font-mono">{{ $bankDetails['reference_number'] }}</p>
                        </div>

                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">Svrha plaćanja / Payment purpose:</p>
                            <p class="font-semibold">{{ $bankDetails['payment_purpose'] }}</p>
                        </div>

                        <div class="border border-gray-300 p-3">
                            <p class="text-xs text-gray-600 mb-1">SWIFT kod:</p>
                            <p class="font-semibold">{{ $bankDetails['swift'] }}</p>
                        </div>

                        <div class="space-y-2">
                            <div class="border border-gray-300 p-3">
                                <p class="text-xs text-gray-600 mb-1">Ime i prezime uplatnica / Payer name:</p>
                                <p class="font-semibold">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="border border-gray-300 p-3">
                                <p class="text-xs text-gray-600 mb-1">Potpis / Signature:</p>
                                <div class="h-8"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 pt-4 border-t border-gray-300">
                    <div class="flex justify-between text-sm text-gray-600">
                        <div>
                            <p>Datum izdavanja: {{ now()->format('d.m.Y') }}</p>
                            <p>Vreme: {{ now()->format('H:i:s') }}</p>
                        </div>
                        <div class="text-right">
                            <p>MyOglasi.rs</p>
                            <p>ID: {{ $transaction->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4">
                <button wire:click="printPaymentSlip" 
                    class="flex-1 min-w-0 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors print:hidden">
                    <i class="fas fa-print mr-2"></i>
                    Odštampaj uplatnicu
                </button>
                
                <button wire:click="downloadPaymentSlip" 
                    class="flex-1 min-w-0 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors print:hidden">
                    <i class="fas fa-download mr-2"></i>
                    Preuzmi PDF
                </button>
                
                <button wire:click="markAsPaid" 
                    onclick="return confirm('Da li ste sigurni da ste izvršili uplatu?')"
                    class="flex-1 min-w-0 px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition-colors print:hidden">
                    <i class="fas fa-check mr-2"></i>
                    Označiti kao plaćeno
                </button>
                
                <button wire:click="cancelPayment" 
                    onclick="return confirm('Da li želite da otkažete ovu transakciju?')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors print:hidden">
                    <i class="fas fa-times mr-2"></i>
                    Otkaži
                </button>
            </div>

            <!-- Important Notice -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg print:hidden">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                    <div>
                        <h3 class="text-yellow-900 font-semibold mb-2">Važne napomene</h3>
                        <ul class="text-yellow-800 text-sm space-y-1">
                            <li>• Uplatite tačan iznos sa pozivom na broj</li>
                            <li>• Kredit će biti uveličan automatski kada sistem verifikuje uplatu</li>
                            <li>• Verifikacija može da traje 1-2 radna dana</li>
                            <li>• Za hitne slučajeve kontaktirajte podršku na support@myoglasi.rs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('print-payment-slip', () => {
        // Hide non-printable elements and print
        window.print();
    });
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #payment-slip, #payment-slip * {
        visibility: visible;
    }
    #payment-slip {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
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
}
</style>