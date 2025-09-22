<div class="max-w-4xl mx-auto py-6 px-2 md:px-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6">
        <!-- Header -->
        <div class="mb-6 border-b border-slate-200 dark:border-slate-600 pb-4">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Uredi uslugu</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Ažurirajte informacije o vašoj usluzi</p>
        </div>

        <!-- Messages -->
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-red-700 dark:text-red-200">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Naslov usluge <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="title" id="title"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('title') border-red-500 @enderror"
                        placeholder="Unesite naslov usluge">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Kategorija usluge <span class="text-red-500">*</span>
                    </label>

                    <!-- Main Category -->
                    <select wire:model.live="service_category_id"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('service_category_id') border-red-500 @enderror">
                        <option value="">Odaberite kategoriju usluge</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('service_category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Subcategory -->
                    @if ($service_category_id && $subcategories && $subcategories->count() > 0)
                        <div class="mt-4">
                            <label for="subcategory_id"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                                Podkategorija
                            </label>
                            <select wire:model="subcategory_id" id="subcategory_id"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('subcategory_id') border-red-500 @enderror">
                                <option value="">Odaberite podkategoriju</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                            @error('subcategory_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif($service_category_id)
                        <div
                            class="mt-4 p-3 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg text-sky-700 dark:text-sky-300 text-sm">
                            <p><strong>Info:</strong> Odabrana kategorija nema dostupne podkategorije.</p>
                        </div>
                    @endif
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Cena usluge (RSD) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" wire:model="price" id="price" step="0.01"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('price') border-red-500 @enderror"
                        placeholder="0.00">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Opis usluge <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="description" id="description" rows="6"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('description') border-red-500 @enderror"
                    placeholder="Detaljno opišite uslugu koju pružate..."></textarea>
                <div class="flex justify-between items-center mt-1">
                    @error('description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @else
                        <p class="text-slate-500 dark:text-slate-300 text-sm">Minimum 10 karaktera</p>
                    @enderror
                    <p class="text-slate-400 text-sm">{{ strlen($description ?? '') }}/2000</p>
                </div>
            </div>

            <!-- Existing Images -->
            @if ($service->images->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Postojeće slike
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($service->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url }}" alt="Service image"
                                    class="w-full h-24 object-cover rounded-lg border">
                                <button type="button" wire:click="removeExistingImage({{ $image->id }})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add New Images -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Dodaj nove slike (maksimalno {{ \App\Models\Setting::get('max_images_per_service', 10) }})
                    @if (!empty($tempImages))
                        <span class="text-sky-600 dark:text-sky-400">({{ count($tempImages) }} novih)</span>
                    @endif
                </label>

                <!-- Upload Area -->
                @php $maxImages = \App\Models\Setting::get('max_images_per_service', 10); @endphp
                <div
                    class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-slate-400 dark:hover:border-slate-500 transition-colors">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    <input type="file" wire:model="tempImages" multiple accept="image/*" class="hidden"
                        id="new-images">
                    <label for="new-images" class="cursor-pointer">
                        <span class="text-sky-600 hover:text-sky-500 font-medium">Kliknite za dodavanje novih
                            slika</span>
                        <span class="text-slate-500 dark:text-slate-300"> ili prevucite ovde</span>
                    </label>
                    <p class="text-slate-400 text-sm mt-2">PNG, JPG, JPEG do 5MB po slici</p>
                </div>

                @error('tempImages.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- New Image Previews -->
                @if (!empty($tempImages))
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($tempImages as $index => $image)
                            <div class="relative group">
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                    class="w-full h-24 object-cover rounded-lg border">
                                <button type="button" wire:click="removeImage({{ $index }})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Lokacija <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="location" id="location"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('location') border-red-500 @enderror">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Phone -->
            <div>
                <label for="contact_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Kontakt telefon
                </label>
                <input type="text" wire:model="contact_phone" id="contact_phone"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <p class="text-slate-500 dark:text-slate-300 text-sm mt-1">
                    Telefon je vidljiv u uslugama samo ako je označen kao vidljiv u profilu.
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-200 dark:border-slate-600">
                <a href="{{ route('services.show', $service) }}"
                    class="px-4 py-2 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Otkaži
                </a>

                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="save">
                        Sačuvaj izmene
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Čuvam...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
