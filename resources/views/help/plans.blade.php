<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Planovi naplate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    Izaberite plan koji vam odgovara
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Fleksibilni planovi prilagođeni vašim potrebama
                </p>
            </div>

            <!-- Plan Comparison Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-12">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    Karakteristike
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    Osnovni plan
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-slate-100 bg-blue-50 dark:bg-blue-900/20">
                                    Mesečni plan
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    Godišnji plan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Cena
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ \App\Models\Setting::get('listing_fee_amount', 10) }} RSD
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 block">
                                        po oglasu
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ \App\Models\Setting::get('monthly_plan_price', 500) }} RSD
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 block">
                                        mesečno
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ \App\Models\Setting::get('yearly_plan_price', 5000) }} RSD
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 block">
                                        godišnje
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Broj oglasa
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                    1 oglas
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <span class="text-green-600 dark:text-green-400 font-semibold">Neograničeno</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-green-600 dark:text-green-400 font-semibold">Neograničeno</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Trajanje oglasa
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                    {{ \App\Models\Setting::get('listing_auto_expire_days', 60) }} dana
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    {{ \App\Models\Setting::get('listing_auto_expire_days', 60) }} dana
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                    {{ \App\Models\Setting::get('listing_auto_expire_days', 60) }} dana
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Broj slika po oglasu
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                    {{ \App\Models\Setting::get('max_images_per_listing', 10) }}
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    {{ \App\Models\Setting::get('max_images_per_listing', 10) }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                    {{ \App\Models\Setting::get('max_images_per_listing', 10) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Prioritet u pretrazi
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Istaknut profil
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Mesečna statistika
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Verifikovan nalog
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Premium podrška
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <svg class="w-5 h-5 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition">
                                        Trenutno aktivan
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center bg-blue-50 dark:bg-blue-900/20">
                                    <a href="{{ route('balance.plan-selection') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        Odaberi plan
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('balance.plan-selection') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition inline-block">
                                        Odaberi plan
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FAQs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">
                    Često postavljana pitanja o planovima
                </h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Mogu li promeniti plan u bilo kom trenutku?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Da, možete promeniti plan kada god želite. Ako prelazite na viši plan, razlika će biti
                            obračunata proporcionalno. Pri prelasku na niži plan, promena stupa na snagu od sledećeg
                            obračunskog perioda.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Šta se dešava sa mojim oglasima ako prekinem pretplatu?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Vaši postojeći oglasi ostaju aktivni do isteka. Nakon prekida pretplate, prelazite na
                            osnovni plan gde plaćate po oglasu.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Da li mogu dobiti povraćaj novca?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Povraćaj novca je moguć u roku od 7 dana od kupovine plana, pod uslovom da niste
                            objavili više od 3 oglasa u tom periodu.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                            Kako funkcioniše godišnji plan?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Godišnji plan se plaća unapred za celu godinu. Dobijate sve benefite mesečnog plana
                            plus dodatne pogodnosti kao što su verifikovan nalog i premium podrška, uz uštedu od
                            {{ number_format(\App\Models\Setting::get('monthly_plan_price', 500) * 12 - \App\Models\Setting::get('yearly_plan_price', 5000), 0, ',', '.') }} RSD godišnje.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>