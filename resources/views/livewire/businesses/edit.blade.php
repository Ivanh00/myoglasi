<div class="max-w-4xl mx-auto py-6 px-2 md:px-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6">
        <!-- Header -->
        <div class="mb-6 border-b border-purple-200 dark:border-purple-700 pb-4">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Izmeni Business</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Ažurirajte informacije o vašem business-u</p>
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

        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-green-700 dark:text-green-200">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="update" class="space-y-6">
            <!-- Business Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Naziv Business-a <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="name" id="name"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                    placeholder="Unesite naziv business-a">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slogan -->
            <div>
                <label for="slogan" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Slogan
                </label>
                <input type="text" wire:model="slogan" id="slogan"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('slogan') border-red-500 @enderror"
                    placeholder="Kratak slogan vašeg business-a">
                @error('slogan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Kategorija <span class="text-red-500">*</span>
                </label>
                <div x-data="{ open: false }" x-init="open = false" class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full px-3 py-2 bg-white dark:bg-slate-700 border @error('business_category_id') border-red-500 @else border-slate-300 dark:border-slate-600 @enderror rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-purple-500 dark:focus:border-purple-400 transition-colors flex items-center justify-between">
                        <span>
                            @if ($business_category_id)
                                {{ $categories->firstWhere('id', $business_category_id)->name ?? 'Odaberite kategoriju' }}
                            @else
                                Odaberite kategoriju
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        @foreach ($categories as $category)
                            <button @click="$wire.set('business_category_id', '{{ $category->id }}'); open = false"
                                type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 {{ $business_category_id == $category->id ? 'bg-purple-100 dark:bg-purple-900/30' : '' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @error('business_category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Subcategory Selection -->
            @if ($subcategories->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Podkategorija
                    </label>
                    <div x-data="{ open: false }" x-init="open = false" class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-slate-700 dark:text-slate-200 text-left hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:border-purple-500 dark:focus:border-purple-400 transition-colors flex items-center justify-between">
                            <span>
                                @if ($subcategory_id)
                                    {{ $subcategories->firstWhere('id', $subcategory_id)->name ?? 'Odaberite podkategoriju' }}
                                @else
                                    Odaberite podkategoriju (opciono)
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <button @click="$wire.set('subcategory_id', null); open = false" type="button"
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                Bez podkategorije
                            </button>
                            @foreach ($subcategories as $subcategory)
                                <button @click="$wire.set('subcategory_id', '{{ $subcategory->id }}'); open = false"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 {{ $subcategory_id == $subcategory->id ? 'bg-purple-100 dark:bg-purple-900/30' : '' }}">
                                    {{ $subcategory->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Opis <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="description" id="description" rows="6"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror"
                    placeholder="Detaljno opišite vaš business"></textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location and Established Year -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Lokacija <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="location" id="location"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="established_year"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Godina osnivanja
                    </label>
                    <input type="number" wire:model="established_year" id="established_year" min="1800"
                        max="{{ date('Y') }}"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('established_year') border-red-500 @enderror"
                        placeholder="{{ date('Y') }}">
                    @error('established_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Info -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Kontakt informacije</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_phone"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Kontakt telefon
                        </label>
                        <input type="text" wire:model="contact_phone" id="contact_phone"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_phone') border-red-500 @enderror">
                        @error('contact_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Kontakt email
                        </label>
                        <input type="email" wire:model="contact_email" id="contact_email"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_email') border-red-500 @enderror">
                        @error('contact_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Contacts -->
                <div class="border-t border-slate-200 dark:border-slate-600 pt-4 space-y-4">
                    <h4 class="text-md font-medium text-slate-900 dark:text-slate-100">Dodatni kontakti</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_name_2"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Ime za kontakt 2
                            </label>
                            <input type="text" wire:model="contact_name_2" id="contact_name_2"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_name_2') border-red-500 @enderror"
                                placeholder="Ime osobe">
                            @error('contact_name_2')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone_2"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Telefon 2
                            </label>
                            <input type="text" wire:model="contact_phone_2" id="contact_phone_2"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_phone_2') border-red-500 @enderror"
                                placeholder="+381 60 123 4567">
                            @error('contact_phone_2')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_name_3"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Ime za kontakt 3
                            </label>
                            <input type="text" wire:model="contact_name_3" id="contact_name_3"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_name_3') border-red-500 @enderror"
                                placeholder="Ime osobe">
                            @error('contact_name_3')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone_3"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Telefon 3
                            </label>
                            <input type="text" wire:model="contact_phone_3" id="contact_phone_3"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_phone_3') border-red-500 @enderror"
                                placeholder="+381 60 123 4567">
                            @error('contact_phone_3')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Društvene mreže</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="website_url"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Website URL
                        </label>
                        <input type="url" wire:model="website_url" id="website_url"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('website_url') border-red-500 @enderror"
                            placeholder="https://example.com">
                        @error('website_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="facebook_url"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Facebook URL
                        </label>
                        <input type="url" wire:model="facebook_url" id="facebook_url"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('facebook_url') border-red-500 @enderror"
                            placeholder="https://facebook.com/yourpage">
                        @error('facebook_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instagram_url"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Instagram URL
                        </label>
                        <input type="url" wire:model="instagram_url" id="instagram_url"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('instagram_url') border-red-500 @enderror"
                            placeholder="https://instagram.com/yourpage">
                        @error('instagram_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="youtube_url"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            YouTube URL
                        </label>
                        <input type="url" wire:model="youtube_url" id="youtube_url"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('youtube_url') border-red-500 @enderror"
                            placeholder="https://youtube.com/c/yourchannel">
                        @error('youtube_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Logo Upload -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Logo
                </label>

                @if ($existing_logo)
                    <div class="mb-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Trenutni logo:</p>
                        <div class="relative inline-block">
                            <img src="{{ Storage::url($existing_logo) }}" alt="Logo"
                                class="h-32 w-auto object-contain rounded border border-slate-200 dark:border-slate-600">
                            <button type="button" wire:click="removeLogo"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <input type="file" wire:model="logo" id="logo" accept="image/*"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                @error('logo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($logo)
                    <div class="mt-2">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Novi logo:</p>
                        <img src="{{ $logo->temporaryUrl() }}" alt="Preview"
                            class="h-32 w-auto object-contain rounded border border-slate-200 dark:border-slate-600">
                    </div>
                @endif
            </div>

            <!-- Images Upload -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Slike (maksimalno {{ \App\Models\Setting::get('max_images_per_business', 10) }})
                </label>

                <!-- Existing Images -->
                @if ($existing_images->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Trenutne slike:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($existing_images as $existingImage)
                                <div class="relative">
                                    <img src="{{ Storage::url($existingImage->path) }}" alt="Business image"
                                        class="w-full h-32 object-cover rounded border border-slate-200 dark:border-slate-600">
                                    <button type="button" wire:click="removeExistingImage({{ $existingImage->id }})"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6">
                    <input type="file" wire:model="tempImages" id="tempImages" accept="image/*" multiple
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        Trenutno: {{ count($existing_images) + count($images) }}/{{ \App\Models\Setting::get('max_images_per_business', 10) }}
                        slika
                    </p>

                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Images Preview -->
                @if (count($images) > 0)
                    <div class="mt-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Nove slike:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($images as $index => $image)
                                <div class="relative">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="w-full h-32 object-cover rounded border border-slate-200 dark:border-slate-600">
                                    <button type="button" wire:click="removeImage({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-600">
                <a href="{{ route('businesses.show', $business->slug) }}"
                    class="px-6 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Otkaži
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Sačuvaj izmene
                </button>
            </div>
        </form>
    </div>
</div>
