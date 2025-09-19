<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

use function Livewire\Volt\state;

state([
    'name' => fn() => auth()->user()->name,
    'email' => fn() => auth()->user()->email,
    'city' => fn() => auth()->user()->city,
    'phone' => fn() => auth()->user()->phone,
    'phone_visible' => fn() => auth()->user()->phone_visible,
    'seller_terms' => fn() => auth()->user()->seller_terms,
]);

$updateProfileInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'city' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:20'],
        'phone_visible' => ['boolean'],
        'seller_terms' => ['nullable', 'string', 'max:2000'],
    ]);

    $user->fill($validated);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    $this->dispatch('profile-updated', name: $user->name);
};

$sendVerification = function () {
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

// Dodajte ovaj trait za upload fajlova
use function Livewire\Volt\uses;

uses([WithFileUploads::class]);

state([
    'name' => fn() => auth()->user()->name,
    'email' => fn() => auth()->user()->email,
    'city' => fn() => auth()->user()->city,
    'phone' => fn() => auth()->user()->phone,
    'phone_visible' => fn() => auth()->user()->phone_visible,
    'seller_terms' => fn() => auth()->user()->seller_terms,
    'avatar' => null,
    'remove_avatar' => false,
]);

$updateProfileInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'city' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:20'],
        'phone_visible' => ['boolean'],
        'seller_terms' => ['nullable', 'string', 'max:2000'],
        'avatar' => ['nullable', 'image', 'max:2048'],
    ]);

    // Obrada uklanjanja avatara
    if ($this->remove_avatar && $user->avatar) {
        Storage::disk('public')->delete($user->avatar);
        $validated['avatar'] = null;
        $this->remove_avatar = false;
    } else {
        // Samo ako nije zahtevano uklanjanje, obradi upload
        if ($this->avatar instanceof \Illuminate\Http\UploadedFile) {
            // Obrišite stari avatar ako postoji
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Sačuvajte novi avatar
            $validated['avatar'] = $this->avatar->store('avatars', 'public');
        } else {
            // Ako nema novog uploada, zadržite postojeći avatar
            unset($validated['avatar']);
        }
    }

    $user->fill($validated);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    // Resetujte avatar property nakon čuvanja
    $this->avatar = null;

    $this->dispatch('profile-updated', name: $user->name);
};

