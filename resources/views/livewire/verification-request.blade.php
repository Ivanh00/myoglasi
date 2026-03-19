<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight px-4 sm:px-0">
            Verifikacija naloga
        </h2>

        {{-- Flash messages --}}
        @if (session()->has('success'))
            <div class="mx-4 sm:mx-0 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-2"></i>
                    <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-4 sm:mx-0 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mr-2"></i>
                    <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Status Banner --}}
        <div class="mx-4 sm:mx-0 p-4 rounded-lg
            @if ($status === 'verified') bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700
            @elseif ($status === 'pending') bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700
            @elseif ($status === 'rejected') bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700
            @else bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700
            @endif">
            <div class="flex items-center">
                @if ($status === 'verified')
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-green-800 dark:text-green-200">Vaš nalog je verifikovan</p>
                        <p class="text-sm text-green-700 dark:text-green-300">Vaš identitet je potvrđen. Verifikovani korisnici uživaju veće poverenje.</p>
                    </div>
                @elseif ($status === 'pending')
                    <i class="fas fa-clock text-amber-600 dark:text-amber-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-amber-800 dark:text-amber-200">Zahtev je na čekanju</p>
                        <p class="text-sm text-amber-700 dark:text-amber-300">Vaš zahtev za verifikaciju se pregleda. Bićete obavešteni o odluci.</p>
                    </div>
                @elseif ($status === 'rejected')
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-red-800 dark:text-red-200">Verifikacija je odbijena</p>
                        @if ($rejectionComment)
                            <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                                <strong>Razlog:</strong> {{ $rejectionComment }}
                            </p>
                        @endif
                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">Možete ponovo podneti zahtev sa ispravnim podacima.</p>
                    </div>
                @else
                    <i class="fas fa-user-shield text-slate-500 dark:text-slate-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-slate-200">Nalog nije verifikovan</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Verifikujte svoj nalog slanjem lične karte i adrese prebivališta.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pending: show submitted info (without images for security) --}}
        @if ($status === 'pending' && $existingDocument)
            <div class="mx-4 sm:mx-0 p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Podneti podaci</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Ime i prezime</p>
                        <p class="text-slate-900 dark:text-slate-100">{{ $existingDocument->first_name }} {{ $existingDocument->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Adresa</p>
                        <p class="text-slate-900 dark:text-slate-100">{{ $existingDocument->street_address }} {{ $existingDocument->street_number }}, {{ $existingDocument->city }}</p>
                    </div>
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <i class="fas fa-check mr-2"></i>
                        Slike lične karte su uspešno podnete
                    </div>
                </div>
            </div>
        @endif

        {{-- Form: show for unverified and rejected --}}
        @if (in_array($status, ['unverified', 'rejected']))
            <div class="mx-4 sm:mx-0 p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-1">Podnesite zahtev za verifikaciju</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Unesite vaše ime i prezime, priložite slike lične karte (prednja i zadnja strana) i unesite adresu prebivališta.</p>

                <form wire:submit="submit" class="space-y-6">
                    {{-- Name fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Ime <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="firstName" type="text" id="firstName"
                                class="w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            @error('firstName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Prezime <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="lastName" type="text" id="lastName"
                                class="w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            @error('lastName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- ID Front --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Prednja strana lične karte <span class="text-red-500">*</span>
                        </label>
                        @if ($idFront)
                            <div class="mb-2">
                                <img src="{{ $idFront->temporaryUrl() }}" alt="Preview prednja strana"
                                    class="max-h-48 rounded-lg border border-slate-200 dark:border-slate-600">
                            </div>
                        @endif
                        <input wire:model="idFront" type="file" accept="image/*"
                            class="block w-full text-sm text-slate-500 dark:text-slate-400
                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                            file:text-sm file:font-medium file:bg-sky-50 file:text-sky-700
                            dark:file:bg-sky-900/50 dark:file:text-sky-300
                            hover:file:bg-sky-100 dark:hover:file:bg-sky-900/70 file:cursor-pointer">
                        @error('idFront') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="idFront" class="text-sm text-sky-600 dark:text-sky-400 mt-1">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Učitavanje...
                        </div>
                    </div>

                    {{-- ID Back --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Zadnja strana lične karte <span class="text-red-500">*</span>
                        </label>
                        @if ($idBack)
                            <div class="mb-2">
                                <img src="{{ $idBack->temporaryUrl() }}" alt="Preview zadnja strana"
                                    class="max-h-48 rounded-lg border border-slate-200 dark:border-slate-600">
                            </div>
                        @endif
                        <input wire:model="idBack" type="file" accept="image/*"
                            class="block w-full text-sm text-slate-500 dark:text-slate-400
                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                            file:text-sm file:font-medium file:bg-sky-50 file:text-sky-700
                            dark:file:bg-sky-900/50 dark:file:text-sky-300
                            hover:file:bg-sky-100 dark:hover:file:bg-sky-900/70 file:cursor-pointer">
                        @error('idBack') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="idBack" class="text-sm text-sky-600 dark:text-sky-400 mt-1">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Učitavanje...
                        </div>
                    </div>

                    {{-- Address fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="streetAddress" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Ulica <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="streetAddress" type="text" id="streetAddress"
                                class="w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            @error('streetAddress') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="streetNumber" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Broj <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="streetNumber" type="text" id="streetNumber"
                                class="w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            @error('streetNumber') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- City --}}
                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Grad <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="city" type="text" id="city"
                            class="w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-sky-600 text-white font-medium rounded-lg hover:bg-sky-700 transition-colors"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50">
                            <span wire:loading.remove wire:target="submit">
                                <i class="fas fa-paper-plane mr-2"></i> Podnesi zahtev
                            </span>
                            <span wire:loading wire:target="submit">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Slanje...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
