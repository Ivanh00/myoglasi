<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Upravljanje slikama</h1>
                <p class="text-slate-600 dark:text-slate-400">Pregled i upravljanje svim slikama oglasa</p>
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
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupno slika</h3>
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
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Primarne slike</h3>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['primary'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-slate-500 dark:text-slate-300">Ukupna veličina</h3>
                    <p class="text-2xl font-semibold text-slate-900">
                        {{ $stats['totalSize'] > 0 ? number_format($stats['totalSize'] / 1024 / 1024, 2) : '0' }} MB
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filteri i pretraga -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Pretraga -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pretraga po oglasu</label>
                <input type="text" wire:model.live="search" placeholder="Pretraži po naslovu oglasa..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <!-- Oglas -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Filter po oglasu</label>
                <select wire:model.live="filters.listing_id"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Svi oglasi</option>
                    @foreach ($listings as $listing)
                        <option value="{{ $listing->id }}">{{ Str::limit($listing->title, 40) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Primarna slika -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tip slike</label>
                <select wire:model.live="filters.is_primary"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">Sve slike</option>
                    <option value="1">Samo primarne</option>
                    <option value="0">Samo dodatne</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Pronađeno: {{ $images->total() }} slika
            </div>
            <div>
                <button wire:click="resetFilters"
                    class="px-3 py-1 text-sm text-slate-600 border border-slate-300 rounded hover:bg-slate-50">
                    Resetuj filtere
                </button>
            </div>
        </div>
    </div>

    <!-- Grid slika -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if ($images->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 p-4">
                @foreach ($images as $image)
                    <div
                        class="relative group bg-slate-100 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <!-- Slika -->
                        <div class="aspect-w-16 aspect-h-12 bg-slate-200">
                            <img src="{{ Storage::disk('public')->exists($image->image_path) ? Storage::url($image->image_path) : 'https://via.placeholder.com/300x200?text=Slika+obrisana' }}"
                                alt="Slika oglasa" class="w-full h-48 object-cover cursor-pointer"
                                wire:click="viewImage({{ $image->id }})">
                        </div>

                        <!-- Overlay sa informacijama -->
                        <div
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 flex items-end">
                            <div
                                class="p-2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200 w-full">
                                <!-- Naslov oglasa -->
                                <div class="text-sm font-medium mb-1 truncate" title="{{ $image->listing->title }}">
                                    {{ Str::limit($image->listing->title, 30) }}
                                </div>

                                <!-- Status -->
                                <div class="flex items-center justify-between text-xs">
                                    @if ($image->is_primary)
                                        <span class="bg-green-500 text-white px-2 py-1 rounded-full">Primarna</span>
                                    @else
                                        <span class="bg-slate-500 text-white px-2 py-1 rounded-full">Dodatna</span>
                                    @endif

                                    <span class="text-slate-300">{{ $image->created_at->format('d.m.Y.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Akcije -->
                        <div
                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="flex space-x-1">
                                @if (!$image->is_primary)
                                    <button wire:click="setAsPrimary({{ $image->id }})"
                                        class="p-1 bg-green-500 text-white rounded-full hover:bg-green-600"
                                        title="Postavi kao primarnu">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endif

                                <button wire:click="confirmDelete({{ $image->id }})"
                                    class="p-1 bg-red-500 text-white rounded-full hover:bg-red-600"
                                    title="Obriši sliku">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Badge za primarnu sliku -->
                        @if ($image->is_primary)
                            <div class="absolute top-2 left-2">
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Primarna</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <p class="text-slate-500 dark:text-slate-300">Nema pronađenih slika</p>
                <p class="text-slate-400 text-sm mt-1">Pokušajte da promenite filtere ili pretragu</p>
            </div>
        @endif

        <!-- Pagination -->
        @if ($images->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $images->links() }}
            </div>
        @endif
    </div>

    <!-- View Modal -->
    @if ($showViewModal && $selectedImage)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-slate-900">Pregled slike</h3>
                        <button wire:click="$set('showViewModal', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Slika -->
                        <div class="bg-slate-100 rounded-lg p-4 flex items-center justify-center">
                            <img src="{{ Storage::disk('public')->exists($selectedImage->image_path) ? Storage::url($selectedImage->image_path) : 'https://via.placeholder.com/600x400?text=Slika+obrisana' }}"
                                alt="Pregled slike" class="max-h-96 max-w-full object-contain">
                        </div>

                        <!-- Informacije -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Oglas:</h4>
                                <p class="text-lg font-semibold text-slate-900">{{ $selectedImage->listing->title }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Status:</h4>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if ($selectedImage->is_primary) bg-green-100 text-green-800 @else bg-slate-100 text-slate-800 @endif">
                                        @if ($selectedImage->is_primary)
                                            Primarna slika
                                        @else
                                            Dodatna slika
                                        @endif
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Datum:</h4>
                                    <p class="text-sm text-slate-900">
                                        {{ $selectedImage->created_at->format('d.m.Y. H:i') }}</p>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-300">Putanja:</h4>
                                <p class="text-sm text-slate-900 font-mono break-all">{{ $selectedImage->image_path }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-200">
                                <div class="flex space-x-3">
                                    @if (!$selectedImage->is_primary)
                                        <button wire:click="setAsPrimary({{ $selectedImage->id }})"
                                            class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                            Postavi kao primarnu
                                        </button>
                                    @endif

                                    <button wire:click="confirmDelete({{ $selectedImage->id }})"
                                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                        Obriši sliku
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-slate-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>

                    <h3 class="text-lg font-medium text-slate-900 mb-4">Obriši sliku?</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
                        Da li ste sigurni da želite da obrišete ovu sliku?
                        <br>Ova akcija je nepovratna.
                    </p>

                    <div class="bg-amber-50 border border-amber-200 rounded-md p-3 mb-4 text-left">
                        <div class="flex">
                            <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700">
                                    Ovo će trajno obrisati sliku sa servera.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded-md hover:bg-slate-400">
                            Otkaži
                        </button>
                        <button wire:click="deleteImage"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Obriši
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
