<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400">Početna</a></li>
                    <li class="text-slate-400 dark:text-slate-600">/</li>
                    <li><a href="{{ route('help.faq') }}" class="text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400">Pomoć</a></li>
                    <li class="text-slate-400 dark:text-slate-600">/</li>
                    <li class="text-slate-900 dark:text-slate-100">Kako postaviti aukciju</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-gavel text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako postaviti aukciju</h1>
                            <p class="text-amber-100">Prodajte svoj proizvod putem uzbudljive aukcije</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- What is Auction -->
                    <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 mr-2"></i>
                            Šta je aukcija?
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">
                            Aukcija je način prodaje gde kupci licitiraju za vaš proizvod, a proizvod dobija onaj ko ponudi najvišu cenu do kraja aukcije.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center">
                                <i class="fas fa-clock text-3xl text-amber-600 dark:text-amber-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Vremenski ograničena</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Aukcije traju određeni broj dana</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-chart-line text-3xl text-amber-600 dark:text-amber-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Cena raste</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Kupci se nadmeću za proizvod</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-trophy text-3xl text-amber-600 dark:text-amber-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Pobednik uzima</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Najviša ponuda dobija proizvod</p>
                            </div>
                        </div>
                    </div>

                    <!-- Two Ways to Create -->
                    <div class="mb-8 border border-slate-200 dark:border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-route text-sky-500 mr-2"></i>
                            Dva načina kreiranja aukcije
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 rounded-lg p-4">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fas fa-plus-circle text-sky-600 dark:text-sky-400 mr-2"></i>Direktno kreiranje
                                </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                                    Kliknite na "Postavi" u navigaciji i izaberite "Aukcija"
                                </p>
                                <div class="bg-white dark:bg-slate-800 rounded p-2 inline-block">
                                    <span class="text-xs text-slate-600 dark:text-slate-400">Postavi → 🔨 Aukcija</span>
                                </div>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fas fa-exchange-alt text-amber-600 dark:text-amber-400 mr-2"></i>Pretvaranje oglasa
                                </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                                    Postojeći oglas možete pretvoriti u aukciju
                                </p>
                                <div class="bg-white dark:bg-slate-800 rounded p-2 inline-block">
                                    <span class="text-xs text-slate-600 dark:text-slate-400">Moj oglas → Pretvori u aukciju</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                            <p class="text-sm text-green-800 dark:text-green-300">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Napomena:</strong> Ako na aukciji nema ponuda, možete je vratiti nazad u običan oglas!
                            </p>
                        </div>
                    </div>

                    <!-- Steps -->
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Koraci za postavljanje aukcije</h2>

                    <!-- Step 1 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-amber-600 dark:text-amber-400 font-bold">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Odaberite način kreiranja</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Možete direktno kreirati aukciju ili pretvoriti postojeći oglas.
                                </p>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span class="text-slate-600 dark:text-slate-400">Direktno: Brže za nove proizvode</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span class="text-slate-600 dark:text-slate-400">Pretvaranje: Fleksibilno za postojeće oglase</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-amber-600 dark:text-amber-400 font-bold">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Popunite osnovne informacije</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Unesite sve potrebne informacije o proizvodu koji prodajete na aukciji.
                                </p>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-tag text-amber-500 mr-2 mt-1"></i>
                                        <span>Naslov i opis proizvoda</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-images text-amber-500 mr-2 mt-1"></i>
                                        <span>Kvalitetne fotografije (minimum 3)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-list text-amber-500 mr-2 mt-1"></i>
                                        <span>Kategorija i stanje proizvoda</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-map-marker-alt text-amber-500 mr-2 mt-1"></i>
                                        <span>Lokacija za preuzimanje</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-amber-600 dark:text-amber-400 font-bold">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Postavite parametre aukcije</h3>
                                <div class="space-y-4">
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-coins text-amber-500 mr-2"></i>Početna cena
                                        </h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Minimalna cena od koje počinje licitiranje. Preporučujemo da bude 30-50% od očekivane finalne cene.
                                        </p>
                                    </div>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-step-forward text-amber-500 mr-2"></i>Korak licitiranja
                                        </h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Minimalni iznos za koji se ponuda mora povećati. Obično 5-10% od početne cene.
                                        </p>
                                    </div>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-calendar-alt text-amber-500 mr-2"></i>Trajanje aukcije
                                        </h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Izaberite koliko dana će aukcija trajati (3, 5, 7 ili 10 dana).
                                        </p>
                                    </div>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-shield-alt text-amber-500 mr-2"></i>Rezervna cena (opciono)
                                        </h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Tajna minimalna cena ispod koje nećete prodati proizvod. Kupci ne vide ovaj iznos.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-amber-600 dark:text-amber-400 font-bold">4</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Pokrenite i upravljajte aukcijom</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Pregledajte sve postavke i kliknite "Pokreni aukciju". Aukcija počinje odmah!
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-green-800 dark:text-green-300 mb-2">
                                            <i class="fas fa-play-circle mr-2"></i>Pokretanje
                                        </h4>
                                        <p class="text-sm text-green-700 dark:text-green-400">
                                            Aukcija počinje odmah nakon pokretanja
                                        </p>
                                    </div>
                                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-amber-800 dark:text-amber-300 mb-2">
                                            <i class="fas fa-undo mr-2"></i>Vraćanje u oglas
                                        </h4>
                                        <p class="text-sm text-amber-700 dark:text-amber-400">
                                            Ako nema ponuda, možete vratiti u običan oglas
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div class="mt-12 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-lightbulb text-amber-500 mr-2"></i>
                            Saveti za uspešnu aukciju
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Postavite atraktivnu početnu cenu</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Niža početna cena privlači više učesnika</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Kvalitetne fotografije</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Dodajte što više fotografija proizvoda</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Detaljan opis</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Opišite stanje i karakteristike proizvoda</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Pravo vreme</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Završite aukciju vikendom ili uveče</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-8 text-center">
                        <div class="inline-flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('auctions.index') }}"
                               class="inline-flex items-center justify-center px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                <i class="fas fa-gavel mr-2"></i>
                                Pogledajte aktivne aukcije
                            </a>
                            <a href="{{ route('listings.my') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-list mr-2"></i>
                                Moji oglasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>