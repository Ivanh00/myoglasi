<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Promocije oglasa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    Istaknite svoj oglas
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Povećajte vidljivost i prodajte brže uz naše opcije promocije
                </p>
            </div>

            <!-- Main Promotion Options from Database -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">

                <!-- Featured Homepage (Top glavne strane) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-2 border-red-500 overflow-hidden relative">
                    <div class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 text-sm rounded-bl-lg">
                        Najjača promocija
                    </div>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">Top glavne strane</h3>
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
                                <span class="text-gray-700 dark:text-gray-300">Prikazuje se na početnoj stranici</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Crvena "ISTAKNUT" oznaka</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">10x više pregleda</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Trajanje: {{ \App\Models\Setting::get('promotion_featured_homepage_days', 1) }} dan{{ \App\Models\Setting::get('promotion_featured_homepage_days', 1) > 1 ? 'a' : '' }}</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                                {{ \App\Models\Setting::get('promotion_featured_homepage_price', 100) }} kredita
                            </div>
                            <a href="{{ route('listings.my') }}" class="block w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 rounded-lg transition text-center">
                                Aktiviraj
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Featured Category (Top kategorije) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-blue-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">Top kategorije</h3>
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
                                <span class="text-gray-700 dark:text-gray-300">Prvi na listi u kategoriji</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Plava "TOP" oznaka</span>
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
                                <span class="text-gray-700 dark:text-gray-300">Trajanje: {{ \App\Models\Setting::get('promotion_featured_category_days', 3) }} dana</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                                {{ \App\Models\Setting::get('promotion_featured_category_price', 75) }} kredita
                            </div>
                            <a href="{{ route('listings.my') }}" class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 rounded-lg transition text-center">
                                Aktiviraj
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Highlighted (Istaknut oglas) -->
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
                                <span class="text-gray-700 dark:text-gray-300">Žuta pozadina u rezultatima</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Žuta "OZNAČEN" oznaka</span>
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
                                <span class="text-gray-700 dark:text-gray-300">Trajanje: {{ \App\Models\Setting::get('promotion_highlighted_days', 7) }} dana</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                                {{ \App\Models\Setting::get('promotion_highlighted_price', 50) }} kredita
                            </div>
                            <a href="{{ route('listings.my') }}" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 rounded-lg transition text-center">
                                Aktiviraj
                            </a>
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

                    <!-- Auto Refresh -->
                    <div class="border dark:border-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Automatsko osvežavanje</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Oglas se vraća na vrh liste</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Vaš oglas se automatski osvežava svakih {{ \App\Models\Setting::get('promotion_auto_refresh_days', 7) }} dana i vraća na vrh liste u kategoriji.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ \App\Models\Setting::get('promotion_auto_refresh_price', 30) }} kredita
                            </span>
                            <a href="{{ route('listings.my') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                Aktiviraj
                            </a>
                        </div>
                    </div>

                    <!-- Double Images -->
                    <div class="border dark:border-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Dupliraj broj slika</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Dodajte više fotografija</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Možete dodati duplo više slika u vaš oglas (do {{ \App\Models\Setting::get('max_images_per_listing', 10) * 2 }} slika).
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ \App\Models\Setting::get('promotion_double_images_price', 25) }} kredita
                            </span>
                            <a href="{{ route('listings.my') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                                Aktiviraj
                            </a>
                        </div>
                    </div>

                    <!-- Extended Duration -->
                    <div class="border dark:border-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-orange-100 dark:bg-orange-900 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Produžena trajnost</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Oglas traje duže</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Produžite trajanje vašeg oglasa za dodatnih {{ \App\Models\Setting::get('promotion_extended_duration_days', 30) }} dana.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ \App\Models\Setting::get('promotion_extended_duration_price', 40) }} kredita
                            </span>
                            <a href="{{ route('listings.my') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">
                                Aktiviraj
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-8 mb-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">
                    Kako funkcioniše?
                </h2>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="text-center">
                        <div class="bg-white dark:bg-gray-800 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">1</span>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Idite na Moji oglasi</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Otvorite stranicu sa vašim oglasima
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white dark:bg-gray-800 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">2</span>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Kliknite na Promocije</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Pronađite dugme "Promocije" kod željenog oglasa
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white dark:bg-gray-800 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">3</span>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Izaberite i platite</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Odaberite željene promocije i platite kreditima
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 text-white">
                <h2 class="text-2xl font-bold mb-6">Zašto promovirati oglase?</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">5x</div>
                        <p>Više pregleda za Top oglase</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">70%</div>
                        <p>Brža prodaja sa promocijom</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">3x</div>
                        <p>Više kontakata od kupaca</p>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-12">
                <a href="{{ route('listings.my') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Promovišite vaše oglase sada
                </a>
            </div>
        </div>
    </div>
</x-app-layout>