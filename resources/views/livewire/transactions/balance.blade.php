<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Current Balance Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Vaš kredit</h1>
                <div
                    class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-r from-blue-500 to-green-500 rounded-full mb-4">
                    <span
                        class="text-white text-3xl font-bold">{{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                </div>
                <p class="text-gray-600 text-lg">dinara</p>

                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('balance.payment-options') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Dopuni kredit
                    </a>

                    <a href="{{ route('balance.plan-selection') }}"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Vaš plan
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-arrow-up text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Ukupno dopunjeno</p>
                        <p class="text-xl font-semibold text-gray-900">
                            {{ number_format($this->totalTopup, 0, ',', '.') }} RSD
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-arrow-down text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Ukupno potrošeno</p>
                        <p class="text-xl font-semibold text-gray-900">
                            {{ number_format($this->totalSpent, 0, ',', '.') }} RSD
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-list text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Aktivni oglasi</p>
                        <p class="text-xl font-semibold text-gray-900">
                            {{ $this->activeListingsCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Poslednje transakcije</h2>

            @if ($this->transactions->count() > 0)
                <div class="space-y-3">
                    @foreach ($this->transactions as $transaction)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                @if ($transaction->type === 'credit_topup')
                                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                                        <i class="fas fa-plus text-green-600"></i>
                                    </div>
                                @else
                                    <div class="p-2 bg-red-100 rounded-lg mr-3">
                                        <i class="fas fa-minus text-red-600"></i>
                                    </div>
                                @endif

                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $transaction->description }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $transaction->created_at->format('d.m.Y H:i') }}
                                    </p>
                                    @if ($transaction->payment_method)
                                        <span class="text-xs text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <p
                                    class="font-bold {{ $transaction->type === 'credit_topup' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'credit_topup' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                    RSD
                                </p>
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ $transaction->status === 'completed'
                                        ? 'bg-green-100 text-green-800'
                                        : ($transaction->status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
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
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">
                        Pogledaj sve transakcije
                    </button>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-receipt text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">Nemate još uvek transakcija</p>
                    <p class="text-gray-500 text-sm">Dopunite svoj balans da biste mogli da postavljate oglase</p>
                </div>
            @endif
        </div>
    </div>
</div>
