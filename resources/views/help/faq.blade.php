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
                    <li class="text-slate-900 dark:text-slate-100">Često postavljana pitanja</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-question-circle text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Često postavljana pitanja</h1>
                            <p class="text-purple-100">Pronađite odgovore na najčešća pitanja o korišćenju PazAriO platforme</p>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <input type="text"
                               placeholder="Pretražite pitanja..."
                               class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </div>

                <!-- FAQ Categories -->
                <div class="p-6 sm:p-8">
                    <!-- General Questions -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-info-circle text-sky-500 mr-2"></i>
                            Opšta pitanja
                        </h2>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Šta je PazAriO?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>PazAriO je online platforma za kupovinu i prodaju različitih proizvoda i usluga. Naš naziv dolazi od "moji oglasi" na srpskom jeziku. Nudimo siguran i jednostavan način za oglašavanje, aukcije, usluge i poklone.</p>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Da li je registracija besplatna?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Da, registracija na PazAriO platformu je potpuno besplatna. Dodatno, dobijate 10 kredita dobrodošlice koje možete koristiti za promociju vaših oglasa.</p>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako mogu kontaktirati podršku?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Možete nas kontaktirati preko:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Email: podrska@pazario.rs</li>
                                        <li>• Telefon: 011/123-4567</li>
                                        <li>• Live chat na sajtu (radnim danima 9-17h)</li>
                                        <li>• Sistem poruka na platformi</li>
                                    </ul>
                                </div>
                            </details>
                        </div>
                    </div>

                    <!-- Buying and Selling -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-shopping-cart text-green-500 mr-2"></i>
                            Kupovina i prodaja
                        </h2>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako postaviti oglas?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Postavljanje oglasa je jednostavno:</p>
                                    <ol class="mt-2 space-y-1 list-decimal list-inside">
                                        <li>Prijavite se na vaš nalog</li>
                                        <li>Kliknite na "Postavi" u navigaciji</li>
                                        <li>Izaberite tip oglasa (običan, aukcija, usluga, poklon)</li>
                                        <li>Popunite potrebne informacije</li>
                                        <li>Dodajte fotografije</li>
                                        <li>Objavite oglas</li>
                                    </ol>
                                    <a href="{{ route('help.create-listing') }}" class="inline-block mt-3 text-sky-600 dark:text-sky-400 hover:underline">
                                        <i class="fas fa-arrow-right mr-1"></i>Detaljno uputstvo
                                    </a>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Koliko košta postavljanje oglasa?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Osnovni oglas je besplatan. Dodatne opcije promocije koštaju:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• TOP oglas - 15 kredita</li>
                                        <li>• Istaknut oglas - 10 kredita</li>
                                        <li>• VRH kategorije - 8 kredita</li>
                                        <li>• Hitno oznaka - 5 kredita</li>
                                    </ul>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako funkcionišu aukcije?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Aukcije omogućavaju prodaju proizvoda putem licitiranja:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Postavite početnu cenu i korak licitiranja</li>
                                        <li>• Odredite trajanje aukcije (3-10 dana)</li>
                                        <li>• Kupci licitiraju za proizvod</li>
                                        <li>• Najviša ponuda na kraju aukcije pobeđuje</li>
                                        <li>• Možete vratiti aukciju u običan oglas ako nema ponuda</li>
                                    </ul>
                                    <a href="{{ route('help.create-auction') }}" class="inline-block mt-3 text-sky-600 dark:text-sky-400 hover:underline">
                                        <i class="fas fa-arrow-right mr-1"></i>Saznajte više o aukcijama
                                    </a>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako da budem siguran pri kupovini?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Sledite ove savete za sigurnu kupovinu:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Proverite ocenu i recenzije prodavca</li>
                                        <li>• Kupujte od verifikovanih korisnika</li>
                                        <li>• Insistirajte na ličnom preuzimanju za skupe proizvode</li>
                                        <li>• Nikad ne šaljite novac unapred bez garancije</li>
                                        <li>• Koristite sistem poruka na platformi</li>
                                        <li>• Prijavite sumnjive oglase</li>
                                    </ul>
                                </div>
                            </details>
                        </div>
                    </div>

                    <!-- Credits System -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-coins text-amber-500 mr-2"></i>
                            Kredit sistem
                        </h2>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Šta su krediti?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Krediti su virtuelna valuta PazAriO platforme koja se koristi za:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Promociju oglasa</li>
                                        <li>• Pokretanje aukcija</li>
                                        <li>• Isticanje usluga</li>
                                        <li>• Dodatne opcije vidljivosti</li>
                                    </ul>
                                    <p class="mt-2">Krediti ne istič i mogu se prenositi drugim korisnicima.</p>
                                    <a href="{{ route('help.credit-system') }}" class="inline-block mt-3 text-sky-600 dark:text-sky-400 hover:underline">
                                        <i class="fas fa-arrow-right mr-1"></i>Detaljno o kreditima
                                    </a>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako mogu dobiti besplatne kredite?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Postoji nekoliko načina za besplatne kredite:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Registracija - 10 kredita</li>
                                        <li>• Verifikacija email/telefona - po 10 kredita</li>
                                        <li>• Dnevna prijava - 1 kredit</li>
                                        <li>• Preporuka prijatelja - 5 kredita</li>
                                        <li>• Kompletiran profil - 5 kredita</li>
                                    </ul>
                                    <a href="{{ route('help.earn-credits') }}" class="inline-block mt-3 text-sky-600 dark:text-sky-400 hover:underline">
                                        <i class="fas fa-arrow-right mr-1"></i>Svi načini zarađivanja
                                    </a>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako kupiti kredite?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Kredite možete kupiti na više načina:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Platna kartica (Visa, MasterCard)</li>
                                        <li>• PayPal</li>
                                        <li>• Bankovna uplata</li>
                                        <li>• SMS poruka (za manje iznose)</li>
                                    </ul>
                                    <p class="mt-2">Paketi: 50 kredita (500 RSD), 100 kredita (900 RSD), 200 kredita (1700 RSD)</p>
                                </div>
                            </details>
                        </div>
                    </div>

                    <!-- Account and Security -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-shield-alt text-red-500 mr-2"></i>
                            Nalog i bezbednost
                        </h2>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako verifikovati nalog?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Verifikacija povećava poverenje drugih korisnika:</p>
                                    <ol class="mt-2 space-y-1 list-decimal list-inside">
                                        <li>Idite na Podešavanja profila</li>
                                        <li>Kliknite na "Verifikacija"</li>
                                        <li>Potvrdite email adresu (10 kredita bonus)</li>
                                        <li>Potvrdite broj telefona (10 kredita bonus)</li>
                                        <li>Opciono: Pošaljite dokument za potpunu verifikaciju</li>
                                    </ol>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Zaboravio sam lozinku, šta da radim?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Resetovanje lozinke je jednostavno:</p>
                                    <ol class="mt-2 space-y-1 list-decimal list-inside">
                                        <li>Na stranici za prijavu kliknite "Zaboravili ste lozinku?"</li>
                                        <li>Unesite vašu email adresu</li>
                                        <li>Proverite email za link za resetovanje</li>
                                        <li>Kliknite na link i postavite novu lozinku</li>
                                    </ol>
                                    <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Link važi 24 sata od slanja
                                    </p>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kako da zaštitim svoj nalog?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Sledite ove savete za maksimalnu bezbednost:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Koristite jaku lozinku sa kombinacijom slova, brojeva i znakova</li>
                                        <li>• Aktivirajte dvofaktorsku autentifikaciju</li>
                                        <li>• Ne delite podatke o nalogu</li>
                                        <li>• Redovno proveravajte aktivnosti na nalogu</li>
                                        <li>• Odjavite se sa deljenih uređaja</li>
                                        <li>• Pazite na phishing emailove</li>
                                    </ul>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Mogu li obrisati svoj nalog?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Da, možete obrisati nalog, ali imajte na umu:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Svi vaši oglasi će biti obrisani</li>
                                        <li>• Krediti se ne mogu povratiti</li>
                                        <li>• Istorija transakcija se briše</li>
                                        <li>• Proces je nepovratan</li>
                                    </ul>
                                    <p class="mt-2">Za brisanje naloga kontaktirajte podršku ili idite na Podešavanja → Privatnost → Obriši nalog</p>
                                </div>
                            </details>
                        </div>
                    </div>

                    <!-- Technical Issues -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-tools text-slate-500 mr-2"></i>
                            Tehnički problemi
                        </h2>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Sajt se sporo učitava, šta da radim?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Pokušajte sledeće:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Očistite keš i kolačiće u browseru</li>
                                        <li>• Proverite internet konekciju</li>
                                        <li>• Pokušajte sa drugim browserom</li>
                                        <li>• Isključite ad-blocker ekstenzije</li>
                                        <li>• Restartujte uređaj</li>
                                    </ul>
                                    <p class="mt-2">Ako problem persitira, kontaktirajte podršku sa opisom problema.</p>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Ne mogu da postavim fotografije</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>Proverite sledeće:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>• Maksimalna veličina fajla je 5MB</li>
                                        <li>• Podržani formati: JPG, PNG, GIF</li>
                                        <li>• Maksimalno 10 fotografija po oglasu</li>
                                        <li>• Minimalna rezolucija 600x400px</li>
                                    </ul>
                                    <p class="mt-2">Savet: Kompresujte slike pre postavljanja za brže učitavanje.</p>
                                </div>
                            </details>

                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900/70 transition-colors">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Koji browseri su podržani?</span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-slate-500"></i>
                                </summary>
                                <div class="p-4 text-slate-600 dark:text-slate-400">
                                    <p>PazAriO najbolje radi na:</p>
                                    <ul class="mt-2 space-y-1">
                                        <li>✓ Google Chrome (poslednje 2 verzije)</li>
                                        <li>✓ Mozilla Firefox (poslednje 2 verzije)</li>
                                        <li>✓ Safari (poslednje 2 verzije)</li>
                                        <li>✓ Microsoft Edge (poslednje 2 verzije)</li>
                                        <li>✓ Opera (poslednje 2 verzije)</li>
                                    </ul>
                                    <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Internet Explorer nije podržan
                                    </p>
                                </div>
                            </details>
                        </div>
                    </div>

                    <!-- Still Need Help -->
                    <div class="bg-gradient-to-r from-sky-50 to-blue-50 dark:from-sky-900/20 dark:to-blue-900/20 rounded-lg p-6">
                        <div class="text-center">
                            <i class="fas fa-headset text-4xl text-sky-600 dark:text-sky-400 mb-4"></i>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">Još uvek trebate pomoć?</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">
                                Naš tim podrške je tu da vam pomogne sa bilo kojim pitanjem ili problemom.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('messages.inbox') }}"
                                   class="inline-flex items-center justify-center px-6 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Pošaljite poruku
                                </a>
                                <button class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i class="fas fa-comments mr-2"></i>
                                    Live chat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>