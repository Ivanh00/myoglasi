<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use function Livewire\Volt\state;

state([
    'name' => fn() => auth()->user()->name,
    'email' => fn() => auth()->user()->email,
    'city' => fn() => auth()->user()->city,
    'phone' => fn() => auth()->user()->phone,
    'phone_visible' => fn() => auth()->user()->phone_visible,
]);

$updateProfileInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'city' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:20'],
        'phone_visible' => ['boolean'],
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

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Name')" />
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
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Grad/Mesto -->
        <div x-data="{ open: false, selected: @entangle('city') }" class="relative">
            <x-input-label for="city" :value="__('City')" />

            <!-- Dugme -->
            <button type="button" @click="open = !open"
                class="mt-1 w-full flex justify-between items-center border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-sm shadow-sm px-3 py-2 focus:outline-none">
                <span x-text="selected ? selected : '{{ __('Odaberi grad') }}'"></span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Popup -->
            <div x-show="open" x-transition @click.away="open = false"
                class="absolute z-20 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg p-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 max-h-72 overflow-y-auto">
                    @foreach (config('cities') as $city)
                        <button type="button" @click="selected = '{{ $city }}'; open = false"
                            class="w-full text-left px-2 py-2 rounded-md hover:bg-blue-500 hover:text-white transition">
                            {{ $city }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Hidden input -->
            <input type="hidden" name="city" :value="selected">
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>


        <!-- Telefon -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full"
                autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Vidljivost telefona -->
        <div class="block mt-4">
            <label for="phone_visible" class="flex items-center">
                <x-checkbox wire:model="phone_visible" id="phone_visible" name="phone_visible" />
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Make my phone number visible to other registered users') }}
                </span>
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('phone_visible')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
