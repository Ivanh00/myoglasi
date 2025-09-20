<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Upravljanje transakcijama</h1>
                <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje svim transakcijama u sistemu</p>
            </div>
            <button wire:click="exportTransactions"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Izvezi u CSV
            </button>
        </div>
    </div>

    <!-- Statistike -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-sky-100 rounded-lg">
                    <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupno transakcija</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Završene</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Na čekanju</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Neuspešne</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['failed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Finansijske statistike -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-center">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupan iznos</h3>
                <p class="text-2xl font-semibold text-green-600">{{ number_format($stats['totalAmount'], 2) }} RSD</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-center">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupne uplate</h3>
                <p class="text-2xl font-semibold text-sky-600">{{ number_format($stats['totalDeposits'], 2) }} RSD</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-center">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupne naknade</h3>
                <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                    {{ number_format($stats['totalFees'], 2) }} RSD</p>
            </div>
        </div>
    </div>

    <!-- Filteri i pretraga -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            <!-- Pretraga -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Pretraga</label>
                <input type="text" wire:model.live="search" placeholder="Pretraži transakcije..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <!-- Tip transakcije -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Tip</label>
                <select wire:model.live="filters.type" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi tipovi</option>
                    @foreach ($typeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Status</label>
                <select wire:model.live="filters.status" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi statusi</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Korisnik -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Korisnik</label>
                <select wire:model.live="filters.user_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi korisnici</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Datum od -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Datum od</label>
                <input type="date" wire:model.live="filters.date_from"
                    value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>

            <!-- Datum do -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Datum do</label>
                <input type="date" wire:model.live="filters.date_to"
                    value="{{ request('date_to', now()->endOfMonth()->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>

            <!-- Broj stavki -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Po strani</label>
                <select wire:model.live="perPage" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Pronađeno: {{ $transactions->total() }} transakcija
            </div>
            <div>
                <button wire:click="resetFilters"
                    class="px-3 py-1 text-sm text-slate-600 border border-slate-300 rounded hover:bg-slate-50">
                    Resetuj filtere
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Tabela transakcija -->
    <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            Datum
                            @if ($sortField === 'created_at')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Korisnik</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Tip
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('amount')">
                            Iznos
                            @if ($sortField === 'amount')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Opis
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $transaction->created_at->format('d.m.Y.') }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">
                                    {{ $transaction->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($transaction->user->avatar)
                                        <img src="{{ $transaction->user->avatar_url }}"
                                            alt="{{ $transaction->user->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $transaction->user->name }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ $transaction->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if ($transaction->type === 'deposit') bg-sky-100 text-sky-800
                                @elseif($transaction->type === 'withdrawal') bg-green-100 text-green-800
                                @elseif($transaction->type === 'fee') bg-red-100 text-red-800
                                @elseif($transaction->type === 'refund') bg-amber-100 text-amber-800
                                @else bg-slate-100 text-slate-800 @endif">
                                    {{ $typeOptions[$transaction->type] ?? $transaction->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="text-sm font-medium 
                                @if ($transaction->amount < 0) text-red-600 @else text-green-600 @endif">
                                    {{ number_format($transaction->amount, 2) }} RSD
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">
                                    {{ Str::limit($transaction->description, 40) }}
                                    @if (strlen($transaction->description) > 40)
                                        <button wire:click="viewTransaction({{ $transaction->id }})"
                                            class="text-sky-600 dark:text-sky-400 text-xs ml-1">
                                            više...
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if ($transaction->status === 'completed' || in_array($transaction->id, $processedTransactions)) bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-amber-100 text-amber-800
                                @elseif($transaction->status === 'failed') bg-red-100 text-red-800 @endif">
                                    @if (in_array($transaction->id, $processedTransactions))
                                        Završeno
                                    @else
                                        {{ $statusOptions[$transaction->status] }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="viewTransaction({{ $transaction->id }})"
                                        class="text-sky-600 hover:text-sky-900" title="Pregled">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>

                                    @if ($transaction->status !== 'completed' && !in_array($transaction->id, $processedTransactions))
                                        <button wire:click="editTransaction({{ $transaction->id }})"
                                            class="text-amber-600 hover:text-amber-900" title="Izmeni">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if ($transaction->status === 'pending' && !in_array($transaction->id, $processedTransactions))
                                        <button wire:click="markAsCompleted({{ $transaction->id }})"
                                            class="text-green-600 hover:text-green-900" title="Označi kao završeno">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>

                                        <button wire:click="markAsFailed({{ $transaction->id }})"
                                            class="text-red-600 hover:text-red-900" title="Označi kao neuspešno">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-300">
                                Nema pronađenih transakcija.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Mobile Transaction Cards -->
    <div class="lg:hidden space-y-4">
        @forelse($transactions as $transaction)
            <div class="bg-white shadow rounded-lg p-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center
                            @if ($transaction->amount > 0) bg-green-100 text-green-600
                            @else bg-red-100 text-red-600 @endif">
                            @if ($transaction->amount > 0)
                                <i class="fas fa-plus"></i>
                            @else
                                <i class="fas fa-minus"></i>
                            @endif
                        </div>
                        <div class="ml-3 flex-1">
                            <div
                                class="text-lg font-bold {{ $transaction->amount > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                RSD
                            </div>
                            <div class="text-sm text-slate-500 dark:text-slate-300">ID: {{ $transaction->id }}</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if ($transaction->status === 'completed' || in_array($transaction->id, $processedTransactions)) bg-green-100 text-green-800
                        @elseif($transaction->status === 'pending') bg-amber-100 text-amber-800
                        @elseif($transaction->status === 'failed') bg-red-100 text-red-800 @endif">
                        @if (in_array($transaction->id, $processedTransactions))
                            Završeno
                        @else
                            {{ $statusOptions[$transaction->status] }}
                        @endif
                    </span>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <div class="text-sm text-slate-900">{{ $transaction->description }}</div>
                    @if ($transaction->type)
                        <div class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                            {{ $typeOptions[$transaction->type] ?? ucfirst($transaction->type) }}</div>
                    @endif
                </div>

                <!-- User Info -->
                @if ($transaction->user)
                    <div class="bg-slate-50 p-3 rounded-lg mb-4">
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Korisnik</div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                @if ($transaction->user->avatar)
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ $transaction->user->avatar_url }}"
                                        alt="{{ $transaction->user->name }}">
                                @else
                                    <div
                                        class="h-8 w-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-xs">
                                        {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-slate-900">{{ $transaction->user->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">
                                    {{ $transaction->user->email }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Date -->
                <div class="mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Datum</div>
                    <div class="text-sm text-slate-900">{{ $transaction->created_at->format('d.m.Y H:i') }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="viewTransaction({{ $transaction->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Pregled
                    </button>

                    @if ($transaction->status !== 'completed' && !in_array($transaction->id, $processedTransactions))
                        <button wire:click="editTransaction({{ $transaction->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>
                            Izmeni
                        </button>
                    @endif

                    @if ($transaction->status === 'pending' && !in_array($transaction->id, $processedTransactions))
                        <button wire:click="markAsCompleted({{ $transaction->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-check mr-1"></i>
                            Završi
                        </button>

                        <button wire:click="markAsFailed({{ $transaction->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                            <i class="fas fa-times mr-1"></i>
                            Neuspešno
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-receipt text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema transakcija</h3>
                <p class="text-slate-600 dark:text-slate-400">Nema transakcija koje odgovaraju kriterijumima pretrage.
                </p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- View Modal -->
    @if ($showViewModal && $selectedTransaction)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Detalji transakcije</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Korisnik:</h4>
                            <div class="flex items-center mt-1">
                                @if ($selectedTransaction->user->avatar)
                                    <img src="{{ $selectedTransaction->user->avatar_url }}"
                                        alt="{{ $selectedTransaction->user->name }}"
                                        class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                        {{ strtoupper(substr($selectedTransaction->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $selectedTransaction->user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-300">
                                        {{ $selectedTransaction->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Datum:</h4>
                            <p class="text-sm text-slate-900 mt-1">
                                {{ $selectedTransaction->created_at->format('d.m.Y. H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Tip transakcije:</h4>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if ($selectedTransaction->type === 'deposit') bg-sky-100 text-sky-800
                            @elseif($selectedTransaction->type === 'withdrawal') bg-green-100 text-green-800
                            @elseif($selectedTransaction->type === 'fee') bg-red-100 text-red-800
                            @elseif($selectedTransaction->type === 'refund') bg-amber-100 text-amber-800
                            @else bg-slate-100 text-slate-800 @endif">
                                {{ $typeOptions[$selectedTransaction->type] ?? $selectedTransaction->type }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Status:</h4>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if ($selectedTransaction->status === 'completed') bg-green-100 text-green-800
                            @elseif($selectedTransaction->status === 'pending') bg-amber-100 text-amber-800
                            @elseif($selectedTransaction->status === 'failed') bg-red-100 text-red-800 @endif">
                                {{ $statusOptions[$selectedTransaction->status] }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Iznos:</h4>
                        <p
                            class="text-2xl font-semibold 
                        @if ($selectedTransaction->amount < 0) text-red-600 @else text-green-600 @endif">
                            {{ number_format($selectedTransaction->amount, 2) }} RSD
                        </p>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Opis:</h4>
                        <p class="text-sm text-slate-900 mt-1">{{ $selectedTransaction->description }}</p>
                    </div>

                    <div class="flex justify-end">
                        <button wire:click="$set('showViewModal', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Zatvori
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Update Modal -->
    @if ($showUpdateModal && $selectedTransaction)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Izmeni transakciju</h3>

                    <form wire:submit.prevent="updateTransaction">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status
                                    *</label>
                                <select wire:model="updateState.status"
                                    class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('updateState.status')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-200">Opis</label>
                                <textarea wire:model="updateState.description" rows="3"
                                    class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2"></textarea>
                                @error('updateState.description')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="$set('showUpdateModal', false)"
                                class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                                Otkaži
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700">
                                Sačuvaj izmene
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
