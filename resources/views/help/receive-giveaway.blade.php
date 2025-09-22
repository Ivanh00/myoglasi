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
                    <li class="text-slate-900 dark:text-slate-100">Kako dobiti poklon</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-2 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-gift text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako dobiti poklon</h1>
                            <p class="text-purple-100">Pronađite besplatne stvari koje vam mogu biti korisne</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-2 sm:p-8">
                    <!-- What is Receiving a Gift -->
                    <div
                        class="mb-8 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-hand-holding-gift text-purple-600 dark:text-purple-400 mr-2"></i>
                            Kako funkcioniše sistem poklona?
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">
                            Sistem poklona omogućava korisnicima da besplatno dobiju stvari koje drugi žele da poklone.
                            Sve što trebate je da pronađete poklon koji vas interesuje i pošaljete zahtev vlasniku.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center">
                                <i class="fas fa-search text-3xl text-purple-600 dark:text-purple-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Pretražite poklone</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Pronađite ono što vam treba</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-hand-paper text-3xl text-purple-600 dark:text-purple-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Pošaljite zahtev</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Prijavite se za poklon</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-check-circle text-3xl text-purple-600 dark:text-purple-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Preuzmite poklon</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Dogovorite preuzimanje</p>
                            </div>
                        </div>
                    </div>

                    <!-- Important Info -->
                    <div
                        class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 mr-2"></i>
                            Važne informacije
                        </h3>
                        <ul class="text-slate-600 dark:text-slate-400 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-users text-amber-500 mr-2 mt-1"></i>
                                <span>Do <strong>{{ \App\Models\Setting::get('max_giveaway_requests', 9) }}
                                        korisnika</strong> može istovremeno da traži isti poklon</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-user-check text-amber-500 mr-2 mt-1"></i>
                                <span>Vlasnik poklona bira kome će dati stvar na osnovu vaših poruka</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-bell text-amber-500 mr-2 mt-1"></i>
                                <span>Dobićete obaveštenje kada vlasnik donese odluku</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-handshake text-amber-500 mr-2 mt-1"></i>
                                <span>Preuzimanje se dogovara direktno sa vlasnikom</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Steps -->
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Koraci za dobijanje poklona
                    </h2>

                    <!-- Step 1 -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div
                                class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                                1
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Pronađite
                                    poklon</h3>
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <p class="text-slate-600 dark:text-slate-400 mb-3">
                                        Idite na stranicu sa poklonima klikom na dugme "Pokloni" u glavnoj navigaciji
                                        ili <a href="{{ route('giveaways.index') }}"
                                            class="text-purple-600 dark:text-purple-400 hover:underline">kliknite
                                            ovde</a>.
                                    </p>
                                    <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
                                        <li>• Pregledajte dostupne poklone</li>
                                        <li>• Koristite filtere za kategorije</li>
                                        <li>• Sortirajte po najnovijim</li>
                                        <li>• Proverite lokaciju poklona</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div
                                class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                                2
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Kliknite na
                                    "Želim poklon"</h3>
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <p class="text-slate-600 dark:text-slate-400 mb-3">
                                        Kada pronađete poklon koji vas interesuje, kliknite na narančasto dugme "Želim
                                        poklon".
                                    </p>
                                    <div
                                        class="bg-orange-100 dark:bg-orange-900/30 border border-orange-300 dark:border-orange-700 rounded p-3 mb-3">
                                        <button
                                            class="w-full sm:w-auto px-4 py-2 bg-orange-600 text-white rounded-lg font-medium cursor-default">
                                            <i class="fas fa-hand-paper mr-2"></i> Želim poklon
                                        </button>
                                    </div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        Napomena: Dugme će prikazivati broj trenutnih zahteva (npr.
                                        3/{{ \App\Models\Setting::get('max_giveaway_requests', 9) }}) ako već ima
                                        zainteresovanih.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div
                                class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                                3
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Napišite
                                    poruku</h3>
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <p class="text-slate-600 dark:text-slate-400 mb-3">
                                        Otvoriće se prozor gde trebate napisati poruku vlasniku. Ova poruka je
                                        <strong>veoma važna</strong> jer na osnovu nje vlasnik bira kome će dati poklon.
                                    </p>
                                    <div
                                        class="bg-green-50 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded p-3">
                                        <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">
                                            <i class="fas fa-lightbulb mr-2"></i>Saveti za dobru poruku:
                                        </h4>
                                        <ul class="text-sm text-green-700 dark:text-green-300 space-y-1">
                                            <li>• Objasnite zašto vam je poklon potreban</li>
                                            <li>• Budite ljubazni i pristojni</li>
                                            <li>• Navedite kada možete preuzeti</li>
                                            <li>• Potvrdite da možete doći po poklon</li>
                                            <li>• Nemojte tražiti dostavu ako nije ponuđena</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div
                                class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                                4
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Sačekajte
                                    odluku</h3>
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <p class="text-slate-600 dark:text-slate-400 mb-3">
                                        Nakon slanja zahteva, vlasnik će pregledati sve zahteve i doneti odluku. U
                                        međuvremenu:
                                    </p>
                                    <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
                                        <li>• Dugme će pokazati "Zahtev poslat"</li>
                                        <li>• Ne možete poslati dupli zahtev</li>
                                        <li>• Možete tražiti druge poklone</li>
                                        <li>• Pratite obaveštenja za odgovor</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div
                                class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                                5
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Dobijte
                                    obaveštenje</h3>
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <p class="text-slate-600 dark:text-slate-400 mb-4">
                                        Kada vlasnik donese odluku, dobićete obaveštenje u sekciji "Obaveštenja":
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div
                                            class="bg-green-50 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded p-3">
                                            <h4 class="font-medium text-green-700 dark:text-green-300 mb-2">
                                                <i class="fas fa-check-circle mr-2"></i>Ako je odobren:
                                            </h4>
                                            <ul class="text-sm text-green-600 dark:text-green-400">
                                                <li>• Čestitamo! Dobili ste poklon</li>
                                                <li>• Kontaktirajte vlasnika za dogovor</li>
                                                <li>• Dogovorite mesto i vreme</li>
                                            </ul>
                                        </div>
                                        <div
                                            class="bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded p-3">
                                            <h4 class="font-medium text-red-700 dark:text-red-300 mb-2">
                                                <i class="fas fa-times-circle mr-2"></i>Ako je odbijen:
                                            </h4>
                                            <ul class="text-sm text-red-600 dark:text-red-400">
                                                <li>• Poklon je dat drugom korisniku</li>
                                                <li>• Pokušajte sa drugim poklonima</li>
                                                <li>• Ne obeshrabrite se</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-star text-amber-500 mr-2"></i>
                            Korisni saveti
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Za uspešno dobijanje:
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Budite brzi - pokloni se brzo razgrabe</li>
                                    <li>• Pišite iskrene i pristojne poruke</li>
                                    <li>• Budite fleksibilni sa vremenom preuzimanja</li>
                                    <li>• Proverite lokaciju pre slanja zahteva</li>
                                    <li>• Poštujte dogovoreno vreme</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Pri preuzimanju:</h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Budite tačni na dogovoreno vreme</li>
                                    <li>• Proverite stanje poklona</li>
                                    <li>• Budite zahvalni i ljubazni</li>
                                    <li>• Ne tražite dodatne stvari</li>
                                    <li>• Ostavite pozitivnu ocenu vlasniku</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Status Examples -->
                    <div class="mt-8 bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mr-2"></i>
                            Statusi dugmeta
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Možete poslati zahtev:</span>
                                <button class="px-3 py-1.5 bg-orange-600 text-white rounded text-sm font-medium">
                                    <i class="fas fa-hand-paper mr-1"></i> Želim poklon
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Već ima zahteva:</span>
                                <button class="px-3 py-1.5 bg-orange-600 text-white rounded text-sm font-medium">
                                    <i class="fas fa-hand-paper mr-1"></i> Želim poklon
                                    (3/{{ \App\Models\Setting::get('max_giveaway_requests', 9) }})
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Vaš zahtev je poslat:</span>
                                <button disabled
                                    class="px-3 py-1.5 bg-sky-600 text-white rounded text-sm font-medium opacity-50">
                                    <i class="fas fa-paper-plane mr-1"></i> Zahtev poslat
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Dostignut maksimum:</span>
                                <button disabled
                                    class="px-3 py-1.5 bg-amber-600 text-white rounded text-sm font-medium opacity-50">
                                    <i class="fas fa-users mr-1"></i> Max. broj zahteva
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Poklon je dat:</span>
                                <button disabled
                                    class="px-3 py-1.5 bg-slate-600 text-white rounded text-sm font-medium opacity-50">
                                    <i class="fas fa-check-circle mr-1"></i> Poklonjeno
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="mt-8 text-center">
                        <a href="{{ route('giveaways.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-gift mr-2"></i>
                            Pregledaj dostupne poklone
                        </a>
                    </div>

                </div>
            </div>

            <!-- Related Help Links -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('help.create-giveaway') }}"
                    class="block bg-white dark:bg-slate-800 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">
                        <i class="fas fa-gift text-green-600 dark:text-green-400 mr-2"></i>
                        Kako postaviti poklon
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Saznajte kako da poklonite stvari koje vam više nisu potrebne
                    </p>
                </a>
                <a href="{{ route('help.faq') }}"
                    class="block bg-white dark:bg-slate-800 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">
                        <i class="fas fa-question-circle text-sky-600 dark:text-sky-400 mr-2"></i>
                        Često postavljana pitanja
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Pronađite odgovore na najčešća pitanja
                    </p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
