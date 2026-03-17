<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state(['password' => '']);

rules(['password' => ['required', 'string', 'current_password']]);

$deleteUser = function (Logout $logout) {
    $this->validate();

    tap(Auth::user(), $logout(...))->delete();

    $this->redirect('/', navigate: true);
};

?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
            {{ __('Brisanje naloga') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __('Kada se vaš nalog obriše, svi njegovi podaci će biti trajno izbrisani. Pre brisanja naloga, preuzmite sve podatke ili informacije koje želite da sačuvate.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Obriši nalog') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
                {{ __('Da li ste sigurni da želite da obrišete nalog?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                {{ __('Kada se vaš nalog obriše, svi njegovi podaci će biti trajno izbrisani. Unesite lozinku da potvrdite brisanje naloga.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Lozinka') }}" class="sr-only" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Lozinka') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Otkaži') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Obriši nalog') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
