<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Upravljanje korisnicima</h1>
        <p class="text-gray-600">Pregled i upravljanje svim korisnicima sistema</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Pretraži korisnike..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select wire:model.live="perPage" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="10">10 po strani</option>
                    <option value="25">25 po strani</option>
                    <option value="50">50 po strani</option>
                    <option value="100">100 po strani</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            Ime
                            @if ($sortField === 'name')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('email')">
                            Email
                            @if ($sortField === 'email')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Telefon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            Datum
                            @if ($sortField === 'created_at')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-medium text-gray-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->city ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->phone ?? '-' }}</div>
                                @if ($user->phone_visible)
                                    <span class="text-xs text-green-600">Vidljiv</span>
                                @else
                                    <span class="text-xs text-gray-500">Skriven</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->is_admin)
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Da</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">Ne</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->created_at->format('d.m.Y.') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editUser({{ $user->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Izmeni</button>
                                @if (!$user->is_admin)
                                    <button wire:click="deleteUser({{ $user->id }})"
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Da li ste sigurni?')">Obriši</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Izmeni korisnika</h3>

                    <form wire:submit.prevent="updateUser">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ime</label>
                                <input type="text" wire:model="editState.name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="editState.email"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Grad</label>
                                <input type="text" wire:model="editState.city"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefon</label>
                                <input type="text" wire:model="editState.phone"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="editState.phone_visible" class="rounded">
                                    <span class="ml-2 text-sm text-gray-600">Telefon vidljiv</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="editState.is_admin" class="rounded">
                                    <span class="ml-2 text-sm text-gray-600">Administrator</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="editState.is_banned" class="rounded">
                                    <span class="ml-2 text-sm text-gray-600">Blokiran</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Otkaži
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Sačuvaj
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
