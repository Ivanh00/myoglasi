<div class="min-h-screen bg-slate-50 dark:bg-slate-700 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Current Balance Card -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Vaš kredit</h1>
                <div
                    class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-r from-sky-500 to-green-500 rounded-full mb-4">
                    <span
                        class="text-white text-3xl font-bold">{{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                </div>
                <p class="text-slate-600 dark:text-slate-300 text-lg">dinara</p>

                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('balance.payment-options') }}"
                        class="inline-flex items-center px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Dopuni kredit
                    </a>

                    <a href="{{ route('balance.plan-selection') }}"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Vaš plan
                    </a>

                    <button wire:click="openTransferModal"
                        class="inline-flex items-center px-6 py-3 {{ auth()->user()->balance > 0 ? 'bg-purple-600 hover:bg-purple-700' : 'bg-slate-400 cursor-not-allowed' }} text-white font-semibold rounded-lg transition-colors"
                        {{ auth()->user()->balance <= 0 ? 'disabled' : '' }}
                        title="{{ auth()->user()->balance <= 0 ? 'Nemate dovoljno kredita za transfer' : 'Podelite kredit sa drugim korisnicima' }}">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Podeli kredit
                    </button>

                    <a href="{{ route('earn-credits.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition-colors">
                        <i class="fas fa-coins mr-2"></i>
                        Zaradi kredit
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-200 dark:bg-green-800 rounded-lg">
                        <i class="fas fa-arrow-up text-green-600 dark:text-green-300"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600 dark:text-slate-300">Ukupno dopunjeno</p>
                        <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                            {{ number_format($this->totalTopup, 0, ',', '.') }} RSD
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-200 dark:bg-red-800 rounded-lg">
                        <i class="fas fa-arrow-down text-red-600 dark:text-red-300"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600 dark:text-slate-300">Ukupno potrošeno</p>
                        <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                            {{ number_format($this->totalSpent, 0, ',', '.') }} RSD
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-sky-200 dark:bg-sky-800 rounded-lg">
                        <i class="fas fa-list text-sky-600 dark:text-sky-300"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600 dark:text-slate-300">Aktivni oglasi</p>
                        <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                            {{ $this->activeListingsCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">Poslednje transakcije</h2>

            @if ($this->transactions->count() > 0)
                <div class="space-y-3">
                    @foreach ($this->transactions as $transaction)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <div class="flex items-center">
                                @if (in_array($transaction->type, [
                                        'credit_topup',
                                        'game_earnings',
                                        'daily_contest_winner',
                                        'game_leaderboard_bonus',
                                        'credit_transfer_received',
                                    ]))
                                    <div class="p-2 bg-green-200 dark:bg-green-800 rounded-lg mr-3">
                                        <i class="fas fa-plus text-green-600 dark:text-green-300"></i>
                                    </div>
                                @else
                                    <div class="p-2 bg-red-200 dark:bg-red-800 rounded-lg mr-3">
                                        <i class="fas fa-minus text-red-600 dark:text-red-300"></i>
                                    </div>
                                @endif

                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $transaction->description }}
                                    </p>
                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                        {{ $transaction->created_at->format('d.m.Y H:i') }}
                                    </p>
                                    @if ($transaction->payment_method)
                                        <span class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <p
                                    class="font-bold {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300' }}">
                                    {{ in_array($transaction->type, ['credit_topup', 'game_earnings', 'daily_contest_winner', 'game_leaderboard_bonus', 'credit_transfer_received']) ? '+' : '-' }}{{ number_format(abs($transaction->amount), 0, ',', '.') }}
                                    RSD
                                </p>
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ $transaction->status === 'completed'
                                        ? 'bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200'
                                        : ($transaction->status === 'pending'
                                            ? 'bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200'
                                            : 'bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200') }}">
                                    @switch($transaction->status)
                                        @case('completed')
                                            Završeno
                                        @break

                                        @case('pending')
                                            Na čekanju
                                        @break

                                        @case('failed')
                                            Neuspešno
                                        @break

                                        @case('cancelled')
                                            Otkazano
                                        @break

                                        @case('awaiting_verification')
                                            Čeka verifikaciju
                                        @break

                                        @default
                                            {{ ucfirst($transaction->status) }}
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <button class="text-sky-600 dark:text-sky-300 hover:text-sky-800 font-semibold">
                        Pogledaj sve transakcije
                    </button>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-receipt text-slate-400 text-4xl mb-4"></i>
                    <p class="text-slate-600 dark:text-slate-300">Nemate još uvek transakcija</p>
                    <p class="text-slate-500 dark:text-slate-300 text-sm">Dopunite svoj balans da biste mogli da
                        postavljate oglase</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Credit Transfer Modal -->
    @if ($showTransferModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div
                class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-slate-800">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">
                            <i class="fas fa-exchange-alt text-purple-600 dark:text-purple-400 mr-2"></i>
                            Podeli kredit
                        </h3>
                        <button wire:click="$set('showTransferModal', false)"
                            class="text-slate-400 hover:text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Current Balance Info -->
                    <div
                        class="{{ auth()->user()->balance > 0 ? 'bg-sky-50 border-sky-200' : 'bg-red-50 border-red-200' }} p-3 rounded-lg mb-4 border">
                        <div class="flex items-center">
                            <i
                                class="fas fa-wallet {{ auth()->user()->balance > 0 ? 'text-sky-600 dark:text-sky-300' : 'text-red-600 dark:text-red-300' }} mr-2"></i>
                            <span
                                class="{{ auth()->user()->balance > 0 ? 'text-sky-900' : 'text-red-900' }} font-medium">
                                Vaš trenutni balans: {{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD
                            </span>
                        </div>
                        @if (auth()->user()->balance <= 0)
                            <div class="mt-2 text-xs text-red-700 dark:text-red-200">
                                ⚠️ Nemate dovoljno kredita za transfer. Dopunite balans da biste mogli da delite kredit.
                            </div>
                        @endif
                    </div>

                    <!-- Transfer Form -->
                    <form wire:submit.prevent="transferCredit">
                        <div class="space-y-4">
                            <!-- Recipient Search -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Kome
                                    šaljete kredit?</label>
                                <div class="relative">
                                    <input type="text" wire:model.live="recipientName"
                                        placeholder="Ukucajte ime korisnika..."
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:ring-purple-500 focus:border-purple-500">

                                    <!-- Search Results -->
                                    @if (!empty($userSearchResults))
                                        <div
                                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-40 overflow-y-auto">
                                            @foreach ($userSearchResults as $user)
                                                <button type="button"
                                                    wire:click="selectRecipient({{ $user->id }})"
                                                    class="w-full text-left px-3 py-2 hover:bg-purple-50 flex items-center">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-7000 flex items-center justify-center text-white text-xs mr-3">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium">
                                                            {{ $user->name }}
                                                            @if ($user->shouldShowLastSeen())
                                                                <span
                                                                    class="font-normal text-xs text-slate-500 dark:text-slate-300 ml-1">
                                                                    @if ($user->is_online)
                                                                        <span class="inline-flex items-center">
                                                                            <span
                                                                                class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                                                            {{ $user->last_seen }}
                                                                        </span>
                                                                    @else
                                                                        ({{ $user->last_seen }})
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-slate-500 dark:text-slate-300">
                                                            {{ $user->email }}</div>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Selected Recipient -->
                                @if ($selectedRecipient)
                                    <div
                                        class="mt-2 p-2 bg-green-50 border border-green-200 rounded flex items-center">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-300 mr-2"></i>
                                        <span class="text-green-900">Izabran: {{ $selectedRecipient->name }}</span>
                                        <button type="button" wire:click="$set('selectedRecipient', null)"
                                            class="ml-auto text-green-600 dark:text-green-300 hover:text-green-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif

                                @error('selectedRecipient')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Transfer Amount -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Iznos
                                    za transfer</label>
                                <input type="number" wire:model="transferAmount"
                                    min="{{ \App\Models\Setting::get('minimum_credit_transfer', 10) }}"
                                    step="10"
                                    placeholder="Unesite iznos u RSD (min. {{ number_format(\App\Models\Setting::get('minimum_credit_transfer', 10), 0, ',', '.') }} RSD)"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                @error('transferAmount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Optional Note -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Napomena
                                    (opciono)</label>
                                <textarea wire:model="transferNote" rows="2" placeholder="Razlog transfer-a ili poruka..."
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:ring-purple-500 focus:border-purple-500"></textarea>
                                @error('transferNote')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex items-center justify-end space-x-3 mt-6">
                            <button type="button" wire:click="$set('showTransferModal', false)"
                                class="px-4 py-2 bg-slate-300 text-slate-700 dark:text-slate-200 rounded-md hover:bg-slate-400">
                                Otkaži
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Pošalji kredit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
