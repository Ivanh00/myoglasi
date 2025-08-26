<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Upravljanje kategorijama</h1>
                <p class="text-gray-600">Pregled i upravljanje kategorijama i podkategorijama</p>
            </div>
            <button wire:click="createCategory" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Nova kategorija
            </button>
        </div>
    </div>

    <!-- Filteri i pretraga -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Pretraži kategorije..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex space-x-4">
                <select wire:model.live="perPage" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="15">15 po strani</option>
                    <option value="25">25 po strani</option>
                    <option value="50">50 po strani</option>
                </select>

                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showChildren" class="rounded">
                    <span class="ml-2 text-sm text-gray-600">Prikaži podkategorije</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Tabela kategorija -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            Naziv
                            @if ($sortField === 'name')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Roditelj</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('sort_order')">
                            Redosled
                            @if ($sortField === 'sort_order')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Oglasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 @if ($category->parent_id) bg-gray-50 @endif">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($category->icon)
                                        <img src="{{ $category->icon }}" alt="{{ $category->name }}"
                                            class="w-6 h-6 mr-3">
                                    @else
                                        <div class="w-6 h-6 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $category->name }}
                                            @if ($category->parent_id)
                                                <span class="text-xs text-gray-400 ml-1">(podkategorija)</span>
                                            @endif
                                        </div>
                                        @if ($category->description)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($category->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($category->parent)
                                    <span class="text-sm text-gray-900">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $category->sort_order }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category->listings_count }} oglasa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if ($category->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    @if ($category->is_active)
                                        Aktivan
                                    @else
                                        Neaktivan
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="editCategory({{ $category->id }})"
                                        class="text-blue-600 hover:text-blue-900" title="Izmeni">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>

                                    <button wire:click="toggleActive({{ $category->id }})"
                                        class="text-yellow-600 hover:text-yellow-900"
                                        title="{{ $category->is_active ? 'Deaktiviraj' : 'Aktiviraj' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if ($category->is_active)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                                </path>
                                            @endif
                                        </svg>
                                    </button>

                                    <button wire:click="confirmDelete({{ $category->id }})"
                                        class="text-red-600 hover:text-red-900" title="Obriši">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Brza promena redosleda -->
                                <div class="mt-2 flex space-x-1">
                                    @if (!$category->parent_id)
                                        <button
                                            wire:click="updateSortOrder({{ $category->id }}, {{ $category->sort_order - 1 }})"
                                            class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200"
                                            title="Pomeri gore">
                                            ↑
                                        </button>
                                        <button
                                            wire:click="updateSortOrder({{ $category->id }}, {{ $category->sort_order + 1 }})"
                                            class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200"
                                            title="Pomeri dole">
                                            ↓
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Prikaz podkategorija ako postoje -->
                        @if ($category->children->count() > 0 && !$showChildren)
                            @foreach ($category->children as $child)
                                <tr class="hover:bg-gray-50 bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center ml-8">
                                            @if ($child->icon)
                                                <img src="{{ $child->icon }}" alt="{{ $child->name }}"
                                                    class="w-5 h-5 mr-3">
                                            @else
                                                <div
                                                    class="w-5 h-5 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="text-sm font-medium text-gray-600">{{ $child->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $child->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $category->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $child->sort_order }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $child->listings_count }} oglasa
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if ($child->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                            @if ($child->is_active)
                                                Aktivan
                                            @else
                                                Neaktivan
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button wire:click="editCategory({{ $child->id }})"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Nema pronađenih kategorija.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $selectedCategory ? 'Izmeni kategoriju' : 'Nova kategorija' }}
                    </h3>

                    <form wire:submit.prevent="saveCategory">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Naziv *</label>
                                <input type="text" wire:model="editState.name" wire:blur="generateSlug"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                @error('editState.name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Slug *</label>
                                <input type="text" wire:model="editState.slug"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                @error('editState.slug')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Opis</label>
                                <textarea wire:model="editState.description" rows="3"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                                @error('editState.description')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ikona (URL)</label>
                                <input type="text" wire:model="editState.icon"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                @error('editState.icon')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Roditeljska kategorija</label>
                                <select wire:model="editState.parent_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Glavna kategorija</option>
                                    @foreach ($categories as $category)
                                        @if (!$selectedCategory || $category->id !== $selectedCategory->id)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('editState.parent_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Redosled *</label>
                                <input type="number" wire:model="editState.sort_order" min="0"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                @error('editState.sort_order')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="editState.is_active"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label class="ml-2 text-sm text-gray-600">Aktivna kategorija</label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Otkaži
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ $selectedCategory ? 'Sačuvaj izmene' : 'Kreiraj' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Potvrda brisanja</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Da li ste sigurni da želite da obrišete kategoriju "{{ $selectedCategory->name }}"?
                        <br>Ova akcija je nepovratna.
                    </p>

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Otkaži
                        </button>
                        <button wire:click="deleteCategory"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Obriši
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
