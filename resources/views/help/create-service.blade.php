<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-2 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}"
                            class="text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400">Početna</a>
                    </li>
                    <li class="text-slate-400 dark:text-slate-600">/</li>
                    <li><a href="{{ route('help.faq') }}"
                            class="text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400">Pomoć</a>
                    </li>
                    <li class="text-slate-400 dark:text-slate-600">/</li>
                    <li class="text-slate-900 dark:text-slate-100">Kako postaviti uslugu</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-2 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-tools text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako postaviti uslugu</h1>
                            <p class="text-slate-200">Ponudite svoje veštine i znanje drugima</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-2 sm:p-8">
                    <!-- What are Services -->
                    <div
                        class="mb-8 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-info-circle text-slate-600 dark:text-slate-400 mr-2"></i>
                            Šta su usluge?
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">
                            Usluge su način da ponudite svoje profesionalne veštine, zanate ili bilo koji rad koji
                            možete obaviti za druge korisnike.
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                            <div>
                                <i class="fas fa-laptop-code text-2xl text-slate-600 dark:text-slate-400 mb-2"></i>
                                <p class="text-sm text-slate-600 dark:text-slate-400">IT usluge</p>
                            </div>
                            <div>
                                <i class="fas fa-hammer text-2xl text-slate-600 dark:text-slate-400 mb-2"></i>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Majstorski radovi</p>
                            </div>
                            <div>
                                <i class="fas fa-graduation-cap text-2xl text-slate-600 dark:text-slate-400 mb-2"></i>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Podučavanje</p>
                            </div>
                            <div>
                                <i class="fas fa-car text-2xl text-slate-600 dark:text-slate-400 mb-2"></i>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Transport</p>
                            </div>
                        </div>
                    </div>

                    <!-- Steps -->
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Koraci za postavljanje usluge
                    </h2>

                    <!-- Step 1 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-slate-100 dark:bg-slate-700/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-slate-600 dark:text-slate-400 font-bold">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Kliknite na
                                    "Ponudi uslugu"</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Na početnoj stranici ili u meniju, pronađite opciju "Ponudi uslugu" ili idite
                                    direktno na stranicu usluga.
                                </p>
                                <a href="{{ route('services.index') }}"
                                    class="inline-flex items-center text-sky-600 dark:text-sky-400 hover:underline">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Pogledajte postojeće usluge
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-slate-100 dark:bg-slate-700/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-slate-600 dark:text-slate-400 font-bold">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Izaberite
                                    kategoriju usluge</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Odaberite kategoriju koja najbolje opisuje vašu uslugu:
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-900/50 rounded-lg">
                                        <i class="fas fa-home text-slate-500 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">Kućni poslovi i popravke</span>
                                    </div>
                                    <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-900/50 rounded-lg">
                                        <i class="fas fa-laptop text-slate-500 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">IT i programiranje</span>
                                    </div>
                                    <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-900/50 rounded-lg">
                                        <i class="fas fa-paint-brush text-slate-500 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">Dizajn i kreativnost</span>
                                    </div>
                                    <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-900/50 rounded-lg">
                                        <i class="fas fa-chalkboard-teacher text-slate-500 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">Edukacija i obuka</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-slate-100 dark:bg-slate-700/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-slate-600 dark:text-slate-400 font-bold">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Opišite vašu
                                    uslugu</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Unesite detaljne informacije o usluzi koju nudite:
                                </p>
                                <div class="space-y-3">
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">📝 Naslov usluge
                                        </h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Kratak i jasan naziv koji opisuje šta nudite
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                            Primer: "Profesionalno krečenje stanova i kuća"
                                        </p>
                                    </div>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">📋 Detaljan opis
                                        </h4>
                                        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                            <li>• Šta tačno uključuje vaša usluga</li>
                                            <li>• Koliko iskustva imate</li>
                                            <li>• Koje materijale/alate koristite</li>
                                            <li>• Vremenski okvir za završetak</li>
                                        </ul>
                                    </div>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">💰 Način naplate
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <label class="flex items-center text-slate-600 dark:text-slate-400">
                                                <input type="radio" class="mr-2" checked>
                                                <span>Po satu (npr. 1500 RSD/sat)</span>
                                            </label>
                                            <label class="flex items-center text-slate-600 dark:text-slate-400">
                                                <input type="radio" class="mr-2">
                                                <span>Po projektu (fiksna cena)</span>
                                            </label>
                                            <label class="flex items-center text-slate-600 dark:text-slate-400">
                                                <input type="radio" class="mr-2">
                                                <span>Po dogovoru</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-slate-100 dark:bg-slate-700/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-slate-600 dark:text-slate-400 font-bold">4</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Dodajte
                                    portfolio</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Pokažite primere vašeg rada da pridobijete poverenje klijenata:
                                </p>
                                <div
                                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <h4 class="font-medium text-green-800 dark:text-green-300 mb-2">
                                        <i class="fas fa-images mr-2"></i>Šta dodati u portfolio:
                                    </h4>
                                    <ul class="text-sm text-green-700 dark:text-green-400 space-y-1">
                                        <li>✓ Fotografije završenih radova (pre i posle)</li>
                                        <li>✓ Sertifikate i diplome</li>
                                        <li>✓ Preporuke zadovoljnih klijenata</li>
                                        <li>✓ Video demonstracije vašeg rada</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-slate-100 dark:bg-slate-700/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-slate-600 dark:text-slate-400 font-bold">5</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Postavite
                                    dostupnost i lokaciju</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Definišite gde i kada možete pružati uslugu:
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-map-marker-alt text-slate-500 mr-2"></i>Lokacija
                                        </h4>
                                        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                            <li>• Grad/opština</li>
                                            <li>• Radius delovanja</li>
                                            <li>• Online/Remote opcija</li>
                                        </ul>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-clock text-slate-500 mr-2"></i>Dostupnost
                                        </h4>
                                        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                            <li>• Radni dani/vikend</li>
                                            <li>• Radno vreme</li>
                                            <li>• Hitni pozivi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div
                        class="mt-12 bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-900/20 dark:to-gray-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-star text-amber-500 mr-2"></i>
                            Saveti za uspešnu uslugu
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-certificate text-amber-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Budite profesionalni
                                    </h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Odgovarajte brzo na upite,
                                        budite ljubazni i tačni</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-handshake text-amber-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Gradite poverenje</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Tražite recenzije od
                                        zadovoljnih klijenata</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-tags text-amber-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Konkurentne cene</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Istražite tržište i postavite
                                        fer cene</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-sync-alt text-amber-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Redovno ažurirajte</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Dodajte nove radove u
                                        portfolio i ažurirajte cene</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-8 text-center">
                        <div class="inline-flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('services.create') }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Ponudi uslugu sada
                            </a>
                            <a href="{{ route('services.index') }}"
                                class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-search mr-2"></i>
                                Pregledaj postojeće usluge
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
