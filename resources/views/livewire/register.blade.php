<!-- resources/views/livewire/register.blade.php -->
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
                Kreiraj nalog
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Ili se
                <a href="{{ route('login') }}" class="font-medium text-sky-600 hover:text-sky-500">
                    prijavi ako imaš nalog
                </a>
            </p>
        </div>

        <form wire:submit.prevent="register" class="mt-8 space-y-6">
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">
                        Ime i prezime *
                    </label>
                    <input wire:model="name" type="text" id="name"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">
                        Email adresa *
                    </label>
                    <input wire:model="email" type="email" id="email"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">
                        Broj telefona
                    </label>
                    <input wire:model="phone" type="text" id="phone" placeholder="064/123-456"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Phone visibility checkbox -->
                    <div class="mt-2">
                        <label class="flex items-center">
                            <input wire:model="phone_visible" type="checkbox"
                                class="rounded border-slate-300 text-sky-600 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                Prikaži broj telefona u oglasima (preporučeno)
                            </span>
                        </label>
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                            Kupci će moći direktno da te kontaktiraju
                        </p>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">
                        Lozinka *
                    </label>
                    <input wire:model="password" type="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">
                        Potvrdi lozinku *
                    </label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                        class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    Registruj se
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Registracijom se slažeš sa našim uslovima korišćenja
                </p>
            </div>
        </form>
    </div>
</div>
