<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Upravljanje korisnicima</h2>
            <div class="flex space-x-2">
                <button wire:click="resetFilters" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg">
                    Resetuj filtere
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <input type="text" wire:model.live="search" placeholder="Pretraži korisnike..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select wire:model.live="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Svi korisnici</option>
                    <option value="active">Aktivni</option>
                    <option value="banned">Banovani</option>
                    <option value="admin">Administratori</option>
                    <option value="with_balance">Sa kreditima</option>
                </select>
            </div>
            <div>
                <select wire:model.live="perPage" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10 po stranici</option>
                    <option value="15">15 po stranici</option>
                    <option value="25">25 po stranici</option>
                    <option value="50">50 po stranici</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('name')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Korisnik</span>
                                @if($sortField === 'name')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('balance')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Balans</span>
                                @if($sortField === 'balance')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oglasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Registrovan</span>
                                @if($sortField === 'created_at')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        @if($user->city)
                                            <div class="text-xs text-gray-400">{{ $user->city }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($user->balance ?? 0, 0) }} RSD</div>
                                <div class="text-xs text-gray-500">{{ $user->transactions_count }} transakcija</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->listings_count }} oglasa</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($user->is_banned)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Banovan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktivan
                                        </span>
                                    @endif
                                    
                                    @if($user->is_admin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="viewUserDetails({{ $user->id }})" 
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Detalji">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button wire:click="editUser({{ $user->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded" title="Uredi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button wire:click="adjustBalance({{ $user->id }})" 
                                            class="text-green-600 hover:text-green-900 p-1 rounded" title="Podesi balans">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </button>

                                    <a href="{{ route('admin.notifications.index', ['user_id' => $user->id]) }}" 
                                       class="text-purple-600 hover:text-purple-900 p-1 rounded" title="Pošalji obaveštenje">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </a>
                                    
                                    @if($user->is_banned)
                                        <button wire:click="unbanUser({{ $user->id }})" 
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded" title="Odbaniraj">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="banUser({{ $user->id }})" 
                                                class="text-orange-600 hover:text-orange-900 p-1 rounded" title="Baniraj">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    @if(!$user->is_admin || $user->id === auth()->id())
                                        <button wire:click="deleteUser({{ $user->id }})" 
                                                wire:confirm="Da li ste sigurni da želite da obrišete ovog korisnika? Ova akcija je nepovratna!"
                                                class="text-red-600 hover:text-red-900 p-1 rounded" title="Obriši">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nema korisnika koji odgovaraju kriterijumima pretrage.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Edit User Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Uredi korisnika</h3>
                        <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="updateUser">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ime</label>
                                <input type="text" wire:model="editState.name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('editState.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="editState.email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('editState.email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Grad</label>
                                <input type="text" wire:model="editState.city" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('editState.city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefon</label>
                                <input type="text" wire:model="editState.phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('editState.phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="editState.phone_visible" class="rounded">
                                    <span class="ml-2 text-sm text-gray-700">Telefon vidljiv</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="editState.is_admin" class="rounded">
                                    <span class="ml-2 text-sm text-gray-700">Administrator</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 mt-6">
                            <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Otkaži
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
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
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Baniraj korisnika</h3>
                        <button wire:click="$set('showBanModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="confirmBan">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Razlog banovanja</label>
                            <textarea wire:model="banState.ban_reason" rows="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="Unesite razlog banovanja..."></textarea>
                            @error('banState.ban_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="$set('showBanModal', false)" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Otkaži
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
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
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Podesi balans korisnika</h3>
                        <button wire:click="$set('showBalanceModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @if($selectedUser)
                        <p class="text-sm text-gray-600 mb-4">Trenutni balans: <strong>{{ number_format($selectedUser->balance ?? 0, 0) }} RSD</strong></p>
                    @endif
                    <form wire:submit="updateBalance">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Iznos (pozitivni za dodavanje, negativni za oduzimanje)</label>
                                <input type="number" step="0.01" wire:model="balanceState.amount" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="npr. 1000 ili -500">
                                @error('balanceState.amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Opis</label>
                                <input type="text" wire:model="balanceState.description" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="npr. Admin dodavanje sredstava">
                                @error('balanceState.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 mt-6">
                            <button type="button" wire:click="$set('showBalanceModal', false)" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Otkaži
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
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
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-medium text-gray-900">Detalji korisnika: {{ $userDetails['user']->name }}</h3>
                        <button wire:click="$set('showUserDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- User Info Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Oglasi</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ $userDetails['total_listings'] }}</p>
                            <p class="text-sm text-blue-600">{{ $userDetails['active_listings'] }} aktivni</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Balans</h4>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($userDetails['user']->balance ?? 0, 0) }}</p>
                            <p class="text-sm text-green-600">RSD</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800">Poruke</h4>
                            <p class="text-2xl font-bold text-purple-600">{{ $userDetails['messages_sent'] + $userDetails['messages_received'] }}</p>
                            <p class="text-sm text-purple-600">{{ $userDetails['messages_sent'] }} poslano, {{ $userDetails['messages_received'] }} primljeno</p>
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
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3">Poslednji oglasi</h4>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                @foreach($userDetails['recent_listings'] as $listing)
                                    <div class="bg-white p-3 rounded border">
                                        <p class="font-medium text-sm">{{ $listing->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $listing->created_at->format('d.m.Y H:i') }}</p>
                                        <p class="text-xs font-semibold text-green-600">{{ number_format($listing->price, 0) }} RSD</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3">Poslednje transakcije</h4>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                @foreach($userDetails['recent_transactions'] as $transaction)
                                    <div class="bg-white p-3 rounded border">
                                        <div class="flex justify-between">
                                            <p class="text-sm">{{ $transaction->description }}</p>
                                            <p class="text-sm font-semibold {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->amount, 0) }} RSD
                                            </p>
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3">Informacije o korisniku</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p><strong>Email:</strong> {{ $userDetails['user']->email }}</p>
                                <p><strong>Grad:</strong> {{ $userDetails['user']->city ?? 'Nije navedeno' }}</p>
                                <p><strong>Telefon:</strong> {{ $userDetails['user']->phone ?? 'Nije navedeno' }}</p>
                            </div>
                            <div>
                                <p><strong>Registrovan:</strong> {{ $userDetails['user']->created_at->format('d.m.Y H:i') }}</p>
                                <p><strong>Status:</strong> 
                                    @if($userDetails['user']->is_banned)
                                        <span class="text-red-600">Banovan</span>
                                        @if($userDetails['user']->ban_reason)
                                            <br><small class="text-gray-500">{{ $userDetails['user']->ban_reason }}</small>
                                        @endif
                                    @else
                                        <span class="text-green-600">Aktivan</span>
                                    @endif
                                </p>
                                @if($userDetails['user']->is_admin)
                                    <p><strong>Privilage:</strong> <span class="text-purple-600">Administrator</span></p>
                                @endif
                            </div>
                        </div>
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
