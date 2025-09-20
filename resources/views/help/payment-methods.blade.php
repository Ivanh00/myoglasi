<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Načini plaćanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    Sigurni i jednostavni načini plaćanja
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Izaberite način plaćanja koji vam najviše odgovara
                </p>
            </div>

            <!-- Payment Methods Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
                <!-- Credit/Debit Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 mr-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Platne kartice</h3>
                                <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded">Instant</span>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Sigurno i brzo plaćanje kreditnim ili debitnim karticama.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" class="h-8">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-8">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="American Express" class="h-8">
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Instant odobravanje
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                3D Secure zaštita
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bez dodatnih troškova
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bank Transfer -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-3 mr-4">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Bankovna uplata</h3>
                                <span class="inline-block bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs px-2 py-1 rounded">1-2 dana</span>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Uplata preko bankovnog računa ili u banci.
                        </p>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4 text-sm">
                            <div class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Podaci za uplatu:</div>
                            <div class="space-y-1 text-gray-600 dark:text-gray-400">
                                <div>Primalac: <span class="font-mono">{{ \App\Models\Setting::get('company_name', 'PazAriO d.o.o.') }}</span></div>
                                <div>Račun: <span class="font-mono">{{ \App\Models\Setting::get('bank_account_number', '265-0000000003456-78') }}</span></div>
                                <div>Banka: <span class="font-mono">{{ \App\Models\Setting::get('bank_name', 'Intesa Banka') }}</span></div>
                            </div>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sigurno i pouzdano
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Dostupno 24/7
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Obrada 1-2 radna dana
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- PayPal -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 mr-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.72a.765.765 0 0 1 .757-.663h7.266c2.24 0 3.999.715 5.077 2.061.93 1.163 1.32 2.764 1.088 4.469-.405 3.021-2.79 5.086-5.771 5.086h-2.79a.765.765 0 0 0-.756.664l-.903 5.73a.641.641 0 0 1-.633.54l-.203-.003zm14.348-14.585c-.437 3.27-2.946 5.445-6.282 5.445H13.89a.957.957 0 0 0-.945.83l-1.003 6.352a.48.48 0 0 1-.475.405H8.32a.48.48 0 0 1-.474-.555l.273-1.73.175-1.107.808-5.13a.956.956 0 0 1 .945-.83h.613c3.938 0 7.025-1.586 7.764-6.293.034-.216.06-.425.079-.628z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">PayPal</h3>
                                <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded">Instant</span>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Brzo i sigurno plaćanje preko PayPal računa.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Instant odobravanje
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Zaštita kupca
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Međunarodno plaćanje
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Crypto -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-orange-100 dark:bg-orange-900 rounded-full p-3 mr-4">
                                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Kriptovalute</h3>
                                <span class="inline-block bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300 text-xs px-2 py-1 rounded">15-30 min</span>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Plaćanje Bitcoin-om i drugim kriptovalutama.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">BTC</span>
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">ETH</span>
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">USDT</span>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Anonimno plaćanje
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Niske provizije
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Zahteva blockchain potvrdu
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Mobile Payment -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3 mr-4">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Mobilno plaćanje</h3>
                                <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded">Instant</span>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Platite direktno sa vašeg mobilnog telefona.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">mts</span>
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">Telenor</span>
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">A1</span>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bez kreditne kartice
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Na mesečni račun
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Jednostavno i brzo
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Gift Cards -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-pink-100 dark:bg-pink-900 rounded-full p-3 mr-4">
                                <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Poklon vaučeri</h3>
                                <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded">Instant</span>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Iskoristite poklon vaučer za dopunu kredita.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Idealno za poklon
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Instant aktivacija
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bez roka trajanja
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-12">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Vaša sigurnost je naš prioritet
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Svi načini plaćanja su sigurni i šifrovani. Koristimo najnovije sigurnosne tehnologije
                            za zaštitu vaših podataka. PCI DSS sertifikovano.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-8 text-center">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    Imate pitanja o plaćanju?
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Naš tim za podršku je tu da vam pomogne sa bilo kojim pitanjem vezanim za plaćanje.
                </p>
                <a href="{{ route('messages.inbox') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Kontaktirajte podršku
                </a>
            </div>
        </div>
    </div>
</x-app-layout>