<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Promocije oglasa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    Istaknite svoj oglas
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Povećajte vidljivost i prodajte brže uz naše opcije promocije
                </p>
            </div>

            <!-- Promotion Options -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
                <!-- Highlighted Listing -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-yellow-400 overflow-hidden">
                    <div class="bg-yellow-400 text-black p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">Istaknut oglas</h3>
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Žuta pozadina u rezultatima pretrage</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Zvezdica pored naslova</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Prikazuje se iznad običnih oglasa</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Trajanje: 7 dana</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">50 kredita</div>
                            <button class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 rounded-lg transition">
                                Istakni oglas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Top Listing -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-red-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">Top oglas</h3>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Prvi na listi u kategoriji</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Crveni okvir i "TOP" oznaka</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">3x više pregleda</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Trajanje: 3 dana</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">75 kredita</div>
                            <button class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 rounded-lg transition">
                                Postavi kao Top
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Premium Listing -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-purple-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">Premium oglas</h3>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Prikazuje se na početnoj stranici</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Premium oznaka i okvir</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">5x više pregleda</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Trajanje: 24 sata</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">100 kredita</div>
                            <button class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-3 rounded-lg transition">
                                Aktiviraj Premium
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Promotion Services -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 mb-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">
                    Dodatne opcije promocije
                </h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="border dark:border-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Automatsko obnavljanje</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Oglas se vraća na vrh liste</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Vaš oglas se automatski obnavlja svakih 7 dana i vraća na vrh liste u kategoriji.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">30 kredita / 7 dana</span>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                Aktiviraj
                            </button>
                        </div>
                    </div>

                    <div class="border dark:border-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Boost pregleda</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Povećajte broj pregleda</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Vaš oglas se prikazuje češće u preporučenim oglasima i sličnim proizvodima.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">25 kredita / 3 dana</span>
                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                Aktiviraj
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 text-white">
                <h2 class="text-2xl font-bold mb-6">Zašto promovirati oglase?</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">3x</div>
                        <p>Više pregleda za istaknute oglase</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">65%</div>
                        <p>Brža prodaja sa promocijom</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">2x</div>
                        <p>Više kontakata od kupaca</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>