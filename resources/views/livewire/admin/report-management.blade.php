<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Upravljanje prijavama</h2>
                <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje prijavama oglasa</p>
            </div>
            <div class="flex space-x-2">
                <button wire:click="resetFilters"
                    class="px-4 py-2 text-slate-600 hover:text-slate-800 border border-slate-300 rounded-lg">
                    Resetuj filtere
                </button>
                <button wire:click="exportReports"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2 "></i>
                    Izvezi CSV
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <input type="text" wire:model.live="search" placeholder="Pretraži prijave..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select wire:model.live="filterStatus"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="all">Svi statusi</option>
                    <option value="pending">Na čekanju</option>
                    <option value="reviewed">Pregledano</option>
                    <option value="resolved">Rešeno</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filterReason"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="all">Svi razlozi</option>
                    <option value="inappropriate_content">Neprikladan sadržaj</option>
                    <option value="fake_listing">Lažan oglas</option>
                    <option value="spam">Spam</option>
                    <option value="wrong_category">Pogrešna kategorija</option>
                    <option value="overpriced">Previsoka cena</option>
                    <option value="scam">Prevara</option>
                    <option value="duplicate">Duplikat oglas</option>
                    <option value="other">Ostalo</option>
                </select>
            </div>
            <div>
                <select wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="10">10 po stranici</option>
                    <option value="15">15 po stranici</option>
                    <option value="25">25 po stranici</option>
                    <option value="50">50 po stranici</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-slate-100 rounded-lg">
                    <i class="fas fa-flag text-slate-600 dark:text-slate-400 text-xl "></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500">Ukupno prijava</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 rounded-lg">
                    <i class="fas fa-clock text-amber-600 dark:text-amber-400 text-xl "></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500">Na čekanju</h3>
                    <p class="text-2xl font-semibold text-amber-600 dark:text-amber-400">{{ $stats['pending'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-sky-100 rounded-lg">
                    <i class="fas fa-eye text-sky-600 dark:text-sky-400 text-xl "></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500">Pregledano</h3>
                    <p class="text-2xl font-semibold text-sky-600 dark:text-sky-400">{{ $stats['reviewed'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl "></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500">Rešeno</h3>
                    <p class="text-2xl font-semibold text-green-600">{{ $stats['resolved'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Reports Table -->
    <div class="hidden lg:block bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 uppercase tracking-wider hover:text-slate-700">
                                <span>Datum</span>
                                @if ($sortField === 'created_at')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Prijavila</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Oglas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Razlog</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $report->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($report->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <i class="fas fa-clock mr-1 "></i>
                                            Na čekanju
                                        </span>
                                    @break

                                    @case('reviewed')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                            <i class="fas fa-eye mr-1 "></i>
                                            Pregledano
                                        </span>
                                    @break

                                    @case('resolved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1 "></i>
                                            Rešeno
                                        </span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($report->user->avatar)
                                        <img src="{{ $report->user->avatar_url }}" alt="{{ $report->user->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $report->user->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $report->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($report->listing->images->count() > 0)
                                        <img src="{{ $report->listing->images->first()->url }}"
                                            alt="{{ $report->listing->title }}"
                                            class="w-10 h-10 rounded object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400 "></i>
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ Str::limit($report->listing->title, 30) }}</div>
                                        <div class="text-sm text-slate-500">{{ $report->listing->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">
                                    @php
                                        $reasonLabels = [
                                            'inappropriate_content' => 'Neprikladan sadržaj',
                                            'fake_listing' => 'Lažan oglas',
                                            'spam' => 'Spam',
                                            'wrong_category' => 'Pogrešna kategorija',
                                            'overpriced' => 'Previsoka cena',
                                            'scam' => 'Prevara',
                                            'duplicate' => 'Duplikat oglas',
                                            'other' => 'Ostalo',
                                        ];
                                    @endphp
                                    {{ $reasonLabels[$report->reason] ?? $report->reason }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="viewReportDetails({{ $report->id }})"
                                        class="text-sky-600 hover:text-sky-900 p-1 rounded" title="Detalji">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>

                                    @if ($report->status === 'pending')
                                        <button wire:click="markAsReviewed({{ $report->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded"
                                            title="Označi kao pregledano">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if ($report->status !== 'resolved')
                                        <button wire:click="markAsResolved({{ $report->id }})"
                                            class="text-green-600 hover:text-green-900 p-1 rounded"
                                            title="Označi kao rešeno">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 12l2 2 4-4m5-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    <a href="{{ route('listings.show', $report->listing) }}" target="_blank"
                                        class="text-purple-600 hover:text-purple-900 p-1 rounded"
                                        title="Pogledaj oglas">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </a>

                                    @if ($report->listing && $report->listing->status === 'inactive')
                                        <button wire:click="restoreListing({{ $report->id }})"
                                            class="text-green-600 hover:text-green-900 p-1 rounded"
                                            title="Vrati oglas">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6">
                                                </path>
                                            </svg>
                                        </button>

                                        <button wire:click="confirmDeleteListing({{ $report->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded" title="Trajno obriši">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="confirmDeleteListing({{ $report->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded" title="Obriši oglas">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-slate-500">
                                    Nema prijava koje odgovaraju kriterijumima pretrage.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200">
                {{ $reports->links() }}
            </div>
        </div>

        <!-- Mobile Reports Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($reports as $report)
                <div class="bg-white shadow rounded-lg p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if ($report->listing->images->count() > 0)
                                    <img src="{{ $report->listing->images->first()->url }}"
                                        alt="{{ $report->listing->title }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <div class="h-10 w-10 rounded bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 "></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ Str::limit($report->listing->title, 25) }}</div>
                                <div class="text-xs text-slate-500">{{ $report->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @switch($report->status)
                                @case('pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <i class="fas fa-clock mr-1 "></i>
                                        Na čekanju
                                    </span>
                                @break

                                @case('reviewed')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                        <i class="fas fa-eye mr-1 "></i>
                                        Pregledano
                                    </span>
                                @break

                                @case('resolved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1 "></i>
                                        Rešeno
                                    </span>
                                @break
                            @endswitch
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Prijavio</div>
                            <div class="text-sm font-medium text-slate-900">{{ $report->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $report->user->email }}</div>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-lg">
                            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Razlog</div>
                            <div class="text-sm font-medium text-slate-900">
                                @php
                                    $reasonLabels = [
                                        'inappropriate_content' => 'Neprikladan sadržaj',
                                        'fake_listing' => 'Lažan oglas',
                                        'spam' => 'Spam',
                                        'wrong_category' => 'Pogrešna kategorija',
                                        'overpriced' => 'Previsoka cena',
                                        'scam' => 'Prevara',
                                        'duplicate' => 'Duplikat oglas',
                                        'other' => 'Ostalo',
                                    ];
                                @endphp
                                {{ $reasonLabels[$report->reason] ?? $report->reason }}
                            </div>
                        </div>
                    </div>

                    <!-- Owner Info -->
                    <div class="mb-4">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Vlasnik oglasa</div>
                        <div class="text-sm text-slate-900">{{ $report->listing->user->name }}</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="viewReportDetails({{ $report->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                            <i class="fas fa-eye mr-1 "></i>
                            Detalji
                        </button>

                        @if ($report->status === 'pending')
                            <button wire:click="markAsReviewed({{ $report->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-check mr-1 "></i>
                                Pregledano
                            </button>
                        @endif

                        @if ($report->status !== 'resolved')
                            <button wire:click="markAsResolved({{ $report->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-check-circle mr-1 "></i>
                                Rešeno
                            </button>
                        @endif

                        @if ($report->listing)
                            @if ($report->listing->status !== 'inactive')
                                <a href="{{ route('listings.show', $report->listing) }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-200 transition-colors">
                                    <i class="fas fa-external-link-alt mr-1 "></i>
                                    Pogledaj
                                </a>
                            @endif
                        @endif

                        @if ($report->listing && $report->listing->status === 'inactive')
                            <button wire:click="restoreListing({{ $report->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-undo mr-1 "></i>
                                Vrati oglas
                            </button>

                            <button wire:click="confirmDeleteListing({{ $report->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash-alt mr-1 "></i>
                                Trajno obriši
                            </button>
                        @else
                            <button wire:click="confirmDeleteListing({{ $report->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-1 "></i>
                                Obriši oglas
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <i class="fas fa-flag text-slate-400 text-5xl mb-4 "></i>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema prijava</h3>
                        <p class="text-slate-600 dark:text-slate-400">Nema prijava koje odgovaraju kriterijumima pretrage.</p>
                    </div>
                @endforelse

                <!-- Mobile Pagination -->
                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            </div>

            <!-- Report Details Modal -->
            @if ($showDetailsModal && $selectedReport)
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-10 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-medium text-slate-900">Detalji prijave #{{ $selectedReport->id }}</h3>
                                <button wire:click="$set('showDetailsModal', false)"
                                    class="text-slate-400 hover:text-slate-600 dark:text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Report Info -->
                                <div class="space-y-4">
                                    <div class="bg-slate-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-slate-800 mb-3">Informacije o prijavi</h4>

                                        <div class="space-y-3">
                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Status:</span>
                                                @switch($selectedReport->status)
                                                    @case('pending')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 ml-2">
                                                            <i class="fas fa-clock mr-1 "></i>
                                                            Na čekanju
                                                        </span>
                                                    @break

                                                    @case('reviewed')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 ml-2">
                                                            <i class="fas fa-eye mr-1 "></i>
                                                            Pregledano
                                                        </span>
                                                    @break

                                                    @case('resolved')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                            <i class="fas fa-check-circle mr-1 "></i>
                                                            Rešeno
                                                        </span>
                                                    @break
                                                @endswitch
                                            </div>

                                            <div>
                                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Datum
                                                    prijave:</span>
                                                <span
                                                    class="text-sm text-slate-900 ml-2">{{ $selectedReport->created_at->format('d.m.Y H:i') }}</span>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Razlog:</span>
                                                <span class="text-sm text-slate-900 ml-2">
                                                    @php
                                                        $reasonLabels = [
                                                            'inappropriate_content' => 'Neprikladan sadržaj',
                                                            'fake_listing' => 'Lažan oglas',
                                                            'spam' => 'Spam',
                                                            'wrong_category' => 'Pogrešna kategorija',
                                                            'overpriced' => 'Previsoka cena',
                                                            'scam' => 'Prevara',
                                                            'duplicate' => 'Duplikat oglas',
                                                            'other' => 'Ostalo',
                                                        ];
                                                    @endphp
                                                    {{ $reasonLabels[$selectedReport->reason] ?? $selectedReport->reason }}
                                                </span>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Prijavio:</span>
                                                <div class="mt-1">
                                                    <div class="text-sm font-medium text-slate-900">
                                                        {{ $selectedReport->user->name }}</div>
                                                    <div class="text-sm text-slate-500">{{ $selectedReport->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Report Details -->
                                    <div class="bg-slate-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-slate-800 mb-3">Detaljno objašnjenje</h4>
                                        <p class="text-sm text-slate-900 whitespace-pre-line">{{ $selectedReport->details }}
                                        </p>
                                    </div>

                                    <!-- Admin Notes -->
                                    <div class="bg-amber-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-amber-800 mb-3">Admin beleške</h4>
                                        <textarea wire:model="adminNotes" rows="3"
                                            class="w-full px-3 py-2 border border-amber-300 rounded-md focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                            placeholder="Dodaj beleške o ovoj prijavi...">{{ $selectedReport->admin_notes ?? '' }}</textarea>
                                        <div class="mt-2">
                                            <button wire:click="saveAdminNotes"
                                                class="px-3 py-1.5 bg-amber-600 text-white text-sm rounded hover:bg-amber-700">
                                                Sačuvaj beleške
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Listing Info -->
                                <div class="space-y-4">
                                    <div class="bg-slate-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-slate-800 mb-3">Prijavljeni oglas</h4>

                                        <!-- Listing Image -->
                                        <div class="mb-4">
                                            @if ($selectedReport->listing->images->count() > 0)
                                                <img src="{{ $selectedReport->listing->images->first()->url }}"
                                                    alt="{{ $selectedReport->listing->title }}"
                                                    class="w-full h-48 rounded-lg object-cover">
                                            @else
                                                <div
                                                    class="w-full h-48 rounded-lg bg-slate-200 flex items-center justify-center">
                                                    <i class="fas fa-image text-slate-400 text-4xl "></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="space-y-2">
                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Naslov:</span>
                                                <div class="text-sm font-medium text-slate-900 mt-1">
                                                    {{ $selectedReport->listing->title }}</div>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Cena:</span>
                                                <div class="text-sm text-slate-900 mt-1">
                                                    {{ number_format($selectedReport->listing->price, 0) }} RSD</div>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Vlasnik:</span>
                                                <div class="text-sm text-slate-900 mt-1">
                                                    {{ $selectedReport->listing->user->name }}
                                                    ({{ $selectedReport->listing->user->email }})
                                                </div>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-sm font-medium text-slate-600 dark:text-slate-400">Objavljen:</span>
                                                <div class="text-sm text-slate-900 mt-1">
                                                    {{ $selectedReport->listing->created_at->format('d.m.Y H:i') }}</div>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="mt-4 pt-4 border-t border-slate-200">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('listings.show', $selectedReport->listing) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200">
                                                    <i class="fas fa-external-link-alt mr-1 "></i>
                                                    Pogledaj oglas
                                                </a>

                                                @if ($selectedReport->status === 'pending')
                                                    <button wire:click="markAsReviewed({{ $selectedReport->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200">
                                                        <i class="fas fa-check mr-1 "></i>
                                                        Označi kao pregledano
                                                    </button>
                                                @endif

                                                @if ($selectedReport->status !== 'resolved')
                                                    <button wire:click="markAsResolved({{ $selectedReport->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200">
                                                        <i class="fas fa-check-circle mr-1 "></i>
                                                        Označi kao rešeno
                                                    </button>
                                                @endif

                                                <button wire:click="confirmDeleteListing({{ $selectedReport->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200">
                                                    <i class="fas fa-trash mr-1 "></i>
                                                    Obriši oglas
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delete Listing Confirmation Modal -->
            @if ($showDeleteModal && $selectedReport)
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class=" font-medium text-slate-900">Potvrdi brisanje</h3>
                                <button wire:click="$set('showDeleteModal', false)"
                                    class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-4">
                                <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2 "></i>
                                    <p class="text-sm text-red-700">
                                        Da li ste sigurni da želite da obrišete oglas
                                        "<strong>{{ $selectedReport->listing->title }}</strong>"?
                                        Ova akcija je nepovratna!
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Razlog brisanja
                                        (opciono)</label>
                                    <textarea wire:model="deleteReason" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-md"
                                        placeholder="Unesite razlog brisanja oglasa..."></textarea>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input type="checkbox" wire:model="notifyUser" id="notifyUser" class="rounded">
                                    <label for="notifyUser" class="ml-2 text-sm text-slate-700">
                                        Pošalji obaveštenje korisniku o brisanju oglasa
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="$set('showDeleteModal', false)"
                                    class="px-4 py-2 text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50">
                                    Otkaži
                                </button>
                                <button wire:click="deleteListing"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-trash mr-1 "></i>
                                    Obriši oglas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <script>
            // Close modals on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    @this.call('closeAllModals');
                }
            });

            // Prevent modal close when clicking inside modal content
            document.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('.fixed.inset-0');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        @this.call('closeAllModals');
                    }
                });
            });
        </script>
