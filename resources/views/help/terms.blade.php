<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Uslovi korišćenja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Last Updated -->
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Poslednja izmena: 20. septembar 2025.
                    </div>

                    <!-- Introduction -->
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <h2 class="text-2xl font-bold mb-4">1. Prihvatanje uslova</h2>
                        <p class="mb-6">
                            Korišćenjem PazAriO platforme prihvatate ove uslove korišćenja u potpunosti.
                            Ako se ne slažete sa bilo kojim delom ovih uslova, molimo vas da ne koristite našu platformu.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">2. Opis usluge</h2>
                        <p class="mb-6">
                            PazAriO je online platforma za oglašavanje koja omogućava korisnicima da objavljuju,
                            pregledaju i odgovaraju na oglase. Naša usluga uključuje:
                        </p>
                        <ul class="list-disc list-inside mb-6 space-y-2">
                            <li>Objavljivanje oglasa za prodaju, aukcije i poklone</li>
                            <li>Sistem poruka između kupaca i prodavaca</li>
                            <li>Upravljanje kreditima za korišćenje platforme</li>
                            <li>Favorite liste i personalizovane preporuke</li>
                        </ul>

                        <h2 class="text-2xl font-bold mb-4">3. Registracija naloga</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6">
                            <p class="font-semibold mb-2">Zahtevi za registraciju:</p>
                            <ul class="list-disc list-inside space-y-2">
                                <li>Morate imati najmanje 18 godina</li>
                                <li>Morate pružiti tačne i potpune informacije</li>
                                <li>Odgovorni ste za čuvanje pristupnih podataka</li>
                                <li>Jedan korisnik može imati samo jedan nalog</li>
                            </ul>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">4. Pravila objavljivanja oglasa</h2>
                        <div class="grid gap-4 mb-6">
                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-bold mb-2 text-red-600 dark:text-red-400">Zabranjeni sadržaj:</h3>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>Ilegalni proizvodi ili usluge</li>
                                    <li>Oružje i eksplozivi</li>
                                    <li>Falsifikovani ili ukradeni proizvodi</li>
                                    <li>Sadržaj koji promoviše mržnju ili diskriminaciju</li>
                                    <li>Lažni ili obmanjujući oglasi</li>
                                    <li>Sadržaj za odrasle</li>
                                </ul>
                            </div>

                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-bold mb-2 text-green-600 dark:text-green-400">Obaveze oglašivača:</h3>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>Pružanje tačnih informacija o proizvodu</li>
                                    <li>Postavljanje jasnih fotografija</li>
                                    <li>Poštovanje dogovorenih uslova prodaje</li>
                                    <li>Blagovremeno odgovaranje na poruke</li>
                                    <li>Ažuriranje statusa oglasa</li>
                                </ul>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">5. Sistem kredita</h2>
                        <p class="mb-4">Krediti su virtuelna valuta na PazAriO platformi:</p>
                        <ul class="list-disc list-inside mb-6 space-y-2">
                            <li>Potrebni su za objavljivanje oglasa</li>
                            <li>Mogu se zaraditi kroz različite aktivnosti</li>
                            <li>Mogu se transferovati između korisnika</li>
                            <li>Nemaju novčanu protivvrednost i ne mogu se povući</li>
                        </ul>

                        <h2 class="text-2xl font-bold mb-4">6. Komunikacija između korisnika</h2>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 mb-6">
                            <p class="font-semibold mb-2">Pravila komunikacije:</p>
                            <ul class="list-disc list-inside space-y-2">
                                <li>Zabranjena je upotreba uvredljivog jezika</li>
                                <li>Zabranjen je spam i neželjene poruke</li>
                                <li>Zabranjena je razmena ličnih podataka van platforme</li>
                                <li>Sve transakcije treba dogovarati putem naše platforme</li>
                            </ul>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">7. Odgovornost i garancije</h2>
                        <p class="mb-6">
                            PazAriO ne garantuje tačnost, potpunost ili kvalitet proizvoda oglašenih na platformi.
                            Mi smo posrednik između kupaca i prodavaca i ne učestvujemo direktno u transakcijama.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">8. Ograničenje odgovornosti</h2>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <p class="text-sm">
                                PazAriO neće biti odgovoran za bilo kakve direktne, indirektne, slučajne,
                                posebne ili posledične štete koje proizlaze iz korišćenja ili nemogućnosti
                                korišćenja naše platforme.
                            </p>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">9. Privatnost podataka</h2>
                        <p class="mb-6">
                            Vaša privatnost je važna za nas. Molimo vas da pregledate našu
                            <a href="{{ route('privacy') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                Politiku privatnosti
                            </a>
                            za informacije o tome kako prikupljamo, koristimo i štitimo vaše podatke.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">10. Izmene uslova</h2>
                        <p class="mb-6">
                            Zadržavamo pravo da izmenimo ove uslove u bilo kom trenutku.
                            Izmene stupaju na snagu odmah nakon objavljivanja na platformi.
                            Vaša dalja upotreba platforme nakon izmena predstavlja prihvatanje novih uslova.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">11. Prekid korišćenja</h2>
                        <p class="mb-6">
                            Možemo prekinuti ili suspendovati vaš nalog odmah, bez prethodnog obaveštenja,
                            ako smatramo da ste prekršili ove uslove korišćenja.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">12. Rešavanje sporova</h2>
                        <p class="mb-6">
                            Svi sporovi koji proizlaze iz korišćenja PazAriO platforme biće rešavani
                            mirnim putem. Ukoliko to nije moguće, nadležan je sud u Beogradu.
                        </p>

                        <h2 class="text-2xl font-bold mb-4">13. Kontakt</h2>
                        <p class="mb-6">
                            Za sva pitanja u vezi sa ovim uslovima korišćenja, možete nas kontaktirati:
                        </p>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <p class="font-semibold">PazAriO Support</p>
                            <p>Email: legal@pazario.com</p>
                            <p>Ili putem <a href="{{ route('messages.inbox') }}" class="text-blue-600 dark:text-blue-400 hover:underline">sistema poruka</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>