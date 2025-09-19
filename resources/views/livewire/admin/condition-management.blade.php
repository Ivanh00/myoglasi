<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Upravljanje stanjima oglasa</h1>
                <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje stanjima oglasa (npr. Novo, Polovno, Oštećeno)</p>
            </div>
            <button wire:click="createCondition" class="bg-sky-600 text-white px-4 py-2 rounded-lg hover:bg-sky-700">
                + Novo stanje
            </button>
        </div>
    </div>

    <!-- Filteri i pretraga -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Pretraži stanja..."
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <select wire:model.live="perPage" class="px-4 py-2 border border-slate-300 rounded-lg">
                    <option value="15">15 po strani</option>
                    <option value="25">25 po strani</option>
                    <option value="50">50 po strani</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabela stanja -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            Naziv
                            @if ($sortField === 'name')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Slug
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Broj
                            oglasa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($conditions as $condition)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $condition->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $condition->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                    {{ $condition->listings_count }} oglasa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if ($condition->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    @if ($condition->is_active)
                                        Aktivan
                                    @else
                                        Neaktivan
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="editCondition({{ $condition->id }})"
                                        class="text-sky-600 hover:text-sky-900" title="Izmeni">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>

                                    <button wire:click="toggleActive({{ $condition->id }})"
                                        class="text-amber-600 hover:text-amber-900"
                                        title="{{ $condition->is_active ? 'Deaktiviraj' : 'Aktiviraj' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if ($condition->is_active)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                                </path>
                                            @endif
                                        </svg>
                                    </button>

                                    <button wire:click="confirmDelete({{ $condition->id }})"
                                        class="text-red-600 hover:text-red-900" title="Obriši"
                                        @if ($condition->listings_count > 0) disabled @endif>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-300">
                                Nema pronađenih stanja oglasa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $conditions->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">
                        {{ $selectedCondition ? 'Izmeni stanje oglasa' : 'Novo stanje oglasa' }}
                    </h3>

                    <form wire:submit.prevent="saveCondition">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Naziv *</label>
                                <input type="text" wire:model="editState.name" wire:blur="generateSlug"
                                    class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                @error('editState.name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Slug *</label>
                                <input type="text" wire:model="editState.slug"
                                    class="mt-1 block w-full border border-slate-300 rounded-md px-3 py-2">
                                @error('editState.slug')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="editState.is_active"
                                    class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                <label class="ml-2 text-sm text-slate-600 dark:text-slate-400">Aktivno stanje</label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                                Otkaži
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700">
                                {{ $selectedCondition ? 'Sačuvaj izmene' : 'Kreiraj' }}
                            </button>
                        </div>
                    </form>
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
                        Da li ste sigurni da želite da obrišete stanje "{{ $selectedCondition->name }}"?
                        <br>Ova akcija je nepovratna.
                    </p>

                    @if ($selectedCondition->listings_count > 0)
                        <div class="bg-amber-50 border border-amber-200 rounded-md p-3 mb-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700">
                                        Ovo stanje ima {{ $selectedCondition->listings_count }} oglasa.
                                        Brisanje nije moguće dok postoje povezani oglasi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Otkaži
                        </button>

                        @if ($selectedCondition->listings_count === 0)
                            <button wire:click="deleteCondition"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Obriši
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
