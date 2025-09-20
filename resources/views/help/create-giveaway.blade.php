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
                    <li class="text-slate-900 dark:text-slate-100">Kako postaviti poklon</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-gift text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako postaviti poklon</h1>
                            <p class="text-green-100">Podelite stvari koje vam više nisu potrebne sa onima kojima mogu koristiti</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- What is Giveaway -->
                    <div class="mb-8 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-heart text-green-600 dark:text-green-400 mr-2"></i>
                            Šta je poklon?
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">
                            Poklon je način da besplatno podelite stvari koje vam više nisu potrebne sa drugim korisnicima. Ovo je odličan način da pomognete nekome, smanjite otpad i oslobodite prostor u vašem domu.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center">
                                <i class="fas fa-hand-holding-heart text-3xl text-green-600 dark:text-green-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Potpuno besplatno</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Bez naknade ili razmene</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-recycle text-3xl text-green-600 dark:text-green-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Ekološki</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Smanjuje otpad</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-users text-3xl text-green-600 dark:text-green-400 mb-2"></i>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Društveno korisno</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Pomaže zajednici</p>
                            </div>
                        </div>
                    </div>

                    <!-- When to Use Giveaway -->
                    <div class="mb-8 border border-slate-200 dark:border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-lightbulb text-amber-500 mr-2"></i>
                            Kada koristiti opciju poklona?
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2 text-green-600 dark:text-green-400">
                                    <i class="fas fa-check-circle mr-2"></i>Idealno za:
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Odeća koju više ne nosite</li>
                                    <li>• Knjige koje ste pročitali</li>
                                    <li>• Igračke koje deca prerasla</li>
                                    <li>• Nameštaj pri selidbi</li>
                                    <li>• Funkcionalni uređaji koji vam ne trebaju</li>
                                    <li>• Baštenski proizvodi (sadnice, seme)</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2 text-red-600 dark:text-red-400">
                                    <i class="fas fa-times-circle mr-2"></i>Nije za:
                                </h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Pokvarene ili oštećene stvari</li>
                                    <li>• Istekle proizvode</li>
                                    <li>• Opasne materijale</li>
                                    <li>• Stvari koje zahtevaju popravku</li>
                                    <li>• Lekove ili medicinske proizvode</li>
                                    <li>• Stvari sumnjivog porekla</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Steps -->
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Koraci za postavljanje poklona</h2>

                    <!-- Step 1 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-green-600 dark:text-green-400 font-bold">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Idite na opciju "Postavi poklon"</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    U glavnoj navigaciji kliknite na "Postavi" i zatim izaberite opciju "🎁 Poklon".
                                </p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 inline-block">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-2 bg-sky-600 text-white rounded-lg text-sm font-medium">
                                            <i class="fas fa-plus mr-2"></i>Postavi
                                        </span>
                                        <i class="fas fa-arrow-right text-slate-400"></i>
                                        <span class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm font-medium">
                                            <i class="fas fa-gift mr-2"></i>Poklon
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-green-600 dark:text-green-400 font-bold">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Unesite naziv poklona</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Napišite kratak i jasan naziv koji opisuje šta poklanjate.
                                </p>
                                <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Naziv poklona</label>
                                    <input type="text" placeholder="npr. Dečije knjige, Ženski zimski kaput, Set posuđa..."
                                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                                           disabled>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                        💡 Savet: Budite specifični - umesto "Odeća" napišite "Ženska letnja odeća veličine M"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-green-600 dark:text-green-400 font-bold">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Izaberite kategoriju</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Odaberite odgovarajuću kategoriju iz liste kategorija oglasa (ne usluga).
                                </p>
                                <div class="space-y-3">
                                    <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 rounded-lg p-4">
                                        <p class="text-sm text-sky-800 dark:text-sky-300">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <strong>Napomena:</strong> Koriste se iste kategorije kao za oglase, što olakšava pronalaženje poklona.
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button class="p-3 text-left border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                            <i class="fas fa-couch text-slate-500 mr-2"></i>
                                            <span class="text-slate-700 dark:text-slate-300">Nameštaj</span>
                                        </button>
                                        <button class="p-3 text-left border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                            <i class="fas fa-tshirt text-slate-500 mr-2"></i>
                                            <span class="text-slate-700 dark:text-slate-300">Odeća i obuća</span>
                                        </button>
                                        <button class="p-3 text-left border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                            <i class="fas fa-book text-slate-500 mr-2"></i>
                                            <span class="text-slate-700 dark:text-slate-300">Knjige</span>
                                        </button>
                                        <button class="p-3 text-left border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                            <i class="fas fa-gamepad text-slate-500 mr-2"></i>
                                            <span class="text-slate-700 dark:text-slate-300">Igračke</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-green-600 dark:text-green-400 font-bold">4</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Dodajte opis (opciono)</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Opišite stanje predmeta, razlog poklanjanja i dodatne informacije.
                                </p>
                                <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Opis poklona</label>
                                    <textarea rows="4" placeholder="npr. Poklanjam set knjiga za decu, očuvane, iz nepušačkog doma. Preuzimanje na Novom Beogradu..."
                                              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                                              disabled></textarea>
                                    <div class="mt-3 space-y-2">
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            <i class="fas fa-check text-green-500 mr-1"></i> Navedite stanje (novo, korišćeno, očuvano)
                                        </p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            <i class="fas fa-check text-green-500 mr-1"></i> Lokacija i način preuzimanja
                                        </p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            <i class="fas fa-check text-green-500 mr-1"></i> Eventualni uslovi (npr. za porodice sa decom)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-green-600 dark:text-green-400 font-bold">5</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Dodajte fotografije</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Dodajte jasne fotografije predmeta koje poklanjate. Fotografije povećavaju šanse da neko preuzme poklon.
                                </p>
                                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-8 text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                                    <p class="text-slate-600 dark:text-slate-400 mb-2">Prevucite fotografije ovde ili kliknite za izbor</p>
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-camera mr-2"></i>Izaberite fotografije
                                    </button>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">Maksimalno 10 fotografija • JPG, PNG do 5MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="mb-8">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                                <span class="text-green-600 dark:text-green-400 font-bold">6</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Objavite poklon</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">
                                    Pregledajte sve informacije i kliknite "Objavi poklon".
                                </p>
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                                    <h4 class="font-medium text-green-800 dark:text-green-300 mb-2">
                                        <i class="fas fa-check-circle mr-2"></i>Šta se dešava nakon objave?
                                    </h4>
                                    <ul class="text-sm text-green-700 dark:text-green-400 space-y-1">
                                        <li>• Poklon se pojavljuje u kategoriji "Poklanjam" i u odabranoj kategoriji</li>
                                        <li>• Zainteresovani korisnici mogu vas kontaktirati</li>
                                        <li>• Možete izabrati kome ćete pokloniti</li>
                                        <li>• Označite poklon kao poklonjen kada ga predate</li>
                                    </ul>
                                </div>
                                <button class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-gift mr-2"></i>Objavi poklon
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits Section -->
                    <div class="mt-12 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-sparkles text-green-500 mr-2"></i>
                            Zašto poklanjati preko PazAriO?
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Bezbedno</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Verifikovani korisnici i sistem ocenjivanja</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-users text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Veliki doseg</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Hiljade korisnika vidi vaš poklon</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-heart text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Karma bodovi</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Dobijate pozitivne ocene za poklone</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-leaf text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Ekološki</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Smanjujete otpad i čuvate prirodu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div class="mt-8 border border-slate-200 dark:border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-lightbulb text-amber-500 mr-2"></i>
                            Saveti za uspešno poklanjanje
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <p class="text-slate-600 dark:text-slate-400">
                                    <strong class="text-slate-700 dark:text-slate-300">Budite iskreni:</strong> Opišite tačno stanje predmeta
                                </p>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <p class="text-slate-600 dark:text-slate-400">
                                    <strong class="text-slate-700 dark:text-slate-300">Brzo odgovarajte:</strong> Na poruke zainteresovanih
                                </p>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <p class="text-slate-600 dark:text-slate-400">
                                    <strong class="text-slate-700 dark:text-slate-300">Organizujte preuzimanje:</strong> Dogovorite tačno vreme i mesto
                                </p>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <p class="text-slate-600 dark:text-slate-400">
                                    <strong class="text-slate-700 dark:text-slate-300">Označite kao poklonjeno:</strong> Kada predate poklon
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-8 text-center">
                        <div class="inline-flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('listings.create') }}"
                               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-gift mr-2"></i>
                                Pokloni sada
                            </a>
                            <a href="{{ route('giveaways.index') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-search mr-2"></i>
                                Pogledaj dostupne poklone
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>