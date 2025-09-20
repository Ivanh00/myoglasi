<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Politika privatnosti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Last Updated -->
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Poslednja izmena: 20. septembar 2025.
                    </div>

                    <!-- Introduction -->
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <h2 class="text-2xl font-bold mb-4">1. Uvod</h2>
                        <p class="mb-6">
                            PazAriO ("mi", "naš" ili "nas") poštuje vašu privatnost i posvećen je zaštiti vaših ličnih podataka.
                            Ova politika privatnosti objašnjava kako prikupljamo, koristimo, čuvamo i štitimo vaše informacije
                            kada koristite našu platformu.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">2. Informacije koje prikupljamo</h2>

                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                            <h3 class="font-bold mb-3">Informacije koje vi pružate:</h3>
                            <ul class="list-disc list-inside space-y-2">
                                <li><strong>Osnovni podaci naloga:</strong> Ime, email adresa, broj telefona</li>
                                <li><strong>Profilne informacije:</strong> Profilna slika, biografija, lokacija</li>
                                <li><strong>Sadržaj oglasa:</strong> Opisi, fotografije, cene proizvoda</li>
                                <li><strong>Komunikacija:</strong> Poruke između korisnika</li>
                                <li><strong>Transakcioni podaci:</strong> Istorija kredita i transakcija</li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <h3 class="font-bold mb-3">Automatski prikupljene informacije:</h3>
                            <ul class="list-disc list-inside space-y-2">
                                <li><strong>Podaci o uređaju:</strong> IP adresa, tip pregledača, operativni sistem</li>
                                <li><strong>Podaci o korišćenju:</strong> Stranice koje posećujete, vreme provedeno na platformi</li>
                                <li><strong>Kolačići:</strong> Session kolačići, kolačići za preferencije</li>
                                <li><strong>Log fajlovi:</strong> Datum i vreme pristupa, greške</li>
                            </ul>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">3. Kako koristimo vaše informacije</h2>
                        <p class="mb-4">Vaše podatke koristimo za:</p>
                        <ul class="list-disc list-inside mb-6 space-y-2">
                            <li>Pružanje i održavanje naših usluga</li>
                            <li>Omogućavanje komunikacije između korisnika</li>
                            <li>Procesiranje transakcija kredita</li>
                            <li>Slanje obaveštenja o vašim oglasima i porukama</li>
                            <li>Poboljšanje korisničkog iskustva</li>
                            <li>Otkrivanje i prevencija prevara</li>
                            <li>Poštovanje zakonskih obaveza</li>
                        </ul>

                        <h2 class="text-2xl font-bold mb-4">4. Deljenje informacija</h2>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 mb-6">
                            <p class="font-semibold mb-2">Vaše informacije delimo samo sa:</p>
                            <ul class="list-disc list-inside space-y-2">
                                <li><strong>Drugim korisnicima:</strong> Informacije vidljive u vašim oglasima</li>
                                <li><strong>Pružaocima usluga:</strong> Hosting, email servisi, analitika</li>
                                <li><strong>Pravnim organima:</strong> Kada je to zakonski obavezno</li>
                                <li><strong>Poslovnim partnerima:</strong> Uz vašu eksplicitnu saglasnost</li>
                            </ul>
                        </div>

                        <p class="mb-6 font-semibold">
                            Nikada ne prodajemo vaše lične podatke trećim stranama.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">5. Sigurnost podataka</h2>
                        <div class="grid gap-4 mb-6">
                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-bold mb-2 text-green-600 dark:text-green-400">Mere zaštite koje koristimo:</h3>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>SSL enkripcija za prenos podataka</li>
                                    <li>Hashovanje lozinki</li>
                                    <li>Redovne sigurnosne provere</li>
                                    <li>Ograničen pristup ličnim podacima</li>
                                    <li>Redovno ažuriranje sistema</li>
                                </ul>
                            </div>

                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-bold mb-2 text-blue-600 dark:text-blue-400">Vaše obaveze:</h3>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>Čuvajte svoje pristupne podatke</li>
                                    <li>Koristite jake lozinke</li>
                                    <li>Ne delite nalog sa drugima</li>
                                    <li>Prijavite sumnjive aktivnosti</li>
                                </ul>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">6. Vaša prava</h2>
                        <p class="mb-4">Imate pravo da:</p>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-6">
                            <ul class="list-disc list-inside space-y-2">
                                <li><strong>Pristupite</strong> svojim ličnim podacima</li>
                                <li><strong>Ispravite</strong> netačne informacije</li>
                                <li><strong>Obrišete</strong> svoj nalog i podatke</li>
                                <li><strong>Ograničite</strong> obradu podataka</li>
                                <li><strong>Prenesete</strong> podatke drugom servisu</li>
                                <li><strong>Prigovorite</strong> na način obrade</li>
                                <li><strong>Povučete</strong> saglasnost u bilo kom trenutku</li>
                            </ul>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">7. Kolačići (Cookies)</h2>
                        <p class="mb-4">Koristimo kolačiće za:</p>
                        <ul class="list-disc list-inside mb-6 space-y-2">
                            <li>Održavanje sesije prijavljenog korisnika</li>
                            <li>Pamćenje vaših preferencija</li>
                            <li>Analizu korišćenja platforme</li>
                            <li>Poboljšanje performansi</li>
                        </ul>
                        <p class="mb-6">
                            Možete kontrolisati kolačiće kroz podešavanja vašeg pregledača, ali to može uticati
                            na funkcionalnost platforme.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">8. Čuvanje podataka</h2>
                        <p class="mb-6">
                            Vaše podatke čuvamo dok imate aktivan nalog. Nakon brisanja naloga, podatke zadržavamo
                            do 90 dana za slučaj da želite da vratite nalog, nakon čega se trajno brišu.
                            Neki podaci mogu biti zadržani duže zbog zakonskih obaveza.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">9. Deca</h2>
                        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-6">
                            <p>
                                Naša platforma nije namenjena osobama mlađim od 18 godina.
                                Ne prikupljamo svesno podatke od maloletnika. Ako saznamo da smo prikupili
                                podatke od deteta, odmah ćemo ih obrisati.
                            </p>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">10. Međunarodni transfer podataka</h2>
                        <p class="mb-6">
                            Vaši podaci mogu biti transferovani i čuvani na serverima van vaše zemlje.
                            Osiguravamo da svi transferi budu u skladu sa važećim zakonima o zaštiti podataka.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">11. Izmene politike privatnosti</h2>
                        <p class="mb-6">
                            Možemo ažurirati ovu politiku privatnosti povremeno. O značajnim izmenama ćemo vas
                            obavestiti putem emaila ili obaveštenja na platformi. Preporučujemo da povremeno
                            pregledate ovu politiku.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">12. Kontakt za pitanja o privatnosti</h2>
                        <p class="mb-4">Za sva pitanja o privatnosti možete nas kontaktirati:</p>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                            <p class="font-semibold">PazAriO Privacy Team</p>
                            <p>Email: privacy@pazario.com</p>
                            <p>Ili putem <a href="{{ route('messages.inbox') }}" class="text-blue-600 dark:text-blue-400 hover:underline">sistema poruka</a></p>
                            <p class="mt-2 text-sm">Odgovaramo na sve zahteve u roku od 30 dana.</p>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">13. Nadležni organ</h2>
                        <p class="mb-6">
                            Ako niste zadovoljni našim odgovorom, možete podneti žalbu Povereniku za informacije
                            od javnog značaja i zaštitu podataka o ličnosti Republike Srbije.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>