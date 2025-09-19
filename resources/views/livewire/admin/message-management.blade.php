<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Upravljanje porukama</h1>
                <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje svim porukama između korisnika</p>
            </div>
        </div>
    </div>

    <!-- Statistike -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-sky-100 rounded-lg">
                    <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupno poruka</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $totalMessages }}</p>
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
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Pročitane</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $readMessages }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Nepročitane</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $unreadMessages }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filteri i pretraga -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            <!-- Pretraga -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Pretraga</label>
                <input type="text" wire:model.live="search" placeholder="Pretraži poruke..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select wire:model.live="filters.is_read" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Sve poruke</option>
                    <option value="1">Pročitane</option>
                    <option value="0">Nepročitane</option>
                </select>
            </div>

            <!-- Pošiljalac -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pošiljalac</label>
                <select wire:model.live="filters.sender_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi pošiljaoci</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Primalac -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Primalac</label>
                <select wire:model.live="filters.receiver_id"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi primaoci</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Pronađeno: {{ $messages->total() }} poruka
            </div>
            <div class="flex space-x-2">
                <button wire:click="resetFilters"
                    class="px-3 py-1 text-sm text-slate-600 border border-slate-300 rounded hover:bg-slate-50">
                    Resetuj filtere
                </button>
                @if ($readMessages > 0)
                    <button wire:click="deleteAllRead"
                        onclick="return confirm('Da li ste sigurni da želite da obrišete sve pročitane poruke?')"
                        class="px-3 py-1 text-sm text-red-600 border border-red-300 rounded hover:bg-red-50">
                        Obriši pročitane
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Tabela poruka -->
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
                            Poruka</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Pošiljalac</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Primalac</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Oglas
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
                    @forelse($messages as $message)
                        <tr class="hover:bg-slate-50 @if (!$message->is_read) bg-sky-50 @endif">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $message->created_at->format('d.m.Y.') }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">
                                    {{ $message->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">
                                    {{ Str::limit($message->message, 50) }}
                                    @if (strlen($message->message) > 50)
                                        <button wire:click="viewMessage({{ $message->id }})"
                                            class="text-sky-600 dark:text-sky-400 text-xs ml-1">
                                            više...
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($message->sender->avatar)
                                        <img src="{{ $message->sender->avatar_url }}"
                                            alt="{{ $message->sender->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $message->sender->name }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ $message->sender->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($message->receiver->avatar)
                                        <img src="{{ $message->receiver->avatar_url }}"
                                            alt="{{ $message->receiver->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($message->receiver->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $message->receiver->name }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ $message->receiver->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($message->listing)
                                    <div class="text-sm text-slate-900">{{ Str::limit($message->listing->title, 30) }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-300">
                                        {{ number_format($message->listing->price, 2) }} RSD</div>
                                @else
                                    <span class="text-xs text-slate-400">Oglas obrisan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if ($message->is_read) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    @if ($message->is_read)
                                        Pročitano
                                    @else
                                        Nepročitano
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="viewMessage({{ $message->id }})"
                                        class="text-sky-600 hover:text-sky-900" title="Pogledaj poruku">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>

                                    @if ($message->is_read)
                                        <button wire:click="markAsUnread({{ $message->id }})"
                                            class="text-amber-600 hover:text-amber-900"
                                            title="Označi kao nepročitano">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="markAsRead({{ $message->id }})"
                                            class="text-green-600 hover:text-green-900" title="Označi kao pročitano">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif

                                    <button wire:click="confirmDelete({{ $message->id }})"
                                        class="text-red-600 hover:text-red-900" title="Obriši poruku">
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
                            <td colspan="7"
                                class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-300">
                                Nema pronađenih poruka.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $messages->links() }}
        </div>
    </div>

    <!-- Mobile Messages Cards -->
    <div class="lg:hidden space-y-4">
        @forelse($messages as $message)
            <div
                class="bg-white shadow rounded-lg p-4 @if (!$message->is_read) border-l-4 border-sky-500 bg-sky-50 @endif">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        @if ($message->is_read)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>
                                Pročitano
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-exclamation mr-1"></i>
                                Nepročitano
                            </span>
                        @endif
                        <div class="text-xs text-slate-500 dark:text-slate-300">
                            {{ $message->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>

                <!-- Sender and Receiver Info -->
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div class="bg-slate-50 p-3 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                Pošiljalac</div>
                            <div class="flex items-center">
                                @if ($message->sender->avatar)
                                    <img src="{{ $message->sender->avatar_url }}" alt="{{ $message->sender->name }}"
                                        class="w-8 h-8 rounded-full object-cover mr-2">
                                @else
                                    <div
                                        class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-sm font-medium text-slate-900">{{ $message->sender->name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-300">{{ $message->sender->email }}</div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                Primalac</div>
                            <div class="flex items-center">
                                @if ($message->receiver->avatar)
                                    <img src="{{ $message->receiver->avatar_url }}"
                                        alt="{{ $message->receiver->name }}"
                                        class="w-8 h-8 rounded-full object-cover mr-2">
                                @else
                                    <div
                                        class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                        {{ strtoupper(substr($message->receiver->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-sm font-medium text-slate-900">{{ $message->receiver->name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-300">{{ $message->receiver->email }}</div>
                    </div>
                </div>

                <!-- Message Preview -->
                <div class="mb-4">
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Poruka</div>
                    <div class="text-sm text-slate-900">{{ Str::limit($message->message, 100) }}</div>
                    @if (strlen($message->message) > 100)
                        <button wire:click="viewMessage({{ $message->id }})"
                            class="text-sky-600 dark:text-sky-400 text-xs mt-1">
                            Prikaži više...
                        </button>
                    @endif
                </div>

                <!-- Listing Info -->
                @if ($message->listing)
                    <div class="mb-4">
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Oglas</div>
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                            <div class="text-sm font-medium text-slate-900">
                                {{ Str::limit($message->listing->title, 30) }}</div>
                            <div class="text-xs text-green-600 font-semibold">
                                {{ number_format($message->listing->price, 2) }} RSD</div>
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Oglas</div>
                        <div class="text-xs text-slate-400 italic">Oglas obrisan</div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="viewMessage({{ $message->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Prikaži
                    </button>

                    @if ($message->is_read)
                        <button wire:click="markAsUnread({{ $message->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-200 transition-colors">
                            <i class="fas fa-eye-slash mr-1"></i>
                            Nepročitano
                        </button>
                    @else
                        <button wire:click="markAsRead({{ $message->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-check mr-1"></i>
                            Pročitano
                        </button>
                    @endif

                    <button wire:click="confirmDelete({{ $message->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Obriši
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-comments text-slate-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema pronađenih poruka</h3>
                <p class="text-slate-600 dark:text-slate-400">Nema poruka koje odgovaraju kriterijumima pretrage.</p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>

    <!-- View Modal -->
    @if ($showViewModal && $selectedMessage)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Detalji poruke</h3>

                    <div class="bg-slate-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Pošiljalac:</h4>
                                <div class="flex items-center mt-1">
                                    @if ($selectedMessage->sender->avatar)
                                        <img src="{{ $selectedMessage->sender->avatar_url }}"
                                            alt="{{ $selectedMessage->sender->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($selectedMessage->sender->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900">
                                            {{ $selectedMessage->sender->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ $selectedMessage->sender->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Primalac:</h4>
                                <div class="flex items-center mt-1">
                                    @if ($selectedMessage->receiver->avatar)
                                        <img src="{{ $selectedMessage->receiver->avatar_url }}"
                                            alt="{{ $selectedMessage->receiver->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($selectedMessage->receiver->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900">
                                            {{ $selectedMessage->receiver->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ $selectedMessage->receiver->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($selectedMessage->listing)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Oglas:</h4>
                                <p class="text-sm text-slate-900 mt-1">{{ $selectedMessage->listing->title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-300">
                                    {{ number_format($selectedMessage->listing->price, 2) }} RSD</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Datum:</h4>
                            <p class="text-sm text-slate-900 mt-1">
                                {{ $selectedMessage->created_at->format('d.m.Y. H:i') }}
                                ({{ $selectedMessage->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300 mb-2">Poruka:</h4>
                        <div class="bg-white border border-slate-200 rounded-lg p-4">
                            <p class="text-slate-800 whitespace-pre-wrap">{{ $selectedMessage->message }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        @if (!$selectedMessage->is_read)
                            <button wire:click="markAsRead({{ $selectedMessage->id }})"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Označi kao pročitano
                            </button>
                        @else
                            <button wire:click="markAsUnread({{ $selectedMessage->id }})"
                                class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                                Označi kao nepročitano
                            </button>
                        @endif

                        <button wire:click="$set('showViewModal', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Zatvori
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Potvrda brisanja</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
                        Da li ste sigurni da želite da obrišete ovu poruku?
                        <br>Ova akcija je nepovratna.
                    </p>

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Otkaži
                        </button>
                        <button wire:click="deleteMessage"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Obriši
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
