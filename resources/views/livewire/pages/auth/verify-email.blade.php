<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;

layout('layouts.guest');

$sendVerification = function () {
    if (Auth::user()->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

        return;
    }

    Auth::user()->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<div>
    <div class="text-center mb-6">
        <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope text-sky-600 text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Verifikuj svoj email</h2>
    </div>

    <div class="mb-6 text-sm text-slate-600 dark:text-slate-400 text-center">
        <p class="mb-3">Hvala što ste se registrovali! Pre nego što počnete da koristite PazAriO, molimo vas da verifikujete vašu email adresu klikom na link koji smo vam poslali.</p>
        <p>Ako niste dobili email, možemo vam poslati novi.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="font-medium text-sm text-green-600">
                    Novi verification link je poslat na vašu email adresu!
                </span>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <x-primary-button wire:click="sendVerification" class="w-full justify-center">
            <i class="fas fa-paper-plane mr-2"></i>
            Pošalji novi verification email
        </x-primary-button>

        <div class="text-center">
            <button wire:click="logout" type="submit" class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-sign-out-alt mr-1"></i>
                Odjavi se
            </button>
        </div>
    </div>
</div>
