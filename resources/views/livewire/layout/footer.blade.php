<footer
    class="bg-white dark:bg-slate-900 text-slate-700 dark:text-white border-t border-slate-200 dark:border-slate-700">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About PazAriO -->
            <div>
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mr-2"></i>
                    O PazAriO
                </h3>
                <p class="text-slate-600 dark:text-slate-300 text-sm mb-4">
                    Moderna platforma za kupovinu, prodaju, aukcije, usluge i poklone.
                    Povezujemo ljude kroz sigurne i jednostavne transakcije.
                </p>
                <div class="flex space-x-3">
                    <a href="#" class="text-slate-500 dark:text-slate-400 hover:text-white">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-slate-500 dark:text-slate-400 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-slate-500 dark:text-slate-400 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- How To Guides -->
            <div>
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-question-circle text-green-600 dark:text-green-400 mr-2"></i>
                    Kako da...
                </h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('help.create-listing') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">📝
                            Postavim oglas</a></li>
                    <li><a href="{{ route('help.create-auction') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🔨
                            Postavim aukciju</a></li>
                    <li><a href="{{ route('help.create-service') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🔧
                            Postavim uslugu</a></li>
                    <li><a href="{{ route('help.create-giveaway') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🎁
                            Postavim poklon</a></li>
                    <li><a href="{{ route('help.credit-system') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">💰
                            Uplatim i koristim kredit</a></li>
                    <li><a href="{{ route('help.earn-credits') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🎮
                            Zaradim kredit</a></li>
                    <li><a href="{{ route('help.transfer-credits') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">💸
                            Podelim kredit</a></li>
                </ul>
            </div>

            <!-- Pricing & Plans -->
            <div>
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-tag text-amber-600 dark:text-amber-400 mr-2"></i>
                    Cenovnik
                </h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('help.pricing') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">💵
                            Cenovnik usluga</a></li>
                    <li><a href="{{ route('help.plans') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">📋
                            Planovi naplate</a></li>
                    <li><a href="{{ route('help.promotions') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🚀
                            Promocije oglasa</a></li>
                    <li><a href="{{ route('help.payment-methods') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">💳
                            Načini plaćanja</a></li>
                    <li><a href="{{ route('help.verification') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">✅
                            Verifikacija naloga</a></li>
                </ul>
            </div>

            <!-- Support & Legal -->
            <div>
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-headset text-purple-600 dark:text-purple-400 mr-2"></i>
                    Podrška
                </h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('help.faq') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">❓
                            Često postavljana pitanja</a></li>
                    <li><a href="{{ route('help.safety') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🛡️
                            Bezbednost na sajtu</a></li>
                    <li><a href="{{ route('help.rules') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">📜
                            Pravila korišćenja</a></li>
                    <li><a href="{{ route('help.privacy') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">🔒
                            Politika privatnosti</a></li>
                    <li><a href="{{ route('help.terms') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">📋
                            Uslovi korišćenja</a></li>
                    <li><a href="{{ route('admin.contact') }}"
                            class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">📧
                            Kontaktiraj admin</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-200 dark:border-slate-700 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} PazAriO. Sva prava zadržana.
                </div>
                <div class="flex items-center space-x-6 mt-4 md:mt-0">
                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        <i class="fas fa-users mr-1"></i>
                        {{ \App\Models\User::count() }} korisnika
                    </span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        <i class="fas fa-list mr-1"></i>
                        {{ \App\Models\Listing::where('status', 'active')->count() }} aktivnih oglasa
                    </span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        <i class="fas fa-gavel mr-1"></i>
                        {{ \App\Models\Auction::where('status', 'active')->count() }} aukcija
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
