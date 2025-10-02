<div class="max-w-4xl mx-auto py-6 px-2 md:px-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-2 md:p-6">
        <!-- Profile Completion Check -->
        @if (!auth()->user()->city || !auth()->user()->phone)
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-1">
                            Važno: Molimo vas da prvo popunite svoj profil
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-300 mb-2">
                            Pre postavljanja business-a, morate popuniti sledeća obavezna polja u svom profilu:
                        </p>
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-200 space-y-1 mb-3">
                            @if (!auth()->user()->city)
                                <li>Grad</li>
                            @endif
                            @if (!auth()->user()->phone)
                                <li>Broj telefona</li>
                            @endif
                        </ul>
                        <a href="{{ route('profile') }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Idite na Moj profil
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-6 border-b border-purple-200 dark:border-purple-700 pb-4">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Dodaj novi Business</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Popunite sva polja i dodajte informacije o vašem business-u</p>
            <div class="mt-2 p-3 bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-800 dark:text-purple-200">
                        Vaš trenutni balans: <strong>{{ number_format(auth()->user()->balance, 2) }} RSD</strong>
                        | Cena objave: <strong>
                            {{ \App\Models\Setting::get('business_fee_enabled', true) ? number_format(\App\Models\Setting::get('business_fee_amount', 200), 2) . ' RSD' : 'Besplatno' }}
                        </strong>
                    </span>
                </div>
            </div>
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
                                class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @error('business_category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

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
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('location') border-red-500 @enderror"
                        placeholder="Grad">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="established_year" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Godina osnivanja
                    </label>
                    <input type="number" wire:model="established_year" id="established_year" min="1800" max="{{ date('Y') }}"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('established_year') border-red-500 @enderror"
                        placeholder="{{ date('Y') }}">
                    @error('established_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Kontakt telefon
                    </label>
                    <input type="text" wire:model="contact_phone" id="contact_phone"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_phone') border-red-500 @enderror"
                        placeholder="+381 60 123 4567">
                    @error('contact_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Kontakt email
                    </label>
                    <input type="email" wire:model="contact_email" id="contact_email"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_email') border-red-500 @enderror"
                        placeholder="email@example.com">
                    @error('contact_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Social Links -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Društvene mreže i web</h3>
                
                <div>
                    <label for="website_url" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fas fa-globe mr-1"></i> Website URL
                    </label>
                    <input type="url" wire:model="website_url" id="website_url"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('website_url') border-red-500 @enderror"
                        placeholder="https://www.example.com">
                    @error('website_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fab fa-facebook mr-1"></i> Facebook URL
                    </label>
                    <input type="url" wire:model="facebook_url" id="facebook_url"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('facebook_url') border-red-500 @enderror"
                        placeholder="https://www.facebook.com/...">
                    @error('facebook_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fab fa-instagram mr-1"></i> Instagram URL
                    </label>
                    <input type="url" wire:model="instagram_url" id="instagram_url"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('instagram_url') border-red-500 @enderror"
                        placeholder="https://www.instagram.com/...">
                    @error('instagram_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="youtube_url" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fab fa-youtube mr-1"></i> YouTube URL
                    </label>
                    <input type="url" wire:model="youtube_url" id="youtube_url"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('youtube_url') border-red-500 @enderror"
                        placeholder="https://www.youtube.com/...">
                    @error('youtube_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Logo Upload -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Logo
                </label>
                <input type="file" wire:model="logo" accept="image/*"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg @error('logo') border-red-500 @enderror">
                @error('logo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @if ($logo)
                    <div class="mt-2">
                        <img src="{{ $logo->temporaryUrl() }}" class="h-32 w-32 object-contain border border-slate-300 dark:border-slate-600 rounded">
                    </div>
                @endif
            </div>

            <!-- Images Upload -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Slike (maksimalno {{ \App\Models\Setting::get('max_images_per_business', 10) }})
                </label>
                <input type="file" wire:model="tempImages" multiple accept="image/*"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg @error('images.*') border-red-500 @enderror">
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Image Preview -->
                @if (!empty($images))
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($images as $index => $image)
                            <div class="relative">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg">
                                <button type="button" wire:click="removeImage({{ $index }})"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i> Sačuvaj Business
                </button>
                <a href="{{ route('businesses.index') }}"
                    class="flex-1 bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-200 font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                    <i class="fas fa-times mr-2"></i> Otkaži
                </a>
            </div>
        </form>
    </div>
</div>
