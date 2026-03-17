<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight px-4 sm:px-0">
                Profil
            </h2>

            {{-- Verification Status --}}
            <div class="px-4 sm:px-0">
                <a href="{{ route('verification.request') }}"
                    class="flex items-center p-4 rounded-lg transition-colors
                    @if(auth()->user()->isVerified())
                        bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 hover:bg-green-100 dark:hover:bg-green-900/50
                    @elseif(auth()->user()->isPendingVerification())
                        bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 hover:bg-amber-100 dark:hover:bg-amber-900/50
                    @elseif(auth()->user()->isVerificationRejected())
                        bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 hover:bg-red-100 dark:hover:bg-red-900/50
                    @else
                        bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700
                    @endif">
                    @if(auth()->user()->isVerified())
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800 dark:text-green-200">Verifikovan nalog</p>
                            <p class="text-sm text-green-600 dark:text-green-400">Vaš identitet je potvrđen.</p>
                        </div>
                    @elseif(auth()->user()->isPendingVerification())
                        <i class="fas fa-clock text-amber-600 dark:text-amber-400 text-lg mr-3"></i>
                        <div>
                            <p class="font-medium text-amber-800 dark:text-amber-200">Verifikacija na čekanju</p>
                            <p class="text-sm text-amber-600 dark:text-amber-400">Vaš zahtev se pregleda.</p>
                        </div>
                    @elseif(auth()->user()->isVerificationRejected())
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-lg mr-3"></i>
                        <div>
                            <p class="font-medium text-red-800 dark:text-red-200">Verifikacija odbijena</p>
                            <p class="text-sm text-red-600 dark:text-red-400">Kliknite da ponovite zahtev.</p>
                        </div>
                    @else
                        <i class="fas fa-user-shield text-slate-500 dark:text-slate-400 text-lg mr-3"></i>
                        <div>
                            <p class="font-medium text-slate-800 dark:text-slate-200">Verifikujte nalog</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Kliknite da pokrenete proces verifikacije.</p>
                        </div>
                    @endif
                    <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
                </a>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
