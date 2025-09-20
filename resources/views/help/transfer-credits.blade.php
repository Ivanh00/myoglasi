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
                    <li class="text-slate-900 dark:text-slate-100">Kako podeliti kredite</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-share-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako podeliti kredite</h1>
                            <p class="text-blue-100">Pošaljite kredite prijateljima ili članovima porodice</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- What is Credit Transfer -->
                    <div class="mb-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-exchange-alt text-blue-600 dark:text-blue-400 mr-2"></i>
                            Šta je transfer kredita?
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">
                            Transfer kredita vam omogućava da podelite vaše kredite sa drugim korisnicima PazAriO platforme. Ovo je korisno kada želite da pomognete prijateljima ili članovima porodice da promovišu svoje oglase.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center">
                                <i class="fas fa-bolt text-3xl text-blue-600 dark:text-blue-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Instant</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Transfer je trenutni</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-lock text-3xl text-blue-600 dark:text-blue-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Bezbedan</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Zaštićen sistemom</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-undo text-3xl text-blue-600 dark:text-blue-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Nepovratni</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Ne može se poništiti</p>
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-check-double text-green-500 mr-2"></i>
                            Uslovi za transfer
                        </h2>
                        <div class="space-y-3">
                            <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Verifikovan nalog</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Vaš nalog mora biti verifikovan (email ili telefon)</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Minimum 10 kredita</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Možete poslati minimum 10 kredita u jednoj transakciji</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Aktivan primalac</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Korisnik kome šaljete mora postojati na platformi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Steps -->
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Koraci za slanje kredita</h2>

                    <!-- Step 1 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Idite na vaš profil</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Kliknite na vašu profilnu sliku ili ime u gornjem desnom uglu, zatim izaberite "Moj profil".
                                </p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 inline-block">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-slate-300 dark:bg-slate-600 rounded-full"></div>
                                        <span class="text-slate-700 dark:text-slate-300">Vaše ime</span>
                                        <i class="fas fa-chevron-down text-slate-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Pronađite sekciju "Krediti"</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Na stranici profila, videćete vašu trenutnu količinu kredita i opciju za transfer.
                                </p>
                                <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-slate-700 dark:text-slate-300 font-medium">Vaš balans:</span>
                                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">125 kredita</span>
                                    </div>
                                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-paper-plane mr-2"></i>Pošalji kredite
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Unesite podatke za transfer</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Popunite formu sa podacima o transferu.
                                </p>
                                <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-user mr-1"></i> Korisničko ime ili email primaoca
                                        </label>
                                        <input type="text" placeholder="npr. marko123 ili marko@email.com"
                                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                                               disabled>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-coins mr-1"></i> Količina kredita
                                        </label>
                                        <input type="number" placeholder="Minimum 10 kredita"
                                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                                               disabled>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            <i class="fas fa-comment mr-1"></i> Poruka (opciono)
                                        </label>
                                        <textarea rows="2" placeholder="npr. Za tvoj novi oglas!"
                                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                                                  disabled></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">4</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Potvrdite transfer</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Pregledajte detalje transfera i potvrdite slanje.
                                </p>
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4">
                                    <h4 class="font-medium text-amber-800 dark:text-amber-300 mb-2">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Pregled transfera
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-slate-600 dark:text-slate-400">Primalac:</span>
                                            <span class="font-medium text-slate-700 dark:text-slate-300">marko123</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-600 dark:text-slate-400">Količina:</span>
                                            <span class="font-medium text-slate-700 dark:text-slate-300">25 kredita</span>
                                        </div>
                                        <div class="border-t border-amber-300 dark:border-amber-700 pt-2 flex justify-between">
                                            <span class="text-slate-600 dark:text-slate-400">Novo stanje:</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300">100 kredita</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                    <p class="text-sm text-red-800 dark:text-red-300">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Važno:</strong> Transfer kredita je nepovratan! Proverite da li ste uneli tačno korisničko ime.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">5</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Primite potvrdu</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Nakon uspešnog transfera, dobićete potvrdu i email sa detaljima.
                                </p>
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                                        <h4 class="font-medium text-green-800 dark:text-green-300">Transfer uspešan!</h4>
                                    </div>
                                    <p class="text-sm text-green-700 dark:text-green-400 mb-2">
                                        Uspešno ste poslali 25 kredita korisniku marko123.
                                    </p>
                                    <p class="text-xs text-green-600 dark:text-green-500">
                                        ID transakcije: #TRX-2024-0125
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Limits and Rules -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-info-circle text-sky-500 mr-2"></i>
                            Ograničenja i pravila
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fas fa-chart-line text-sky-500 mr-2"></i>Dnevni limit
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Maksimalno 500 kredita dnevno</li>
                                    <li>• Maksimalno 10 transfera dnevno</li>
                                    <li>• Reset u ponoć</li>
                                </ul>
                            </div>
                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fas fa-shield-alt text-sky-500 mr-2"></i>Bezbednost
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Email potvrda za velike iznose</li>
                                    <li>• SMS verifikacija preko 100 kredita</li>
                                    <li>• 24h blokada nakon 3 greške</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Često postavljana pitanja</h3>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Mogu li poništiti transfer?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <p class="p-4 text-slate-600 dark:text-slate-400">
                                    Ne, transfer kredita je nepovratan. Uvek proverite podatke pre slanja.
                                </p>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Šta ako pošaljem na pogrešan nalog?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <p class="p-4 text-slate-600 dark:text-slate-400">
                                    Kontaktirajte podršku odmah. Ako primalac nije iskoristio kredite, možda možemo pomoći.
                                </p>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Da li ima provizije za transfer?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <p class="p-4 text-slate-600 dark:text-slate-400">
                                    Ne, transfer kredita je potpuno besplatan. Primalac dobija tačan iznos koji pošaljete.
                                </p>
                            </details>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-8 text-center">
                        <div class="inline-flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Pošaljite kredite sada
                            </a>
                            <a href="{{ route('help.credit-system') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-coins mr-2"></i>
                                Kupite kredite
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>