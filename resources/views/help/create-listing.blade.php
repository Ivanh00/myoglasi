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
                    <li class="text-slate-900 dark:text-slate-100">Kako postaviti oglas</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-sky-500 to-sky-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-clipboard-list text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako postaviti oglas</h1>
                            <p class="text-sky-100">Vodič korak po korak za postavljanje oglasa na PazAriO</p>
                        </div>
                    </div>
                </div>

                <!-- Steps -->
                <div class="p-6 sm:p-8">
                    <!-- Step 1 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sky-600 dark:text-sky-400 font-bold">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Prijavite se na svoj nalog</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Pre postavljanja oglasa, potrebno je da budete prijavljeni na svoj nalog. Ako nemate nalog, možete ga besplatno kreirati.
                                </p>
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                                    <p class="text-sm text-amber-800 dark:text-amber-300">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        <strong>Savet:</strong> Verifikovani nalozi imaju veću vidljivost i poverenje kupaca.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sky-600 dark:text-sky-400 font-bold">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Kliknite na "Dodaj oglas"</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    U glavnom meniju ili na početnoj stranici, pronađite dugme "Dodaj oglas" i kliknite na njega.
                                </p>
                                <img src="/images/help/add-listing-button.png" alt="Dodaj oglas dugme"
                                     class="rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm max-w-full h-auto">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sky-600 dark:text-sky-400 font-bold">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Izaberite kategoriju</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Odaberite odgovarajuću kategoriju i potkategoriju za vaš proizvod. Ovo pomaže kupcima da lakše pronađu vaš oglas.
                                </p>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Automobili i delovi</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Nekretnine</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Elektronika</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>I mnoge druge...</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sky-600 dark:text-sky-400 font-bold">4</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Popunite informacije o oglasu</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Unesite sve potrebne informacije o proizvodu koji prodajete:
                                </p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 space-y-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-heading text-sky-500 w-6"></i>
                                        <span class="ml-3 text-slate-700 dark:text-slate-300"><strong>Naslov:</strong> Kratak i jasan naziv proizvoda</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-align-left text-sky-500 w-6"></i>
                                        <span class="ml-3 text-slate-700 dark:text-slate-300"><strong>Opis:</strong> Detaljan opis sa svim karakteristikama</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-euro-sign text-sky-500 w-6"></i>
                                        <span class="ml-3 text-slate-700 dark:text-slate-300"><strong>Cena:</strong> Postavite realnu cenu ili "Po dogovoru"</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-sky-500 w-6"></i>
                                        <span class="ml-3 text-slate-700 dark:text-slate-300"><strong>Lokacija:</strong> Grad i opština</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sky-600 dark:text-sky-400 font-bold">5</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Dodajte fotografije</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Kvalitetne fotografije značajno povećavaju šanse za prodaju:
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                        <h4 class="font-semibold text-green-800 dark:text-green-300 mb-2">
                                            <i class="fas fa-check-circle mr-2"></i>Preporučeno
                                        </h4>
                                        <ul class="text-sm text-green-700 dark:text-green-400 space-y-1">
                                            <li>• Svetle i jasne fotografije</li>
                                            <li>• Fotografije sa svih strana</li>
                                            <li>• Minimum 3-5 fotografija</li>
                                            <li>• Visoka rezolucija</li>
                                        </ul>
                                    </div>
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                        <h4 class="font-semibold text-red-800 dark:text-red-300 mb-2">
                                            <i class="fas fa-times-circle mr-2"></i>Izbegavajte
                                        </h4>
                                        <ul class="text-sm text-red-700 dark:text-red-400 space-y-1">
                                            <li>• Mutne fotografije</li>
                                            <li>• Fotografije sa interneta</li>
                                            <li>• Loše osvetljenje</li>
                                            <li>• Samo jedna fotografija</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sky-600 dark:text-sky-400 font-bold">6</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Pregledajte i objavite</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Pre objave, pregledajte sve unete informacije. Možete odabrati i dodatne opcije promocije:
                                </p>
                                <div class="space-y-3">
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                                <i class="fas fa-star text-amber-500 mr-2"></i>Istaknut oglas
                                            </span>
                                            <span class="text-sky-600 dark:text-sky-400">10 kredita</span>
                                        </div>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">Vaš oglas će biti istaknut na početnoj stranici</p>
                                    </div>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                                <i class="fas fa-arrow-up text-green-500 mr-2"></i>TOP oglas
                                            </span>
                                            <span class="text-sky-600 dark:text-sky-400">15 kredita</span>
                                        </div>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">Oglas će biti među prvima u rezultatima pretrage</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-12 bg-gradient-to-r from-sky-50 to-blue-50 dark:from-sky-900/20 dark:to-blue-900/20 rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-3">Spremni ste za postavljanje oglasa?</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">
                                Pratite ove korake i vaš oglas će biti vidljiv hiljadama potencijalnih kupaca!
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('listings.create') }}"
                                   class="inline-flex items-center justify-center px-6 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Postavite oglas sada
                                </a>
                                <a href="{{ route('help.faq') }}"
                                   class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i class="fas fa-question-circle mr-2"></i>
                                    Često postavljana pitanja
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Related Articles -->
                    <div class="mt-12 border-t border-slate-200 dark:border-slate-700 pt-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Povezani članci</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="{{ route('help.create-auction') }}"
                               class="group block p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:border-sky-300 dark:hover:border-sky-600 transition-colors">
                                <h4 class="font-medium text-slate-900 dark:text-slate-100 group-hover:text-sky-600 dark:group-hover:text-sky-400 mb-1">
                                    <i class="fas fa-gavel mr-2"></i>Kako postaviti aukciju
                                </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Saznajte kako da prodate proizvod putem aukcije</p>
                            </a>
                            <a href="{{ route('help.promotions') }}"
                               class="group block p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:border-sky-300 dark:hover:border-sky-600 transition-colors">
                                <h4 class="font-medium text-slate-900 dark:text-slate-100 group-hover:text-sky-600 dark:group-hover:text-sky-400 mb-1">
                                    <i class="fas fa-rocket mr-2"></i>Promocije oglasa
                                </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Kako da povećate vidljivost vašeg oglasa</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>