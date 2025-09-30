<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Upravljanje ocenama</h1>
                <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje svim korisničkim ocenama u sistemu
                </p>
            </div>
        </div>
    </div>

    <!-- Rating Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-sky-100 rounded-lg">
                    <span class="text-2xl">📊</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupno ocena</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <span class="text-2xl">😊</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Pozitivne</h3>
                    <p class="text-2xl font-semibold text-green-600">{{ $stats['positive'] }}</p>
                    <p class="text-xs text-slate-400">{{ $stats['positive_percentage'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 rounded-lg">
                    <span class="text-2xl">😐</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Neutralne</h3>
                    <p class="text-2xl font-semibold text-amber-600 dark:text-amber-400">{{ $stats['neutral'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <span class="text-2xl">😞</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Negativne</h3>
                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $stats['negative'] }}</p>
                    <p class="text-xs text-slate-400">{{ $stats['negative_percentage'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Pretraži</label>
                    <input type="text" wire:model.live="search" placeholder="Korisnik, komentar, oglas..."
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Od datuma</label>
                    <input type="date" wire:model.live="filters.date_from"
                        value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}"
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Do datuma</label>
                    <input type="date" wire:model.live="filters.date_to"
                        value="{{ request('date_to', now()->endOfMonth()->format('Y-m-d')) }}"
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <!-- Reset Filters -->
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="w-full bg-slate-500 text-white px-4 py-2 rounded-md hover:bg-slate-600">
                        Resetuj filtere
                    </button>
                </div>
            </div>

            <!-- Rating Type Filter (Smiley Buttons) -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Tip ocene</label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setRatingFilter('')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filters['rating_type'] === '' ? 'bg-sky-100 text-sky-700 shadow-md' : 'bg-slate-100 hover:bg-slate-200' }}">
                        <span class="text-xl">📊</span>
                        <span>Sve ({{ $stats['total'] }})</span>
                    </button>

                    <button wire:click="setRatingFilter('positive')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filters['rating_type'] === 'positive' ? 'bg-green-100 text-green-700 shadow-md' : 'bg-slate-100 hover:bg-green-50' }}">
                        <span class="text-xl">😊</span>
                        <span>Pozitivne ({{ $stats['positive'] }})</span>
                    </button>

                    <button wire:click="setRatingFilter('neutral')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filters['rating_type'] === 'neutral' ? 'bg-amber-100 text-amber-700 shadow-md' : 'bg-slate-100 hover:bg-amber-50' }}">
                        <span class="text-xl">😐</span>
                        <span>Neutralne ({{ $stats['neutral'] }})</span>
                    </button>

                    <button wire:click="setRatingFilter('negative')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all {{ $filters['rating_type'] === 'negative' ? 'bg-red-100 text-red-700 shadow-md' : 'bg-slate-100 hover:bg-red-50' }}">
                        <span class="text-xl">😞</span>
                        <span>Negativne ({{ $stats['negative'] }})</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100"
                            wire:click="sortBy('rating')">
                            Ocena
                            @if ($sortField === 'rating')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Ocenio
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Ocenjen
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Oglas
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Komentar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer hover:bg-slate-100"
                            wire:click="sortBy('created_at')">
                            Datum
                            @if ($sortField === 'created_at')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Akcije
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($ratings as $rating)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-2">{{ $rating->rating_icon }}</span>
                                    <span class="text-sm font-medium {{ $rating->rating_color }}">
                                        {{ ucfirst($rating->rating) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $rating->rater->name }}</div>
                                <div class="text-sm text-slate-500 dark:text-slate-300">{{ $rating->rater->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $rating->ratedUser->name }}</div>
                                <div class="text-sm text-slate-500 dark:text-slate-300">{{ $rating->ratedUser->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($rating->listing)
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ Str::limit($rating->listing->title, 30) }}</div>
                                @else
                                    <span class="text-sm text-slate-400">Oglas obrisan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($rating->comment)
                                    <div class="text-sm text-slate-900">{{ Str::limit($rating->comment, 50) }}</div>
                                @else
                                    <span class="text-sm text-slate-400 italic">Bez komentara</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-300">
                                {{ $rating->created_at->format('d.m.Y. H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="editRating({{ $rating->id }})"
                                        class="text-sky-600 hover:text-sky-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $rating->id }})"
                                        class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-slate-500 dark:text-slate-300">
                                Nema pronađenih ocena za zadati filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @forelse($ratings as $rating)
            <div
                class="bg-white rounded-lg shadow border-l-4 {{ $rating->rating === 'positive' ? 'border-green-500' : ($rating->rating === 'neutral' ? 'border-amber-500' : 'border-red-500') }}">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $rating->rating_icon }}</span>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">{{ $rating->rater->name }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-300">ocenio
                                    {{ $rating->ratedUser->name }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $rating->created_at->format('d.m.Y') }}
                        </div>
                    </div>

                    <!-- Listing -->
                    @if ($rating->listing)
                        <div class="mb-3">
                            <p class="text-xs text-slate-500 dark:text-slate-300 mb-1">Oglas:</p>
                            <p class="text-sm font-medium text-slate-800">{{ $rating->listing->title }}</p>
                        </div>
                    @endif

                    <!-- Comment -->
                    @if ($rating->comment)
                        <div class="mb-4">
                            <p class="text-xs text-slate-500 dark:text-slate-300 mb-1">Komentar:</p>
                            <p class="text-sm text-slate-700 dark:text-slate-200">"{{ $rating->comment }}"</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <button wire:click="editRating({{ $rating->id }})"
                            class="text-sky-600 hover:text-sky-800 text-sm font-medium">
                            Izmeni
                        </button>
                        <button wire:click="confirmDelete({{ $rating->id }})"
                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Obriši
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-4xl mb-3">😊</div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Nema pronađenih ocena</h3>
                <p class="text-slate-600 dark:text-slate-400">Trenutno nema ocena koje odgovaraju vašoj pretrazi.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($ratings->hasPages())
        <div class="mt-6">
            {{ $ratings->links() }}
        </div>
    @endif

    <!-- Edit Rating Modal -->
    <div x-data="{ open: @entangle('showEditModal') }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                x-show="open" x-cloak x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4">Izmeni ocenu</h3>

                            @if ($selectedRating)
                                <div class="mb-4 p-3 bg-slate-50 rounded">
                                    <p class="text-sm"><strong>Ocenio:</strong> {{ $selectedRating->rater->name }}</p>
                                    <p class="text-sm"><strong>Ocenjen:</strong>
                                        {{ $selectedRating->ratedUser->name }}</p>
                                    @if ($selectedRating->listing)
                                        <p class="text-sm"><strong>Oglas:</strong>
                                            {{ $selectedRating->listing->title }}</p>
                                    @endif
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Tip ocene</label>
                                    <select wire:model="editState.rating"
                                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                        <option value="">Izaberi tip ocene</option>
                                        @foreach ($ratingOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('editState.rating')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Komentar</label>
                                    <textarea wire:model="editState.comment" rows="4"
                                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                        placeholder="Opcioni komentar..."></textarea>
                                    @error('editState.comment')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="updateRating" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Sačuvaj
                    </button>
                    <button wire:click="closeModals" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Otkaži
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Rating Modal -->
    <div x-data="{ open: @entangle('showDeleteModal') }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L2.732 13.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-slate-900">Obriši ocenu</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 dark:text-slate-300">
                                    Da li ste sigurni da želite da obrišete ovu ocenu? Ova akcija se ne može poništiti.
                                </p>

                                @if ($selectedRating)
                                    <div class="mt-3 p-3 bg-red-50 rounded">
                                        <p class="text-sm"><strong>Ocenio:</strong> {{ $selectedRating->rater->name }}
                                        </p>
                                        <p class="text-sm"><strong>Ocenjen:</strong>
                                            {{ $selectedRating->ratedUser->name }}</p>
                                        <p class="text-sm"><strong>Tip:</strong>
                                            {{ ucfirst($selectedRating->rating) }} {{ $selectedRating->rating_icon }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteRating" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Obriši
                    </button>
                    <button wire:click="closeModals" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Otkaži
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
