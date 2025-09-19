<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Upravljanje korisnicima</h2>
            <div class="flex space-x-2">
                <button wire:click="resetFilters"
                    class="px-4 py-2 text-slate-600 hover:text-slate-800 border border-slate-300 rounded-lg">
                    Resetuj filtere
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div>
                <input type="text" wire:model.live="search" placeholder="Pretraži korisnike..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select wire:model.live="filterStatus"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="all">Svi korisnici</option>
                    <option value="active">Aktivni</option>
                    <option value="banned">Banovani</option>
                    <option value="admin">Administratori</option>
                    <option value="with_balance">Sa kreditima</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filterVerification"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="all">Svi (verifikacija)</option>
                    <option value="verified">✅ Verifikovani</option>
                    <option value="pending">⏳ Na čekanju</option>
                    <option value="rejected">❌ Odbačeni</option>
                    <option value="unverified">👤 Neverifikovani</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filterPaymentPlan"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="all">Svi načini plaćanja</option>
                    <option value="per_listing">Plaćanje po oglasu</option>
                    <option value="monthly">Mesečni plan</option>
                    <option value="yearly">Godišnji plan</option>
                    <option value="free">Besplatan plan</option>
                    <option value="free_disabled">Isključeno plaćanje</option>
                    <option value="active_plans">Aktivni planovi</option>
                    <option value="expired_plans">Istekli planovi</option>
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

    <!-- Desktop Users Table -->
    <div class="hidden lg:block bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('name')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 uppercase tracking-wider hover:text-slate-700">
                                <span>Korisnik</span>
                                @if ($sortField === 'name')
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
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('balance')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 uppercase tracking-wider hover:text-slate-700">
                                <span>Balans</span>
                                @if ($sortField === 'balance')
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
                            Oglasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Verifikacija</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Način plaćanja</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center space-x-1 text-xs font-medium text-slate-500 uppercase tracking-wider hover:text-slate-700">
                                <span>Registrovan</span>
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
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                        @if ($user->city)
                                            <div class="text-xs text-slate-400">{{ $user->city }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">
                                    {{ number_format($user->balance ?? 0, 0) }} RSD</div>
                                <div class="text-xs text-slate-500">{{ $user->transactions_count }} transakcija</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $user->listings_count }} oglasa</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if ($user->is_banned)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Banovan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktivan
                                        </span>
                                    @endif

                                    @if ($user->is_admin)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    {!! $user->verification_badge !!}
                                    @if ($user->verification_comment)
                                        <div class="text-xs text-slate-500 mt-1"
                                            title="{{ $user->verification_comment }}">
                                            {{ Str::limit($user->verification_comment, 30) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if (!$user->payment_enabled)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Isključeno
                                        </span>
                                    @else
                                        @switch($user->payment_plan)
                                            @case('per_listing')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                                    <i class="fas fa-receipt mr-1"></i>
                                                    Po oglasu
                                                </span>
                                            @break

                                            @case('monthly')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    Mesečni
                                                </span>
                                                @if ($user->plan_expires_at)
                                                    <span class="text-xs text-slate-500">
                                                        {{ $user->plan_expires_at->isPast() ? 'Istekao' : 'Do ' . $user->plan_expires_at->format('d.m.Y') }}
                                                    </span>
                                                @endif
                                            @break

                                            @case('yearly')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    Godišnji
                                                </span>
                                                @if ($user->plan_expires_at)
                                                    <span class="text-xs text-slate-500">
                                                        {{ $user->plan_expires_at->isPast() ? 'Istekao' : 'Do ' . $user->plan_expires_at->format('d.m.Y') }}
                                                    </span>
                                                @endif
                                            @break

                                            @case('free')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    <i class="fas fa-gift mr-1"></i>
                                                    Besplatan
                                                </span>
                                            @break
                                        @endswitch
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $user->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="viewUserDetails({{ $user->id }})"
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

                                    <button wire:click="editUser({{ $user->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded" title="Uredi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>

                                    <button wire:click="adjustBalance({{ $user->id }})"
                                        class="text-green-600 hover:text-green-900 p-1 rounded" title="Podesi balans">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </button>

                                    <button wire:click="editUserPayment({{ $user->id }})"
                                        class="text-sky-600 hover:text-sky-900 p-1 rounded"
                                        title="Podešavanja plaćanja">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                    </button>

                                    <button wire:click="openVerificationModal({{ $user->id }})"
                                        class="text-green-600 hover:text-green-900 p-1 rounded"
                                        title="Upravljaj verifikacijom">
                                        <i class="fas fa-user-check"></i>
                                    </button>

                                    <button wire:click="sendNotificationToUser({{ $user->id }})"
                                        class="text-purple-600 hover:text-purple-900 p-1 rounded"
                                        title="Pošalji obaveštenje">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                    </button>

                                    @if ($user->is_banned)
                                        <button wire:click="unbanUser({{ $user->id }})"
                                            class="text-amber-600 hover:text-amber-900 p-1 rounded" title="Odbaniraj">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="banUser({{ $user->id }})"
                                            class="text-orange-600 hover:text-orange-900 p-1 rounded" title="Baniraj">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if (!$user->is_admin || $user->id === auth()->id())
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Da li ste sigurni da želite da obrišete ovog korisnika? Ova akcija je nepovratna!"
                                            class="text-red-600 hover:text-red-900 p-1 rounded" title="Obriši">
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
                                    Nema korisnika koji odgovaraju kriterijumima pretrage.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Mobile Users Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($users as $user)
                <div class="bg-white shadow rounded-lg p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if ($user->avatar)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $user->avatar_url }}"
                                        alt="{{ $user->name }}">
                                @else
                                    <div
                                        class="h-12 w-12 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-lg font-semibold text-slate-900">{{ $user->name }}</div>
                                <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                @if ($user->city)
                                    <div class="text-xs text-slate-400">{{ $user->city }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Status Badges -->
                        <div class="flex flex-col space-y-1">
                            @if ($user->is_banned)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Banovan
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktivan
                                </span>
                            @endif

                            @if ($user->is_admin)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Admin
                                </span>
                            @endif

                            <!-- Verification Badge -->
                            {!! $user->verification_badge !!}
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Balans</div>
                            <div class="text-sm font-medium text-slate-900">{{ number_format($user->balance ?? 0, 0) }}
                                RSD</div>
                            <div class="text-xs text-slate-500">{{ $user->transactions_count }} transakcija</div>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-lg">
                            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Oglasi</div>
                            <div class="text-sm font-medium text-slate-900">{{ $user->listings_count }} oglasa</div>
                            <div class="text-xs text-slate-500">Ukupno</div>
                        </div>
                    </div>

                    <!-- Payment Plan -->
                    <div class="mb-4">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Način plaćanja</div>
                        @if (!$user->payment_enabled)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <i class="fas fa-times mr-1"></i>
                                Isključeno
                            </span>
                        @else
                            @switch($user->payment_plan)
                                @case('per_listing')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Po oglasu
                                    </span>
                                @break

                                @case('monthly')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Mesečni
                                    </span>
                                    @if ($user->plan_expires_at)
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $user->plan_expires_at->isPast() ? 'Istekao' : 'Do ' . $user->plan_expires_at->format('d.m.Y') }}
                                        </div>
                                    @endif
                                @break

                                @case('yearly')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Godišnji
                                    </span>
                                    @if ($user->plan_expires_at)
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $user->plan_expires_at->isPast() ? 'Istekao' : 'Do ' . $user->plan_expires_at->format('d.m.Y') }}
                                        </div>
                                    @endif
                                @break

                                @case('free')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <i class="fas fa-gift mr-1"></i>
                                        Besplatan
                                    </span>
                                @break
                            @endswitch
                        @endif
                    </div>

                    <!-- Registration Date -->
                    <div class="mb-4">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Registrovan</div>
                        <div class="text-sm text-slate-900">{{ $user->created_at->format('d.m.Y') }}</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="viewUserDetails({{ $user->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Pregled
                        </button>

                        <button wire:click="editUser({{ $user->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>
                            Uredi
                        </button>

                        <button wire:click="adjustBalance({{ $user->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-dollar-sign mr-1"></i>
                            Balans
                        </button>

                        <button wire:click="editUserPayment({{ $user->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-sky-100 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-200 transition-colors">
                            <i class="fas fa-credit-card mr-1"></i>
                            Plaćanje
                        </button>

                        <button wire:click="openVerificationModal({{ $user->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-user-check mr-1"></i>
                            Verifikacija
                        </button>

                        <button wire:click="sendNotificationToUser({{ $user->id }})"
                            class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-200 transition-colors">
                            <i class="fas fa-bell mr-1"></i>
                            Poruka
                        </button>

                        @if ($user->is_banned)
                            <button wire:click="unbanUser({{ $user->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-200 transition-colors">
                                <i class="fas fa-unlock mr-1"></i>
                                Odbaniraj
                            </button>
                        @else
                            <button wire:click="banUser({{ $user->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                                <i class="fas fa-ban mr-1"></i>
                                Baniraj
                            </button>
                        @endif

                        @if (!$user->is_admin || $user->id === auth()->id())
                            <button wire:click="deleteUser({{ $user->id }})"
                                wire:confirm="Da li ste sigurni da želite da obrišete ovog korisnika? Ova akcija je nepovratna!"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-1"></i>
                                Obriši
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <i class="fas fa-users text-slate-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Nema korisnika</h3>
                        <p class="text-slate-600">Nema korisnika koji odgovaraju kriterijumima pretrage.</p>
                    </div>
                @endforelse

                <!-- Mobile Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>

            <!-- Edit User Modal -->
            @if ($showEditModal)
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-slate-900">Uredi korisnika</h3>
                                <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <form wire:submit="updateUser">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Ime</label>
                                        <input type="text" wire:model="editState.name"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md">
                                        @error('editState.name')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Email</label>
                                        <input type="email" wire:model="editState.email"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md">
                                        @error('editState.email')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Grad</label>
                                        <input type="text" wire:model="editState.city"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md">
                                        @error('editState.city')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Telefon</label>
                                        <input type="text" wire:model="editState.phone"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md">
                                        @error('editState.phone')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="editState.phone_visible" class="rounded">
                                            <span class="ml-2 text-sm text-slate-700">Telefon vidljiv</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="editState.is_admin" class="rounded">
                                            <span class="ml-2 text-sm text-slate-700">Administrator</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" wire:click="$set('showEditModal', false)"
                                        class="px-4 py-2 text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50">
                                        Otkaži
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                        Sačuvaj
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ban User Modal -->
            @if ($showBanModal)
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-slate-900">Baniraj korisnika</h3>
                                <button wire:click="$set('showBanModal', false)" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <form wire:submit="confirmBan">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Razlog banovanja</label>
                                    <textarea wire:model="banState.ban_reason" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-md"
                                        placeholder="Unesite razlog banovanja..."></textarea>
                                    @error('banState.ban_reason')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" wire:click="$set('showBanModal', false)"
                                        class="px-4 py-2 text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50">
                                        Otkaži
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        Baniraj
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Balance Adjustment Modal -->
            @if ($showBalanceModal)
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-slate-900">Podesi balans korisnika</h3>
                                <button wire:click="$set('showBalanceModal', false)"
                                    class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            @if ($selectedUser)
                                <p class="text-sm text-slate-600 mb-4">Trenutni balans:
                                    <strong>{{ number_format($selectedUser->balance ?? 0, 0) }} RSD</strong>
                                </p>
                            @endif
                            <form wire:submit="updateBalance">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Iznos (pozitivni za dodavanje,
                                            negativni za oduzimanje)</label>
                                        <input type="number" step="0.01" wire:model="balanceState.amount"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md"
                                            placeholder="npr. 1000 ili -500">
                                        @error('balanceState.amount')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Opis</label>
                                        <input type="text" wire:model="balanceState.description"
                                            class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md"
                                            placeholder="npr. Admin dodavanje sredstava">
                                        @error('balanceState.description')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" wire:click="$set('showBalanceModal', false)"
                                        class="px-4 py-2 text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50">
                                        Otkaži
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                        Ažuriraj balans
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Details Modal -->
            @if ($showUserDetailModal && isset($userDetails['user']))
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-10 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-medium text-slate-900">Detalji korisnika:
                                    {{ $userDetails['user']->name }}</h3>
                                <button wire:click="$set('showUserDetailModal', false)"
                                    class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- User Info Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-sky-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-sky-800">Oglasi</h4>
                                    <p class="text-2xl font-bold text-sky-600">{{ $userDetails['total_listings'] }}</p>
                                    <p class="text-sm text-sky-600">{{ $userDetails['active_listings'] }} aktivni</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-green-800">Balans</h4>
                                    <p class="text-2xl font-bold text-green-600">
                                        {{ number_format($userDetails['user']->balance ?? 0, 0) }}</p>
                                    <p class="text-sm text-green-600">RSD</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-purple-800">Poruke</h4>
                                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ $userDetails['messages_sent'] + $userDetails['messages_received'] }}</p>
                                    <p class="text-sm text-purple-600 dark:text-purple-400">
                                        {{ $userDetails['messages_sent'] }} poslano,
                                        {{ $userDetails['messages_received'] }} primljeno</p>
                                </div>
                                <div class="bg-orange-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-orange-800">Favoriti</h4>
                                    <p class="text-2xl font-bold text-orange-600">{{ $userDetails['favorites_count'] }}</p>
                                    <p class="text-sm text-orange-600">sačuvanih oglasa</p>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Recent Listings -->
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-slate-800 mb-3">Poslednji oglasi</h4>
                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        @foreach ($userDetails['recent_listings'] as $listing)
                                            <div class="bg-white p-3 rounded border">
                                                <p class="font-medium text-sm">{{ $listing->title }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $listing->created_at->format('d.m.Y H:i') }}</p>
                                                <p class="text-xs font-semibold text-green-600">
                                                    {{ number_format($listing->price, 0) }} RSD</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Recent Transactions -->
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-slate-800 mb-3">Poslednje transakcije</h4>
                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        @foreach ($userDetails['recent_transactions'] as $transaction)
                                            <div class="bg-white p-3 rounded border">
                                                <div class="flex justify-between">
                                                    <p class="text-sm">{{ $transaction->description }}</p>
                                                    <p
                                                        class="text-sm font-semibold {{ $transaction->amount >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->amount, 0) }}
                                                        RSD
                                                    </p>
                                                </div>
                                                <p class="text-xs text-slate-500">
                                                    {{ $transaction->created_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="mt-6 bg-slate-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-slate-800 mb-3">Informacije o korisniku</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p><strong>Email:</strong> {{ $userDetails['user']->email }}</p>
                                        <p><strong>Grad:</strong> {{ $userDetails['user']->city ?? 'Nije navedeno' }}</p>
                                        <p><strong>Telefon:</strong> {{ $userDetails['user']->phone ?? 'Nije navedeno' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Registrovan:</strong>
                                            {{ $userDetails['user']->created_at->format('d.m.Y H:i') }}</p>
                                        <p><strong>Status:</strong>
                                            @if ($userDetails['user']->is_banned)
                                                <span class="text-red-600 dark:text-red-400">Banovan</span>
                                                @if ($userDetails['user']->ban_reason)
                                                    <br><small
                                                        class="text-slate-500">{{ $userDetails['user']->ban_reason }}</small>
                                                @endif
                                            @else
                                                <span class="text-green-600">Aktivan</span>
                                            @endif
                                        </p>
                                        @if ($userDetails['user']->is_admin)
                                            <p><strong>Privilage:</strong> <span class="text-purple-600">Administrator</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Modal -->
            @if ($showPaymentModal && $selectedUser)
                <div class="fixed inset-0 bg-slate-500 bg-opacity-75 z-50">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                        <div
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-slate-900">
                                            Podešavanja plaćanja - {{ $selectedUser->name }}
                                        </h3>
                                        <div class="mt-4 space-y-4">
                                            <!-- Current Status -->
                                            <div class="bg-slate-50 p-3 rounded-lg">
                                                <p class="text-sm text-slate-600">
                                                    <strong>Trenutni status:</strong>
                                                    <span class="font-semibold">{{ $selectedUser->plan_status }}</span>
                                                </p>
                                                @if ($selectedUser->plan_expires_at)
                                                    <p class="text-xs text-slate-500 mt-1">
                                                        Ističe: {{ $selectedUser->plan_expires_at->format('d.m.Y H:i') }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Payment Enabled -->
                                            <div class="flex items-center">
                                                <input type="checkbox" id="payment_enabled"
                                                    wire:model="paymentState.payment_enabled"
                                                    class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded">
                                                <label for="payment_enabled" class="ml-2 text-sm text-slate-700">
                                                    Uključi naplaćivanje oglasa za ovog korisnika
                                                </label>
                                            </div>

                                            @if ($paymentState['payment_enabled'])
                                                <!-- Payment Plan -->
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-2">Tip
                                                        plaćanja</label>
                                                    <select wire:model="paymentState.payment_plan"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                                        <option value="per_listing">Plaćanje po oglasu</option>
                                                        <option value="monthly">Mesečni plan</option>
                                                        <option value="yearly">Godišnji plan</option>
                                                        <option value="free">Besplatan plan</option>
                                                    </select>
                                                </div>

                                                <!-- Expiry Date for Plans -->
                                                @if (in_array($paymentState['payment_plan'], ['monthly', 'yearly']))
                                                    <div>
                                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                                            Datum isteka plana (opciono)
                                                        </label>
                                                        <input type="date" wire:model="paymentState.plan_expires_at"
                                                            min="{{ now()->addDay()->format('Y-m-d') }}"
                                                            class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-sky-500 focus:border-sky-500">
                                                        <p class="text-xs text-slate-500 mt-1">
                                                            Ostavi prazno za automatsko izračunavanje
                                                        </p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                                    <p class="text-sm text-amber-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Korisnik može postavljati oglase besplatno (plaćanje isključeno)
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Quick Actions -->
                                            @if ($paymentState['payment_enabled'])
                                                <div class="border-t pt-4">
                                                    <p class="text-sm font-medium text-slate-700 mb-2">Brze akcije:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        <button wire:click="grantMonthlyPlan({{ $selectedUser->id }})"
                                                            class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200">
                                                            Odobri mesečni plan
                                                        </button>
                                                        <button wire:click="grantYearlyPlan({{ $selectedUser->id }})"
                                                            class="text-xs px-2 py-1 bg-sky-100 text-sky-800 rounded hover:bg-sky-200">
                                                            Odobri godišnji plan
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                            @error('paymentState.payment_plan')
                                                <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                                            @enderror
                                            @error('paymentState.plan_expires_at')
                                                <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button wire:click="updateUserPayment" type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    <i class="fas fa-save mr-1"></i>
                                    Sačuvaj
                                </button>
                                <button wire:click="resetModals" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Otkaži
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Verification Modal -->
            @if ($showVerificationModal && $selectedUser)
                <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-slate-900">
                                    <i class="fas fa-shield-check text-green-600 mr-2"></i>
                                    Verifikacija korisnika
                                </h3>
                                <button wire:click="$set('showVerificationModal', false)"
                                    class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- User Info -->
                            <div class="bg-slate-50 p-3 rounded-lg mb-4">
                                <div class="flex items-center">
                                    @if ($selectedUser->avatar)
                                        <img src="{{ $selectedUser->avatar_url }}" alt="{{ $selectedUser->name }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $selectedUser->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $selectedUser->email }}</div>
                                    </div>
                                </div>

                                <!-- Current Status -->
                                <div class="mt-3">
                                    <p class="text-xs text-slate-500">Trenutni status:</p>
                                    <div class="mt-1">{!! $selectedUser->verification_badge !!}</div>
                                    @if ($selectedUser->verification_comment)
                                        <p class="text-xs text-slate-600 mt-2 p-2 bg-slate-100 rounded">
                                            <strong>Poslednji komentar:</strong> {{ $selectedUser->verification_comment }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Comment Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Komentar (razlog verifikacije/odbacivanja)
                                </label>
                                <textarea wire:model="verificationComment" rows="3"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="Napišite razlog verifikacije ili odbacivanja..."></textarea>
                                @error('verificationComment')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-3">
                                <button wire:click="verifyUser('reject')"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-2"></i>
                                    Odbaci verifikaciju
                                </button>

                                <button wire:click="verifyUser('approve')"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <i class="fas fa-check mr-2"></i>
                                    Verifikuj korisnika
                                </button>
                            </div>

                            <!-- Additional Actions -->
                            @if ($selectedUser->verification_status !== 'unverified')
                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <button wire:click="resetVerification({{ $selectedUser->id }})"
                                        onclick="return confirm('Da li ste sigurni da želite da resetujete verifikaciju?')"
                                        class="w-full text-sm text-slate-600 hover:text-slate-800">
                                        <i class="fas fa-undo mr-1"></i>
                                        Resetuj verifikaciju na početno stanje
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Include Notification Management Component for Modal -->
            @livewire('admin.notification-management', ['embedded' => true])

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
