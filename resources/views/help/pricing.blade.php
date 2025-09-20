<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cenovnik usluga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Pricing Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Transparentne cene za sve
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Izaberite plan koji vam najbolje odgovara
                </p>
            </div>

            <!-- Pricing Plans Grid -->
            <div class="grid gap-8 lg:grid-cols-3 mb-12">
                <!-- Pay Per Listing -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Po oglasu
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Idealno za povremene prodavce
                        </p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Setting::get('listing_fee_amount', 10) }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400 ml-1">RSD / oglas</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Oglas aktivan {{ \App\Models\Setting::get('listing_auto_expire_days', 60) }} dana
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Do {{ \App\Models\Setting::get('max_images_per_listing', 10) }} slika po oglasu
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Plaćate samo kada objavite
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bez mesečnih obaveza
                            </li>
                        </ul>
                        <button class="w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg py-3 font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Trenutni plan
                        </button>
                    </div>
                </div>

                <!-- Monthly Plan -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-2 border-blue-500 overflow-hidden relative">
                    <div class="absolute top-0 right-0 bg-blue-500 text-white px-3 py-1 text-sm rounded-bl-lg">
                        Popularno
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Mesečni plan
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Za redovne prodavce
                        </p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Setting::get('monthly_plan_price', 500) }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400 ml-1">RSD / mesec</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <strong>Neograničeno oglasa</strong>
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Prioritet u pretrazi
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Istaknut profil prodavca
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mesečna statistika
                            </li>
                        </ul>
                        <a href="{{ route('balance.plan-selection') }}" class="block w-full bg-blue-600 text-white rounded-lg py-3 font-medium hover:bg-blue-700 transition text-center">
                            Odaberi plan
                        </a>
                    </div>
                </div>

                <!-- Yearly Plan -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 text-sm rounded-bl-lg">
                        Ušteda 2 meseca
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Godišnji plan
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Za profesionalce
                        </p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Setting::get('yearly_plan_price', 5000) }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400 ml-1">RSD / godina</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <strong>Sve iz mesečnog plana</strong>
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verifikovan nalog
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Premium podrška
                            </li>
                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ušteda {{ number_format(\App\Models\Setting::get('monthly_plan_price', 500) * 12 - \App\Models\Setting::get('yearly_plan_price', 5000), 0, ',', '.') }} RSD
                            </li>
                        </ul>
                        <a href="{{ route('balance.plan-selection') }}" class="block w-full bg-green-600 text-white rounded-lg py-3 font-medium hover:bg-green-700 transition text-center">
                            Odaberi plan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Services -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Dodatne usluge
                </h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Istaknut oglas
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                    Vaš oglas na vrhu rezultata pretrage za 7 dana
                                </p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                    50 kredita
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Automatsko obnavljanje
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                    Oglas se automatski obnavlja svakih 30 dana
                                </p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                    30 kredita / mesec
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Turbo oglas
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                    Oglas se prikazuje na naslovnoj stranici 24h
                                </p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                    100 kredita
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Verifikacija profila
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                    Dobijte plavu kvačicu pored imena
                                </p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                    200 kredita
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credit Packages -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Paketi kredita
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Kupite kredite i koristite ih kad vam zatrebaju
                </p>
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">100</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mb-3">kredita</div>
                        <div class="text-xl font-semibold text-gray-900 dark:text-white mb-3">100 RSD</div>
                        <a href="{{ route('balance.payment-options') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Kupi
                        </a>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center border-2 border-blue-500">
                        <div class="absolute -mt-8 -ml-4 bg-blue-500 text-white text-xs px-2 py-1 rounded">+10% bonus</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">550</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mb-3">kredita</div>
                        <div class="text-xl font-semibold text-gray-900 dark:text-white mb-3">500 RSD</div>
                        <a href="{{ route('balance.payment-options') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Kupi
                        </a>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center">
                        <div class="absolute -mt-8 -ml-4 bg-green-500 text-white text-xs px-2 py-1 rounded">+20% bonus</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">1200</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mb-3">kredita</div>
                        <div class="text-xl font-semibold text-gray-900 dark:text-white mb-3">1000 RSD</div>
                        <a href="{{ route('balance.payment-options') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Kupi
                        </a>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center">
                        <div class="absolute -mt-8 -ml-4 bg-purple-500 text-white text-xs px-2 py-1 rounded">+30% bonus</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">2600</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mb-3">kredita</div>
                        <div class="text-xl font-semibold text-gray-900 dark:text-white mb-3">2000 RSD</div>
                        <a href="{{ route('balance.payment-options') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Kupi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>