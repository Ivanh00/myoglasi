<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-slate-800"
                    name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 px-4 text-base font-semibold">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Auth Links Below Button -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-4 pt-4 border-t border-slate-200">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    <i class="fas fa-key mr-1"></i>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a class="inline-flex items-center px-4 py-2 bg-sky-600 text-white text-sm font-medium rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors"
                    href="{{ route('register') }}" wire:navigate>
                    <i class="fas fa-user-plus mr-2"></i>
                    Nemaš nalog? Registruj se
                </a>
            @endif
        </div>
    </form>

    <!-- Magic Link Component -->
    <div class="mt-6">
        @livewire('auth.magic-login')
    </div>

    <!-- Social Login -->
    @if (
        \App\Models\Setting::get('google_login_enabled', false) ||
            \App\Models\Setting::get('facebook_login_enabled', false))
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-slate-500 dark:text-slate-300">ili se prijavite preko</span>
                </div>
            </div>

            <div
                class="mt-4 grid grid-cols-{{ \App\Models\Setting::get('google_login_enabled', false) && \App\Models\Setting::get('facebook_login_enabled', false) ? '2' : '1' }} gap-3">
                <!-- Google Login -->
                @if (\App\Models\Setting::get('google_login_enabled', false))
                    <a href="{{ route('auth.social.redirect', 'google') }}"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-slate-300 rounded-md shadow-sm bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Google
                    </a>
                @endif

                <!-- Facebook Login -->
                @if (\App\Models\Setting::get('facebook_login_enabled', false))
                    <a href="{{ route('auth.social.redirect', 'facebook') }}"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-slate-300 rounded-md shadow-sm bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="#1877F2" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        Facebook
                    </a>
                @endif
            </div>

            <p class="mt-3 text-xs text-slate-500 dark:text-slate-300 text-center">
                Prijavom preko social mreža automatski se kreira nalog sa vašim podacima
            </p>
        </div>
    @endif
</div>
