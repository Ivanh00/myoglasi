<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Upravljanje kategorijama</h1>
                <p class="text-gray-600">Pregled i upravljanje kategorijama i podkategorijama</p>
            </div>
            <div class="flex space-x-2">
                <div class="relative inline-block text-left">
                    <button id="seeder-menu-button" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <span>Seeder opcije</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="seeder-menu" class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <button wire:click="runCategorySeeder" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 w-full text-left whitespace-nowrap">
                                <i class="fas fa-database mr-2"></i> Učitaj kategorije iz seeder-a
                            </button>
                            <button wire:click="writeToSeeder"
                                onclick="return confirm('Da li ste sigurni? Ovo će prepisati postojeći CategorySeeder.php fajl sa trenutnim kategorijama iz baze.')"
                                class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 w-full text-left whitespace-nowrap">
                                <i class="fas fa-save mr-2"></i> Upiši u seeder
                            </button>
                            <button wire:click="exportCategories" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 w-full text-left whitespace-nowrap">
                                <i class="fas fa-download mr-2"></i> Eksportuj kategorije (JSON)
                            </button>
                        </div>
                    </div>
                </div>
                <button wire:click="createCategory" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    + Nova kategorija
                </button>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('seeder-menu-button');
            const menu = document.getElementById('seeder-menu');
            
            button.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function(event) {
                if (!button.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
        </script>
    </div>

    <!-- Brza statistika -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-sm font-medium text-blue-800">Ukupno kategorija</h3>
            <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Category::whereNull('parent_id')->count() }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-sm font-medium text-green-800">Podkategorije</h3>
            <p class="text-2xl font-bold text-green-600">{{ \App\Models\Category::whereNotNull('parent_id')->count() }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h3 class="text-sm font-medium text-purple-800">Aktivne kategorije</h3>
            <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Category::where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
            <h3 class="text-sm font-medium text-orange-800">Sa oglasima</h3>
            <p class="text-2xl font-bold text-orange-600">{{ \App\Models\Category::has('listings')->count() }}</p>
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

    <!-- Desktop Tabela kategorija -->
    <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
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
                                        <div class="w-8 h-8 bg-blue-100 rounded mr-3 flex items-center justify-center">
                                            <i class="{{ $category->icon }} text-blue-600"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                            <i class="fas fa-folder text-gray-400"></i>
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
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>

                                    @if(!$category->parent_id)
                                        <button wire:click="createSubcategory({{ $category->id }})"
                                            class="text-green-600 hover:text-green-900" title="Dodaj podkategoriju">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    <button wire:click="toggleActive({{ $category->id }})"
                                        class="text-yellow-600 hover:text-yellow-900"
                                        title="{{ $category->is_active ? 'Deaktiviraj' : 'Aktiviraj' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if ($category->is_active)
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

                                    <button wire:click="confirmDelete({{ $category->id }})"
                                        class="text-red-600 hover:text-red-900" title="Obriši">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
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
                                                <div class="w-6 h-6 bg-green-100 rounded mr-3 flex items-center justify-center">
                                                    <i class="{{ $child->icon }} text-green-600 text-xs"></i>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                    <i class="fas fa-folder text-gray-400 text-xs"></i>
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
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
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

    <!-- Mobile Category Cards -->
    <div class="lg:hidden space-y-4" x-data="{ openCategories: {} }">
        @php
            // Get all main categories (not paginated for mobile to show full hierarchy)
            $mainCategories = \App\Models\Category::with(['children' => function($query) {
                $query->orderBy('sort_order', 'asc');
            }])
            ->whereNull('parent_id')
            ->withCount('listings')
            ->when($search, function ($query) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy($sortField, $sortDirection)
            ->get();
        @endphp
        
        @forelse($mainCategories as $category)
            <div class="bg-white shadow rounded-lg p-4">
                <!-- Main Category Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center flex-1">
                        @if($category->icon)
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="{{ $category->icon }} text-blue-600"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-gray-900">{{ $category->name }}</div>
                            <div class="text-sm text-gray-500">Glavna kategorija</div>
                        </div>
                        
                        @if($category->children->count() > 0)
                            <button x-on:click="openCategories[{{ $category->id }}] = !openCategories[{{ $category->id }}]" 
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 transition-colors mr-3">
                                <i class="fas fa-chevron-down text-blue-600 transition-transform" 
                                   x-bind:class="openCategories[{{ $category->id }}] ? 'rotate-180' : ''"></i>
                            </button>
                        @endif
                    </div>
                    
                    <!-- Status -->
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $category->is_active ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                        {{ $category->is_active ? 'Aktivna' : 'Neaktivna' }}
                    </span>
                </div>

                <!-- Main Category Info Grid -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wider">Redosled</div>
                        <div class="text-sm font-medium text-gray-900">{{ $category->sort_order ?? 0 }}</div>
                    </div>
                    
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wider">Ukupno oglasa</div>
                        <div class="text-sm font-medium text-gray-900">{{ $category->listings_count ?? 0 }}</div>
                    </div>
                </div>

                <!-- Main Category Action Buttons -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button wire:click="editCategory({{ $category->id }})" 
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Uredi
                    </button>
                    
                    @if($category->is_active)
                        <button wire:click="deactivateCategory({{ $category->id }})" 
                            class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition-colors">
                            <i class="fas fa-eye-slash mr-1"></i>
                            Deaktiviraj
                        </button>
                    @else
                        <button wire:click="activateCategory({{ $category->id }})" 
                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Aktiviraj
                        </button>
                    @endif
                    
                    <button wire:click="deleteCategory({{ $category->id }})" 
                        wire:confirm="Da li ste sigurni da želite da obrišete ovu kategoriju?"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Obriši
                    </button>
                </div>

                <!-- Subcategories (Collapsible) -->
                @if($category->children->count() > 0)
                    <div x-show="openCategories[{{ $category->id }}]" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100"
                         class="border-t border-gray-200 pt-4 mt-4">
                        <div class="text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-folder-open mr-2 text-green-600"></i>
                            Podkategorije ({{ $category->children->count() }})
                        </div>
                        
                        @foreach($category->children as $subcategory)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            @if($subcategory->icon)
                                                <i class="{{ $subcategory->icon }} text-green-600 text-sm"></i>
                                            @else
                                                <i class="fas fa-tag text-green-600 text-sm"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900">{{ $subcategory->name }}</div>
                                            <div class="text-xs text-gray-500">Redosled: {{ $subcategory->sort_order ?? 0 }}</div>
                                        </div>
                                    </div>
                                    
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                        {{ $subcategory->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subcategory->is_active ? 'Aktivna' : 'Neaktivna' }}
                                    </span>
                                </div>
                                
                                <!-- Subcategory Actions -->
                                <div class="flex flex-wrap gap-2">
                                    <button wire:click="editCategory({{ $subcategory->id }})" 
                                        class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded hover:bg-green-200 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Uredi
                                    </button>
                                    
                                    @if($subcategory->is_active)
                                        <button wire:click="deactivateCategory({{ $subcategory->id }})" 
                                            class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded hover:bg-orange-200 transition-colors">
                                            <i class="fas fa-eye-slash mr-1"></i>
                                            Deaktiviraj
                                        </button>
                                    @else
                                        <button wire:click="activateCategory({{ $subcategory->id }})" 
                                            class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded hover:bg-green-200 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Aktiviraj
                                        </button>
                                    @endif
                                    
                                    <button wire:click="deleteCategory({{ $subcategory->id }})" 
                                        wire:confirm="Da li ste sigurni da želite da obrišete ovu podkategoriju?"
                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>
                                        Obriši
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-tags text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Nema kategorija</h3>
                <p class="text-gray-600">Počnite kreiranjem kategorija za oglase.</p>
            </div>
        @endforelse
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
                                <label class="block text-sm font-medium text-gray-700">Ikona (Font Awesome klasa)</label>
                                <input type="text" wire:model="editState.icon"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="npr. fas fa-car, fas fa-home, fas fa-laptop">
                                @error('editState.icon')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                                @if($editState['icon'])
                                    <div class="mt-2 flex items-center">
                                        <span class="text-sm text-gray-600 mr-2">Pregled:</span>
                                        <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                            <i class="{{ $editState['icon'] }} text-blue-600"></i>
                                        </div>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">Koristite Font Awesome 6 ikone (npr. fas fa-car)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Roditeljska kategorija</label>
                                <select wire:model="editState.parent_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Glavna kategorija</option>
                                    @foreach ($parentCategories as $parentCategory)
                                        @if (!$selectedCategory || $parentCategory->id !== $selectedCategory->id)
                                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
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
    @if ($showDeleteModal && $selectedCategory)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Potvrda brisanja</h3>
                    
                    @if ($selectedCategory)
                        @if ($forceDelete && $selectedCategory->children && $selectedCategory->children->count() > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium">Upozorenje!</p>
                                        <p>Kategorija "{{ $selectedCategory->name }}" ima {{ $selectedCategory->children->count() }} podkategorija.</p>
                                        <p class="mt-1">Brisanje će takođe obrisati sve podkategorije!</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($selectedCategory->listings_count > 0)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-red-800">
                                        <p class="font-medium">Kategorija ima {{ $selectedCategory->listings_count }} oglasa!</p>
                                        <p class="mt-1">Brisanje kategorije će uticati na ove oglase.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <p class="text-sm text-gray-500 mb-4">
                            Da li ste sigurni da želite da obrišete kategoriju "{{ $selectedCategory->name }}"?
                            <br>Ova akcija je nepovratna.
                        </p>
                    @endif

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Otkaži
                        </button>
                        @if ($forceDelete)
                            <button wire:click="deleteCategory(true)"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Obriši kategoriju i sve podkategorije
                            </button>
                        @else
                            <button wire:click="deleteCategory"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Obriši kategoriju
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