$removeAvatar = function () {
    $this->remove_avatar = true;
    $this->avatar = null;
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
            {{ __('Informacije o profilu') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __('Ažurirajte informacije o profilu vašeg naloga i adresu e-pošte.') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Ime')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-800 dark:text-slate-200">
                        {{ __('Vaša email adresa nije verifikovana.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:focus:ring-offset-slate-800">
                            {{ __('Kliknite ovde da ponovo pošaljete verifikacioni email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Nova verifikaciona veza je poslata na vašu email adresu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Grad/Mesto -->
        <div x-data="{
            open: false,
            selected: @entangle('city'),
            search: '',
            normalize(str) {
                const map = {
                    // latinica
                    'š': 's',
                    's': 's',
                    'ć': 'c',
                    'č': 'c',
                    'c': 'c',
                    'ž': 'z',
                    'z': 'z',
                    'đ': 'dj',
                    'd': 'd',
        
                    // ćirilica
                    'а': 'a',
                    'б': 'b',
                    'в': 'v',
                    'г': 'g',
                    'д': 'd',
                    'ђ': 'dj',
                    'е': 'e',
                    'ж': 'z',
                    'з': 'z',
                    'и': 'i',
                    'ј': 'j',
                    'к': 'k',
                    'л': 'l',
                    'љ': 'lj',
                    'м': 'm',
                    'н': 'n',
                    'њ': 'nj',
                    'о': 'o',
                    'п': 'p',
                    'р': 'r',
                    'с': 's',
                    'т': 't',
                    'ћ': 'c',
                    'у': 'u',
                    'ф': 'f',
                    'х': 'h',
                    'ц': 'c',
                    'ч': 'c',
                    'џ': 'dz',
                    'ш': 's',
        
                    // velika ćirilica
                    'А': 'a',
                    'Б': 'b',
                    'В': 'v',
                    'Г': 'g',
                    'Д': 'd',
                    'Ђ': 'dj',
                    'Е': 'e',
                    'Ж': 'z',
                    'З': 'z',
                    'И': 'i',
                    'Ј': 'j',
                    'К': 'k',
                    'Л': 'l',
                    'Љ': 'lj',
                    'М': 'm',
                    'Н': 'n',
                    'Њ': 'nj',
                    'О': 'o',
                    'П': 'p',
                    'Р': 'r',
                    'С': 's',
                    'Т': 't',
                    'Ћ': 'c',
                    'У': 'u',
                    'Ф': 'f',
                    'Х': 'h',
                    'Ц': 'c',
                    'Ч': 'c',
                    'Џ': 'dz',
                    'Ш': 's',
                };
                return str.toLowerCase().split('').map(ch => map[ch] || ch).join('');
            },
            get filteredCities() {
                return @js(config('cities')).filter(c =>
                    this.normalize(c).includes(this.normalize(this.search))
                );
            }
        }" class="relative">

            <x-input-label for="city" :value="__('Grad')" />

            <!-- Dugme -->
            <button type="button" @click="open = !open"
                class="mt-1 w-full flex justify-between items-center border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-md shadow-sm px-3 py-2 focus:outline-none">
                <span x-text="selected ? selected : '{{ __('Odaberi grad') }}'"></span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Popup -->
            <div x-show="open" x-transition @click.away="open = false"
                class="absolute z-20 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-md shadow-lg">

                <!-- Search bar -->
                <div class="p-2 border-b border-slate-200 dark:border-slate-700">
                    <input type="text" x-model="search" placeholder="Pretraži grad..."
                        class="w-full px-3 py-2 border rounded-md dark:bg-slate-900 dark:text-slate-300 focus:outline-none focus:ring focus:border-indigo-500">
                </div>

                <!-- Lista gradova -->
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 max-h-72 overflow-y-auto">
                    <template x-for="city in filteredCities" :key="city">
                        <button type="button" @click="selected = city; open = false"
                            class="w-full text-left px-2 py-2 rounded-md hover:bg-indigo-500 hover:text-white transition"
                            :class="selected === city ? 'bg-indigo-600 text-white' : ''">
                            <span x-text="city"></span>
                        </button>
                    </template>
                    <div x-show="filteredCities.length === 0" class="col-span-full text-center text-slate-500 dark:text-slate-300 py-2">
                        Nema rezultata
                    </div>
                </div>
            </div>

            <!-- Hidden input -->
            <input type="hidden" name="city" :value="selected">
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>





        <!-- Telefon -->
        <div>
            <x-input-label for="phone" :value="__('Telefon')" />
            <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full"
                autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Vidljivost telefona -->
        <div class="block mt-4">
            <label for="phone_visible" class="flex items-center">
                <x-checkbox wire:model="phone_visible" id="phone_visible" name="phone_visible" />
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ __('Prikaži broj telefona drugim registrovanim korisnicima') }}
                </span>
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('phone_visible')" />
        </div>

        <!-- Uslovi prodaje -->
        <div>
            <x-input-label for="seller_terms" :value="__('Uslovi prodavca')" />
            <textarea wire:model.defer="seller_terms" id="seller_terms" name="seller_terms" rows="4"
                class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 
               dark:bg-slate-900 dark:text-slate-300 focus:border-indigo-500 
               focus:ring-indigo-500 shadow-sm sm:text-sm"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('seller_terms')" />
        </div>


        <!-- Avatar -->
        <div>
            <x-input-label for="avatar" :value="__('Profile Picture')" />

            @if (auth()->user()->avatar && !$remove_avatar)
                <div class="mt-2 mb-2 flex items-center">
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                        class="w-20 h-20 rounded-full object-cover mr-4">
                    <button type="button" wire:click="removeAvatar"
                        class="bg-red-600 text-white uppercase hover:bg-red-500 text-xs font-bold px-4 py-2 rounded">
                        {{ __('Izbriši') }}
                    </button>
                </div>
            @endif

            @if (!$remove_avatar)
                <x-text-input wire:model="avatar" id="avatar" name="avatar" type="file"
                    class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

                @if ($avatar)
                    <div class="mt-2">
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Preview"
                            class="w-20 h-20 rounded-full object-cover">
                    </div>
                @endif
            @else
                <div class="mt-2 text-green-600">
                    {{ __('Fotografija će biti uklonjena kada sačuvate promene.') }}
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('confirm-remove-avatar', () => {
            if (confirm('Da li ste sigurni da želite da uklonite profilnu sliku?')) {
                @this.call('removeAvatar');
            }
        });
    });
</script>
