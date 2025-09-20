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
                    <li class="text-slate-900 dark:text-slate-100">Bezbednost na sajtu</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-shield-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Bezbednost na sajtu</h1>
                            <p class="text-emerald-100">Vaša sigurnost je naš prioritet. Saznajte kako da se zaštitite.</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- Introduction -->
                    <div class="mb-8 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-lock text-emerald-600 dark:text-emerald-400 mr-2"></i>
                            Naša posvećenost vašoj bezbednosti
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400">
                            PazAriO koristi najnovije tehnologije i procedure za zaštitu vaših podataka i transakcija.
                            Kontinuirano radimo na unapređenju bezbednosnih mera i edukaciji korisnika o sigurnoj trgovini online.
                        </p>
                    </div>

                    <!-- Security Tips for Buyers -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-shopping-bag text-sky-500 mr-2"></i>
                            Saveti za kupce
                        </h2>
                        <div class="space-y-4">
                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">
                                    <i class="fas fa-user-check text-green-500 mr-2"></i>
                                    Proverite prodavca
                                </h3>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Pogledajte ocene i recenzije drugih kupaca</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Proverite da li je nalog verifikovan</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Pregledajte druge oglase istog prodavca</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Obratite pažnju na datum registracije</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">
                                    <i class="fas fa-handshake text-blue-500 mr-2"></i>
                                    Sigurno preuzimanje
                                </h3>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Dogovorite sastanak na javnom mestu</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Pregledajte proizvod pre plaćanja</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Povedite prijatelja za skuplje proizvode</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Izbegavajte sastanke noću ili na izolovanim mestima</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">
                                    <i class="fas fa-credit-card text-purple-500 mr-2"></i>
                                    Načini plaćanja
                                </h3>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Preferirajte lično plaćanje pri preuzimanju</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Koristite PayPal ili druge zaštićene metode za online plaćanje</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times text-red-500 mr-2 mt-1"></i>
                                        <span>Nikad ne šaljite novac unapred nepoznatim prodavcima</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times text-red-500 mr-2 mt-1"></i>
                                        <span>Izbegavajte Western Union ili MoneyGram</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tips for Sellers -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-store text-amber-500 mr-2"></i>
                            Saveti za prodavce
                        </h2>
                        <div class="space-y-4">
                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">
                                    <i class="fas fa-camera text-indigo-500 mr-2"></i>
                                    Kreiranje oglasa
                                </h3>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Koristite stvarne fotografije proizvoda</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Budite iskreni o stanju proizvoda</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Ne delite lične podatke u opisu</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Postavite realnu cenu</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">
                                    <i class="fas fa-comments text-cyan-500 mr-2"></i>
                                    Komunikacija sa kupcima
                                </h3>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Koristite sistem poruka na platformi</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Čuvajte prepisku kao dokaz dogovora</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Budite profesionalni i ljubazni</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times text-red-500 mr-2 mt-1"></i>
                                        <span>Ne delite bankovne podatke putem poruka</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Common Scams -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            Česte prevare - Budite oprezni!
                        </h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h3 class="font-semibold text-red-800 dark:text-red-300 mb-2">
                                    <i class="fas fa-ban mr-2"></i>
                                    Prevara sa uplatom unapred
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-2">
                                    Prevarant traži uplatu pre slanja proizvoda, a zatim nestaje sa novcem.
                                </p>
                                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Zaštita: Nikad ne šaljite novac unapred neproverenim prodavcima
                                </p>
                            </div>

                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h3 class="font-semibold text-red-800 dark:text-red-300 mb-2">
                                    <i class="fas fa-ban mr-2"></i>
                                    Phishing emailovi
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-2">
                                    Lažni emailovi koji izgledaju kao da dolaze od PazAriO platforme.
                                </p>
                                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Zaštita: Proverite email adresu pošiljaoca, nikad ne klikćete sumnjive linkove
                                </p>
                            </div>

                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h3 class="font-semibold text-red-800 dark:text-red-300 mb-2">
                                    <i class="fas fa-ban mr-2"></i>
                                    Prevara sa čekom
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-2">
                                    Kupac šalje čeke veće vrednosti i traži povrat razlike.
                                </p>
                                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Zaštita: Ne prihvatajte čekove, posebno iz inostranstva
                                </p>
                            </div>

                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h3 class="font-semibold text-red-800 dark:text-red-300 mb-2">
                                    <i class="fas fa-ban mr-2"></i>
                                    Prevara sa shipping kompanijama
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-2">
                                    Kupac insistira na korišćenju nepoznate shipping kompanije.
                                </p>
                                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Zaštita: Koristite samo poznate i proverene kurirske službe
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Suspicious Activity -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-flag text-orange-500 mr-2"></i>
                            Prijavite sumnjive aktivnosti
                        </h2>
                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                            <p class="text-slate-600 dark:text-slate-400 mb-4">
                                Ako primetite bilo koju sumnjivu aktivnost, molimo vas da je odmah prijavite našem timu.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Šta prijaviti:</h4>
                                    <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                        <li>• Sumnjive oglase</li>
                                        <li>• Pokušaje prevare</li>
                                        <li>• Neprikladno ponašanje</li>
                                        <li>• Lažne profile</li>
                                        <li>• Spam poruke</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Kako prijaviti:</h4>
                                    <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                        <li>• Dugme "Prijavi" na oglasu</li>
                                        <li>• Email: bezbednost@pazario.rs</li>
                                        <li>• Sistem poruka</li>
                                        <li>• Telefon: 011/123-4567</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-white dark:bg-slate-800 rounded-lg">
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <i class="fas fa-info-circle text-sky-500 mr-2"></i>
                                    Sve prijave se tretiraju poverljivo i istražuju se u najkraćem mogućem roku.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Features -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-lock text-green-500 mr-2"></i>
                            Naše bezbednosne mere
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-4">
                                <i class="fas fa-user-shield text-3xl text-green-600 dark:text-green-400 mb-3"></i>
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Verifikacija korisnika</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Email, telefon i dokumenta za potpunu verifikaciju
                                </p>
                            </div>
                            <div class="bg-gradient-to-br from-blue-50 to-sky-50 dark:from-blue-900/20 dark:to-sky-900/20 rounded-lg p-4">
                                <i class="fas fa-lock text-3xl text-blue-600 dark:text-blue-400 mb-3"></i>
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">SSL enkripcija</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Svi podaci su zaštićeni najnovijom enkripcijom
                                </p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-4">
                                <i class="fas fa-eye text-3xl text-purple-600 dark:text-purple-400 mb-3"></i>
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">24/7 monitoring</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Konstantno praćenje sumjivih aktivnosti
                                </p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-4">
                                <i class="fas fa-star text-3xl text-amber-600 dark:text-amber-400 mb-3"></i>
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Sistem ocenjivanja</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Transparentne ocene i recenzije korisnika
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Security Team -->
                    <div class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-900/20 dark:to-gray-900/20 rounded-lg p-6 text-center">
                        <i class="fas fa-shield-alt text-4xl text-emerald-600 dark:text-emerald-400 mb-4"></i>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">Bezbednosni tim je tu za vas</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">
                            Imate pitanja o bezbednosti ili želite da prijavite problem? Kontaktirajte nas odmah.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="mailto:bezbednost@pazario.rs"
                               class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-envelope mr-2"></i>
                                bezbednost@pazario.rs
                            </a>
                            <button class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-phone mr-2"></i>
                                011/123-4567
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>