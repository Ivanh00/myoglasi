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
                    <li class="text-slate-900 dark:text-slate-100">Kredit sistem</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-coins text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kredit sistem</h1>
                            <p class="text-green-100">Sve što trebate znati o kreditima na PazAriO</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- What are Credits -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-question-circle text-sky-500 mr-2"></i>
                            Šta su krediti?
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">
                            Krediti su virtuelna valuta PazAriO platforme koja vam omogućava da koristite premium usluge i promocije za vaše oglase.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-sky-50 dark:bg-sky-900/20 rounded-lg p-4 text-center">
                                <i class="fas fa-lock text-3xl text-sky-600 dark:text-sky-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Bezbedni</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Zaštićene transakcije</p>
                            </div>
                            <div class="bg-sky-50 dark:bg-sky-900/20 rounded-lg p-4 text-center">
                                <i class="fas fa-exchange-alt text-3xl text-sky-600 dark:text-sky-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Prenosivi</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Možete ih podeliti</p>
                            </div>
                            <div class="bg-sky-50 dark:bg-sky-900/20 rounded-lg p-4 text-center">
                                <i class="fas fa-infinity text-3xl text-sky-600 dark:text-sky-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-1">Bez isteka</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Krediti ne istič</p>
                            </div>
                        </div>
                    </div>

                    <!-- How to Get Credits -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-wallet text-green-500 mr-2"></i>
                            Kako dobiti kredite?
                        </h2>

                        <!-- Purchase Credits -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">1. Kupovina kredita</h3>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                                <p class="text-slate-700 dark:text-slate-300 mb-3">Najbrži način da dobijete kredite:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-credit-card text-green-600 dark:text-green-400 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">Platna kartica</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-university text-green-600 dark:text-green-400 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">Bankovna uплата</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fab fa-paypal text-green-600 dark:text-green-400 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">PayPal</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-mobile-alt text-green-600 dark:text-green-400 mr-3"></i>
                                        <span class="text-slate-700 dark:text-slate-300">SMS uplata</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earn Credits -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">2. Zarada kredita</h3>
                            <div class="space-y-3">
                                <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-slate-700 dark:text-slate-300">
                                            <i class="fas fa-user-plus text-sky-500 mr-2"></i>Registracija
                                        </span>
                                        <span class="text-green-600 dark:text-green-400 font-bold">+10 kredita</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Dobijate bonus kredite pri registraciji</p>
                                </div>
                                <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-slate-700 dark:text-slate-300">
                                            <i class="fas fa-check-circle text-sky-500 mr-2"></i>Verifikacija naloga
                                        </span>
                                        <span class="text-green-600 dark:text-green-400 font-bold">+20 kredita</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Verifikujte email i telefon</p>
                                </div>
                                <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-slate-700 dark:text-slate-300">
                                            <i class="fas fa-share-alt text-sky-500 mr-2"></i>Preporuka prijatelja
                                        </span>
                                        <span class="text-green-600 dark:text-green-400 font-bold">+5 kredita</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Za svakog prijatelja koji se registruje</p>
                                </div>
                                <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-slate-700 dark:text-slate-300">
                                            <i class="fas fa-calendar-check text-sky-500 mr-2"></i>Dnevna prijava
                                        </span>
                                        <span class="text-green-600 dark:text-green-400 font-bold">+1 kredit</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Prijavite se svaki dan za bonus</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- What Credits Are Used For -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-shopping-cart text-purple-500 mr-2"></i>
                            Za šta se koriste krediti?
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-800 dark:text-purple-300 mb-2">
                                    <i class="fas fa-star mr-2"></i>Isticanje oglasa
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• TOP pozicija - 15 kredita</li>
                                    <li>• Istaknut oglas - 10 kredita</li>
                                    <li>• VRH kategorije - 8 kredita</li>
                                </ul>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-800 dark:text-purple-300 mb-2">
                                    <i class="fas fa-rocket mr-2"></i>Promocije
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Hitno oznaka - 5 kredita</li>
                                    <li>• Auto-podizanje - 3 kredita</li>
                                    <li>• Produženje oglasa - 2 kredita</li>
                                </ul>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-800 dark:text-purple-300 mb-2">
                                    <i class="fas fa-gavel mr-2"></i>Aukcije
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Pokretanje aukcije - 5 kredita</li>
                                    <li>• Isticanje aukcije - 10 kredita</li>
                                    <li>• Produženje aukcije - 3 kredita</li>
                                </ul>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-800 dark:text-purple-300 mb-2">
                                    <i class="fas fa-tools mr-2"></i>Usluge
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Postavljanje usluge - 3 kredita</li>
                                    <li>• Isticanje usluge - 8 kredita</li>
                                    <li>• Prošireni opis - 2 kredita</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Credit Packages -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-tags text-amber-500 mr-2"></i>
                            Paketi kredita
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="border-2 border-slate-200 dark:border-slate-700 rounded-lg p-4 hover:border-sky-300 dark:hover:border-sky-600 transition-colors">
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Početni</h3>
                                    <div class="text-3xl font-bold text-sky-600 dark:text-sky-400 my-2">50</div>
                                    <p class="text-slate-600 dark:text-slate-400">kredita</p>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-2">500 RSD</p>
                                </div>
                                <button class="w-full py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                    Kupi sada
                                </button>
                            </div>

                            <div class="border-2 border-amber-300 dark:border-amber-600 rounded-lg p-4 relative">
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    POPULARNO
                                </div>
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Standard</h3>
                                    <div class="text-3xl font-bold text-sky-600 dark:text-sky-400 my-2">100</div>
                                    <p class="text-slate-600 dark:text-slate-400">kredita</p>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-2">900 RSD</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Uštedite 100 RSD</p>
                                </div>
                                <button class="w-full py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                    Kupi sada
                                </button>
                            </div>

                            <div class="border-2 border-slate-200 dark:border-slate-700 rounded-lg p-4 hover:border-sky-300 dark:hover:border-sky-600 transition-colors">
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Premium</h3>
                                    <div class="text-3xl font-bold text-sky-600 dark:text-sky-400 my-2">200</div>
                                    <p class="text-slate-600 dark:text-slate-400">kredita</p>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-2">1700 RSD</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Uštedite 300 RSD</p>
                                </div>
                                <button class="w-full py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                    Kupi sada
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="mt-12 border-t border-slate-200 dark:border-slate-700 pt-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Često postavljana pitanja</h3>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Da li krediti istič?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <p class="p-4 text-slate-600 dark:text-slate-400">
                                    Ne, krediti nemaju rok trajanja i ostaju na vašem nalogu dok ih ne potrošite.
                                </p>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Mogu li podeliti kredite sa prijateljima?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <p class="p-4 text-slate-600 dark:text-slate-400">
                                    Da, možete poslati kredite drugim korisnicima preko opcije "Pošalji kredite" u vašem profilu.
                                </p>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Šta se dešava sa neiskorišćenim kreditima?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <p class="p-4 text-slate-600 dark:text-slate-400">
                                    Krediti ostaju na vašem nalogu i mogu se koristiti u bilo kom trenutku u budućnosti.
                                </p>
                            </details>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-8 text-center">
                        <div class="inline-flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-coins mr-2"></i>
                                Kupi kredite
                            </a>
                            <a href="{{ route('help.earn-credits') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-gift mr-2"></i>
                                Kako zaraditi kredite
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>