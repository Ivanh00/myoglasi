<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Verifikacija naloga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    Verifikujte svoj nalog
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Steknite poverenje kupaca i prodajte brže
                </p>
            </div>

            <!-- Benefits -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 text-white mb-12">
                <h2 class="text-2xl font-bold mb-6 text-center">Zašto verifikovati nalog?</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="text-center">
                        <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Veće poverenje</h3>
                        <p class="text-sm opacity-90">Kupci više veruju verifikovanim prodavcima</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Brža prodaja</h3>
                        <p class="text-sm opacity-90">Verifikovani oglasi se prodaju 50% brže</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Premium funkcije</h3>
                        <p class="text-sm opacity-90">Pristup ekskluzivnim opcijama</p>
                    </div>
                </div>
            </div>

            <!-- Verification Levels -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6 text-center">
                    Nivoi verifikacije
                </h2>
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Basic Verification -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100">Email verifikacija</h3>
                                <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">Osnovno</span>
                            </div>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Potvrda email adrese
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Osnovno poverenje
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Besplatno
                            </li>
                        </ul>
                        <button class="w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Već verifikovano
                        </button>
                    </div>

                    <!-- Phone Verification -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100">Telefon verifikacija</h3>
                                <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 px-2 py-1 rounded">Preporučeno</span>
                            </div>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                SMS verifikacija
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Plava kvačica
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                50 kredita
                            </li>
                        </ul>
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                            Verifikuj telefon
                        </button>
                    </div>

                    <!-- ID Verification -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-green-500 p-6">
                        <div class="absolute -mt-9 -ml-3 bg-green-500 text-white text-xs px-2 py-1 rounded">Potpuna zaštita</div>
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100">Identitet verifikacija</h3>
                                <span class="text-xs bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 px-2 py-1 rounded">Premium</span>
                            </div>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lična karta/pasoš
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Zlatna kvačica
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                200 kredita
                            </li>
                        </ul>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
                            Verifikuj identitet
                        </button>
                    </div>
                </div>
            </div>

            <!-- Verification Process -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 mb-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">
                    Kako funkcioniše verifikacija?
                </h2>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2 mr-4 mt-1">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">1</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-1">Izaberite nivo verifikacije</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Odaberite nivo verifikacije koji vam odgovara - od osnovnog do premium.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2 mr-4 mt-1">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">2</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-1">Pošaljite dokumente</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Učitajte tražene dokumente ili potvrdite telefon putem SMS-a.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2 mr-4 mt-1">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">3</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-1">Sačekajte odobrenje</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Naš tim će pregledati vaše dokumente u roku od 24 sata.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-green-100 dark:bg-green-900 rounded-full p-2 mr-4 mt-1">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-1">Dobijte oznaku verifikacije</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Nakon odobrenja, vaš profil će dobiti oznaku verifikacije.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">
                    Često postavljana pitanja
                </h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Da li su moji podaci sigurni?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Apsolutno. Svi dokumenti se čuvaju šifrovano i koriste se isključivo za verifikaciju.
                            Nakon verifikacije, dokumenti se brišu iz našeg sistema.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Koliko dugo traje verifikacija?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Email verifikacija je trenutna. Telefon verifikacija traje nekoliko minuta.
                            Verifikacija identiteta se obrađuje u roku od 24 sata.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Mogu li izgubiti verifikaciju?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Verifikacija može biti povučena ako kršite uslove korišćenja ili ako se utvrdi
                            da ste pružili lažne informacije.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>