<div class="max-w-4xl mx-auto py-6 px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900">Izmeni oglas</h1>
            <p class="text-gray-600 mt-2">Ažurirajte podatke vašeg oglasa</p>
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-green-700">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-red-700">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="update" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Naslov oglasa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="title" id="title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                        placeholder="Unesite naslov oglasa (npr. iPhone 13 Pro Max 256GB)">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategorija <span class="text-red-500">*</span>
                    </label>

                    <!-- Main Category -->
                    <select wire:model.live="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Odaberite glavnu kategoriju</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Subcategory -->
                    @if ($category_id)
                        @if ($subcategories && $subcategories->count() > 0)
                            <div class="mt-4">
                                <label for="subcategory_id" class="block text-sm font-medium text-gray-700">
                                    Podkategorija
                                </label>
                                <select wire:model="subcategory_id" id="subcategory_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subcategory_id') border-red-500 @enderror">
                                    <option value="">Odaberite podkategoriju</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ $subcategory->id == $subcategory_id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subcategory_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 text-sm">
                                <p><strong>Info:</strong> Odabrana kategorija nema dostupne podkategorije.</p>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Condition -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stanje <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="condition_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('condition_id') border-red-500 @enderror">
                        <option value="">Odaberite stanje</option>
                        @foreach ($conditions as $condition)
                            <option value="{{ $condition->id }}"
                                {{ $condition->id == $condition_id ? 'selected' : '' }}>
                                {{ $condition->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('condition_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    Cena (RSD) <span class="text-red-500">*</span>
                </label>
                <input type="number" wire:model="price" id="price" step="0.01" min="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                    placeholder="0.00">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Opis oglasa <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="description" id="description" rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Detaljno opišite vaš proizvod...">{{ $description }}</textarea>
                <div class="flex justify-between items-center mt-1">
                    @error('description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @else
                        <p class="text-gray-500 text-sm">Minimum 10 karaktera</p>
                    @enderror
                    <p class="text-gray-400 text-sm">{{ strlen($description ?? '') }}/2000</p>
                </div>
            </div>

            <!-- Upload Area -->
            <div
                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                    </path>
                </svg>
                <input type="file" wire:model="newImages" multiple accept="image/*" class="hidden" id="newImages">
                <label for="newImages" class="cursor-pointer">
                    <span class="text-blue-600 hover:text-blue-500 font-medium">Kliknite za dodavanje slika</span>
                    <span class="text-gray-500"> ili prevucite ovde</span>
                </label>
                <p class="text-gray-400 text-sm mt-2">PNG, JPG, JPEG do 5MB po slici</p>
            </div>

            @error('newImages.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- New Image Previews -->
            @if (!empty($newImages))
                <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach ($newImages as $index => $image)
                        <div class="relative group">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                class="w-full h-24 object-cover rounded-lg border">
                            <button type="button" wire:click="removeNewImage({{ $index }})"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                ×
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Existing Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Trenutne slike
                </label>
                @if ($listing->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
                        @foreach ($listing->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url }}" alt="Slika oglasa"
                                    class="w-full h-24 object-cover rounded-lg border">
                                <button type="button" wire:click="removeImage({{ $image->id }})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Nema dodatin slika.</p>
                @endif
            </div>

            <!-- New Images Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Dodaj nove slike (maksimalno 10)
                </label>
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                    Lokacija <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="location" id="location"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                    placeholder="Grad, opština" value="{{ auth()->user()->city }}">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">
                    Automatski preuzeto iz vašeg profila.
                    <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-500">
                        Ažuriraj profil
                    </a>
                </p>
            </div>

            <!-- Contact Phone -->
            <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Kontakt telefon
                </label>
                <input type="text" wire:model="contact_phone" id="contact_phone"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="+381 60 123 4567" value="{{ auth()->user()->phone }}">
                <p class="text-gray-500 text-sm mt-1">
                    Automatski preuzeto iz vašeg profila.
                    <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-500">
                        Ažuriraj profil
                    </a>
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('listings.show', $listing) }}"
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Nazad na oglas
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('listings.my') }}"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Moji oglasi
                    </a>

                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <span wire:loading.remove wire:target="update">Sačuvaj izmene</span>
                        <span wire:loading wire:target="update" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Čuvanje...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
