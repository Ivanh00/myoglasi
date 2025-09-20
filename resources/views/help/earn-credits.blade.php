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
                    <li class="text-slate-900 dark:text-slate-100">Kako zaraditi kredite</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-6 sm:p-8">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg mr-4">
                            <i class="fas fa-coins text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Kako zaraditi kredite</h1>
                            <p class="text-purple-100">Besplatni načini da dobijete kredite za korišćenje platforme</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- Introduction -->
                    <div class="mb-8 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-gift text-purple-600 dark:text-purple-400 mr-2"></i>
                            Zaradite kredite bez plaćanja!
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            PazAriO nagrađuje aktivne korisnike besplatnim kreditima. Što ste aktivniji na platformi, više kredita možete zaraditi za promociju vaših oglasa.
                        </p>
                    </div>

                    <!-- Ways to Earn -->
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Načini zarađivanja kredita</h2>

                    <!-- Registration Bonus -->
                    <div class="mb-6 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-user-plus text-white text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold text-white">Bonus za registraciju</h3>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">+10 kredita</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-slate-600 dark:text-slate-400 mb-3">
                                Dobijate početne kredite čim se registrujete na platformi!
                            </p>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3">
                                <p class="text-sm text-green-800 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Automatski se dodaju na vaš nalog nakon registracije
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Verification -->
                    <div class="mb-6 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-sky-500 to-blue-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope-open-text text-white text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold text-white">Verifikacija email adrese</h3>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">+10 kredita</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-slate-600 dark:text-slate-400 mb-3">
                                Potvrdite vašu email adresu i dobijte bonus kredite.
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <span class="text-sky-500 mr-2">1.</span>
                                    <span class="text-slate-600 dark:text-slate-400">Idite na Podešavanja profila</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-sky-500 mr-2">2.</span>
                                    <span class="text-slate-600 dark:text-slate-400">Kliknite "Pošalji verifikacioni email"</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-sky-500 mr-2">3.</span>
                                    <span class="text-slate-600 dark:text-slate-400">Otvorite email i kliknite na link</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Verification -->
                    <div class="mb-6 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-mobile-alt text-white text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold text-white">Verifikacija broja telefona</h3>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">+10 kredita</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-slate-600 dark:text-slate-400 mb-3">
                                Potvrdite vaš broj telefona SMS kodom.
                            </p>
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3">
                                <p class="text-sm text-amber-800 dark:text-amber-300">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Verifikovani korisnici imaju veće poverenje kupaca
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Login -->
                    <div class="mb-6 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check text-white text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold text-white">Dnevna prijava</h3>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">+1 kredit/dan</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-slate-600 dark:text-slate-400 mb-3">
                                Prijavite se svaki dan i dobijte po 1 kredit!
                            </p>
                            <div class="grid grid-cols-7 gap-2 mb-3">
                                @for($i = 1; $i <= 7; $i++)
                                    <div class="aspect-square bg-{{ $i <= 5 ? 'green' : 'slate' }}-100 dark:bg-{{ $i <= 5 ? 'green' : 'slate' }}-900/30 rounded-lg flex items-center justify-center">
                                        <span class="text-{{ $i <= 5 ? 'green' : 'slate' }}-600 dark:text-{{ $i <= 5 ? 'green' : 'slate' }}-400 text-sm font-bold">{{ $i }}</span>
                                    </div>
                                @endfor
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3">
                                <p class="text-sm text-purple-800 dark:text-purple-300">
                                    <i class="fas fa-trophy mr-2"></i>
                                    Bonus: 7 dana zaredom = dodatnih 5 kredita!
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Program -->
                    <div class="mb-6 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-rose-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-white text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold text-white">Preporuka prijatelja</h3>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">+5 kredita</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-slate-600 dark:text-slate-400 mb-3">
                                Za svakog prijatelja koji se registruje preko vašeg linka!
                            </p>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 mb-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Vaš referral link:</label>
                                <div class="flex">
                                    <input type="text" value="https://pazario.rs/ref/vasusername" readonly
                                           class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-l-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                                    <button class="px-4 py-2 bg-sky-600 text-white rounded-r-lg hover:bg-sky-700">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Pozvali ste: <strong>3 prijatelja</strong></span>
                                <span class="text-green-600 dark:text-green-400">Zaradili: <strong>15 kredita</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Complete Profile -->
                    <div class="mb-6 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-user-check text-white text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold text-white">Kompletiran profil</h3>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">+5 kredita</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-slate-600 dark:text-slate-400 mb-3">
                                Popunite sve informacije na profilu:
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-900/50 rounded">
                                    <span class="text-slate-600 dark:text-slate-400">
                                        <i class="fas fa-camera text-slate-400 mr-2"></i>Profilna slika
                                    </span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-900/50 rounded">
                                    <span class="text-slate-600 dark:text-slate-400">
                                        <i class="fas fa-info-circle text-slate-400 mr-2"></i>O meni
                                    </span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-900/50 rounded">
                                    <span class="text-slate-600 dark:text-slate-400">
                                        <i class="fas fa-map-marker-alt text-slate-400 mr-2"></i>Lokacija
                                    </span>
                                    <i class="fas fa-times-circle text-red-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Activities -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-star text-amber-500 mr-2"></i>
                            Specijalne aktivnosti
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-comment-alt text-amber-600 dark:text-amber-400 text-xl mr-3"></i>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Prvi oglas</h4>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Postavite prvi oglas</p>
                                <p class="text-amber-600 dark:text-amber-400 font-bold">+3 kredita</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-handshake text-green-600 dark:text-green-400 text-xl mr-3"></i>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Prva prodaja</h4>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Završite prvu transakciju</p>
                                <p class="text-green-600 dark:text-green-400 font-bold">+5 kredita</p>
                            </div>
                            <div class="bg-gradient-to-br from-sky-50 to-blue-50 dark:from-sky-900/20 dark:to-blue-900/20 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-star text-sky-600 dark:text-sky-400 text-xl mr-3"></i>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Pozitivna ocena</h4>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Dobijte 5-star ocenu</p>
                                <p class="text-sky-600 dark:text-sky-400 font-bold">+2 kredita</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-gift text-purple-600 dark:text-purple-400 text-xl mr-3"></i>
                                    <h4 class="font-medium text-slate-700 dark:text-slate-300">Poklonite nešto</h4>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Postavite poklon oglas</p>
                                <p class="text-purple-600 dark:text-purple-400 font-bold">+3 kredita</p>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3">
                            <i class="fas fa-calculator text-green-500 mr-2"></i>
                            Koliko možete zaraditi?
                        </h3>
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-4">
                            <table class="w-full">
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                    <tr>
                                        <td class="py-2 text-slate-600 dark:text-slate-400">Jednokratne aktivnosti</td>
                                        <td class="py-2 text-right font-bold text-green-600 dark:text-green-400">40+ kredita</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-slate-600 dark:text-slate-400">Dnevno (mesečno)</td>
                                        <td class="py-2 text-right font-bold text-green-600 dark:text-green-400">30+ kredita</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-slate-600 dark:text-slate-400">Preporuke (po prijatelju)</td>
                                        <td class="py-2 text-right font-bold text-green-600 dark:text-green-400">5 kredita</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-8 text-center">
                        <div class="inline-flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center justify-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-rocket mr-2"></i>
                                Počnite da zarađujete
                            </a>
                            <a href="{{ route('help.credit-system') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-info-circle mr-2"></i>
                                O kredit sistemu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>