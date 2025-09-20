<div class="max-w-4xl mx-auto py-6 px-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
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
                            Pre postavljanja oglasa, morate popuniti sledeća obavezna polja u svom profilu:
                        </p>
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1 mb-3">
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
        <div class="mb-6 border-b border-slate-200 dark:border-slate-600 pb-4">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                @if (request('type') === 'auction')
                    Dodaj novu aukciju
                @elseif(request('type') === 'giveaway')
                    Dodaj novi poklon
                @else
                    Dodaj novi oglas
                @endif
            </h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">
                @if (request('type') === 'auction')
                    Popunite sva polja i dodajte slike proizvoda za aukciju
                @elseif(request('type') === 'giveaway')
                    Popunite sva polja i dodajte slike vašeg poklona
                @else
                    Popunite sva polja i dodajte slike vašeg proizvoda
                @endif
            </p>
            <div class="mt-2 p-3 bg-amber-50 dark:bg-amber-900 border border-amber-200 dark:border-amber-700 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-2" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium text-amber-800 dark:text-amber-200">
                        Vaš trenutni balans: <strong>{{ number_format(auth()->user()->balance, 2) }} RSD</strong>
                        | Cena objave: <strong>
                            @if ($listingType === 'giveaway')
                                Besplatno
                            @else
                                {{ \App\Models\Setting::get('listing_fee_enabled') ? number_format(\App\Models\Setting::get('listing_fee_amount', 10), 2) . ' RSD' : 'Besplatno' }}
                            @endif
                        </strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- Messages -->
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

        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Listing Type Selector -->
            <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg border border-slate-200 dark:border-slate-600">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-3">
                    Tip objave <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all {{ $listingType === 'listing' ? 'border-sky-500 bg-sky-50 dark:bg-sky-900' : 'border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500' }}">
                        <input type="radio" wire:model.live="listingType" value="listing"
                            class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300">
                        <div class="ml-3">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-tag text-sky-600 dark:text-sky-400 mr-2"></i>
                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">Oglas</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Prodaja proizvoda
                                ({{ \App\Models\Setting::get('listing_fee_enabled') ? \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD' : 'Besplatno' }})
                            </p>
                        </div>
                    </label>

                    <label
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all {{ $listingType === 'auction' ? 'border-amber-500 bg-amber-50 dark:bg-amber-900' : 'border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500' }}">
                        <input type="radio" wire:model.live="listingType" value="auction"
                            class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300">
                        <div class="ml-3">
                            <div class="flex items-center">
                                <i class="fas fa-gavel text-amber-600 dark:text-amber-400 mr-2"></i>
                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">Aukcija</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Licitiranje
                                ({{ \App\Models\Setting::get('listing_fee_enabled') ? \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD' : 'Besplatno' }})
                            </p>
                        </div>
                    </label>

                    <label
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all {{ $listingType === 'giveaway' ? 'border-green-500 bg-green-50 dark:bg-green-900' : 'border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500' }}">
                        <input type="radio" wire:model.live="listingType" value="giveaway"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300">
                        <div class="ml-3">
                            <div class="flex items-center">
                                <i class="fas fa-gift text-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">Poklon</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Besplatno davanje (Besplatno)</p>
                        </div>
                    </label>
                </div>

                <!-- Info box for services -->
                <div class="mt-4 p-3 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-sky-600 dark:text-sky-400 mr-2 mt-0.5"></i>
                        <div class="text-sm">
                            <p class="text-sky-800 dark:text-sky-200 font-medium">Želite da ponudite uslugu?</p>
                            <p class="text-sky-700 dark:text-sky-300 mt-1">
                                Usluge imaju svoju dedikovanoj stranicu.
                                <a href="{{ route('services.create') }}"
                                    class="underline hover:text-sky-900 dark:hover:text-sky-100">
                                    Kliknite ovde da dodate uslugu
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                @error('listingType')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Main Content Section -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Osnovne informacije</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                @if ($listingType === 'auction')
                                    Naslov aukcije <span class="text-red-500">*</span>
                                @elseif($listingType === 'giveaway')
                                    Naslov poklona <span class="text-red-500">*</span>
                                @else
                                    Naslov oglasa <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <input type="text" wire:model="title" id="title"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('title') border-red-500 @enderror"
                                placeholder="@if ($listingType === 'auction') Unesite naslov aukcije (npr. iPhone 13 Pro Max 256GB)@elseif($listingType === 'giveaway')Unesite naslov poklona@elseUnesite naslov oglasa (npr. iPhone 13 Pro Max 256GB) @endif">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category Selection -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Kategorija <span class="text-red-500">*</span>
                            </label>

                            <!-- Main Category -->
                            <select wire:model.live="category_id"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Odaberite glavnu kategoriju</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Subcategory (for listings and auctions) -->
                            @if ($category_id && in_array($listingType, ['listing', 'auction']))
                                @if ($subcategories && $subcategories->count() > 0)
                                    <div class="mt-4">
                                        <label for="subcategory_id"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                                            Podkategorija
                                        </label>
                                        <select wire:model="subcategory_id" id="subcategory_id"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('subcategory_id') border-red-500 @enderror">
                                            <option value="">Odaberite podkategoriju</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subcategory_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <div
                                        class="mt-4 p-3 bg-sky-50 dark:bg-sky-900 border border-sky-200 dark:border-sky-700 rounded-lg text-sky-700 dark:text-sky-300 text-sm">
                                        <p><strong>Info:</strong> Odabrana kategorija nema dostupne podkategorije.</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- <!-- DEBUG PANEL -->
                <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm">
                    <h4 class="font-semibold mb-2">Debug Info:</h4>
                    <p><strong>Category ID:</strong> {{ $category_id ?? 'null' }}</p>
                    <p><strong>Subcategory ID:</strong> {{ $subcategory_id ?? 'null' }}</p>
                    <p><strong>Subcategories count:</strong> {{ $subcategories ? $subcategories->count() : 0 }}</p>

                    @if ($category_id)
                        @php
                            $selectedCat = $categories->firstWhere('id', $category_id);
                        @endphp
                        <p><strong>Selected category:</strong>
                            {{ $selectedCat ? $selectedCat->name : 'Not found' }}</p>
                    @endif

                    @if ($subcategories && $subcategories->count() > 0)
                        <p><strong>Available subcategories:</strong></p>
                        <ul class="ml-4 list-disc">
                            @foreach ($subcategories as $sub)
                                <li>{{ $sub->name }} (ID: {{ $sub->id }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div> --}}
                    </div>

                    <!-- Condition (only for regular listings and auctions) -->
                    @if (in_array($listingType, ['listing', 'auction']))
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Stanje <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="condition_id"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('condition_id') border-red-500 @enderror">
                                <option value="">Odaberite stanje</option>
                                @foreach ($conditions as $condition)
                                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                                @endforeach
                            </select>
                            @error('condition_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Price (only for regular listings) -->
                    @if ($listingType === 'listing')
                        <div>
                            <label for="price"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Cena (RSD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="price" id="price" step="0.01"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('price') border-red-500 @enderror"
                                placeholder="0.00">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
            <!-- Auction Settings (only for auctions) -->
            @if ($listingType === 'auction')
                <div
                    class="bg-amber-50 dark:bg-amber-900 p-4 rounded-lg border border-amber-200 dark:border-amber-700 mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                        <i class="fas fa-gavel text-amber-600 dark:text-amber-400 mr-2"></i>
                        Podešavanja aukcije
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Starting Price -->
                        <div>
                            <label for="startingPrice"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Početna cena (RSD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="startingPrice" id="startingPrice" step="0.01"
                                min="1"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('startingPrice') border-red-500 @enderror"
                                placeholder="1.00">
                            @error('startingPrice')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buy Now Price -->
                        <div>
                            <label for="buyNowPrice"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Kupi odmah cena (RSD) <span
                                    class="text-slate-500 dark:text-slate-300 text-xs">(opciono)</span>
                            </label>
                            <input type="number" wire:model="buyNowPrice" id="buyNowPrice" step="0.01"
                                min="1"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('buyNowPrice') border-red-500 @enderror"
                                placeholder="Opciono - mora biti veća od početne cene">
                            @error('buyNowPrice')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Trajanje aukcije <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="duration" id="duration"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('duration') border-red-500 @enderror">
                                <option value="1">1 dan</option>
                                <option value="3">3 dana</option>
                                <option value="5">5 dana</option>
                                <option value="7" selected>7 dana</option>
                                <option value="10">10 dana</option>
                            </select>
                            @error('duration')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Type -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Početak aukcije <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="startType" value="immediately"
                                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700 dark:text-slate-200">Odmah posle
                                        objave</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="startType" value="scheduled"
                                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700 dark:text-slate-200">Zakazano vreme</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduled Start Time (only if scheduled) -->
                    @if ($startType === 'scheduled')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="startDate"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                    Datum početka <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="startDate" id="startDate"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('startDate') border-red-500 @enderror">
                                @error('startDate')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="startTime"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                    Vreme početka <span class="text-red-500">*</span>
                                </label>
                                <input type="time" wire:model="startTime" id="startTime"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('startTime') border-red-500 @enderror">
                                @error('startTime')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Auction Info -->
                    <div
                        class="mt-4 p-3 bg-amber-100 dark:bg-amber-800 border border-amber-300 dark:border-amber-600 rounded-lg">
                        <div class="text-sm text-amber-800 dark:text-amber-200">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 mr-2 mt-0.5"></i>
                                <div>
                                    <p class="font-medium">Kako funkcionišu aukcije:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1 text-xs">
                                        <li>Aukcija počinje sa vašom početnom cenom</li>
                                        <li>Korisnici licitiraju u koracima od
                                            {{ number_format(\App\Models\Setting::get('auction_bid_increment', 10), 0) }}
                                            RSD</li>
                                        <li>Aukcija se automatski produžava ako se licitira u poslednje
                                            {{ \App\Models\Setting::get('auction_extension_trigger_time', 3) }} minuta
                                        </li>
                                        <li>Ako postavite "Kupi odmah" cenu, kupci mogu odmah da kupe po toj ceni</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    @if ($listingType === 'giveaway')
                        Opis poklona <span class="text-red-500">*</span>
                    @elseif($listingType === 'auction')
                        Opis proizvoda za aukciju <span class="text-red-500">*</span>
                    @else
                        Opis oglasa <span class="text-red-500">*</span>
                    @endif
                </label>
                <textarea wire:model="description" id="description" rows="6"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('description') border-red-500 @enderror"
                    placeholder="@if ($listingType === 'giveaway') Detaljno opišite šta poklanjate...@elseif($listingType === 'auction')Detaljno opišite proizvod koji prodajete na aukciji...@elseDetaljno opišite vaš proizvod... @endif"></textarea>
                <div class="flex justify-between items-center mt-1">
                    @error('description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @else
                        <p class="text-slate-500 dark:text-slate-300 text-sm">Minimum 10 karaktera</p>
                    @enderror
                    <p class="text-slate-400 text-sm">{{ strlen($description ?? '') }}/2000</p>
                </div>
            </div>

            <!-- Images Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                    Slike (maksimalno {{ \App\Models\Setting::get('max_images_per_listing', 10) }})
                    @if (!empty($images))
                        <span
                            class="text-sky-600 dark:text-sky-400">({{ count($images) }}/{{ \App\Models\Setting::get('max_images_per_listing', 10) }})</span>
                    @endif
                </label>

                <!-- Upload Area -->
                @php $maxImages = \App\Models\Setting::get('max_images_per_listing', 10); @endphp
                @if (count($images ?? []) < $maxImages)
                    <div
                        class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-slate-400 transition-colors">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <input type="file" wire:model="tempImages" multiple accept="image/*" class="hidden"
                            id="images">
                        <label for="images" class="cursor-pointer">
                            <span class="text-sky-600 hover:text-sky-500 font-medium">Kliknite za dodavanje
                                slika</span>
                            <span class="text-slate-500 dark:text-slate-300"> ili prevucite ovde</span>
                        </label>
                        <p class="text-slate-400 text-sm mt-2">PNG, JPG, JPEG do 5MB po slici</p>
                    </div>
                @else
                    <div class="border-2 border-slate-300 rounded-lg p-6 text-center bg-slate-50">
                        <i class="fas fa-images text-slate-400 text-4xl mb-2"></i>
                        <p class="text-slate-600 dark:text-slate-400 font-medium">Dostigli ste maksimum od
                            {{ $maxImages }} slika</p>
                        <p class="text-slate-500 dark:text-slate-300 text-sm">Obrišite neku sliku da biste dodali novu
                        </p>
                    </div>
                @endif

                @error('images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Image Previews -->
                @if (!empty($images))
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($images as $index => $image)
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

                <!-- Contact Information -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Kontakt informacije</h3>

                    <!-- Location -->
                    <div class="mb-4">
                        <label for="location"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Lokacija <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="location" id="location" readonly
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-100 dark:bg-slate-600 text-slate-900 dark:text-slate-100 cursor-not-allowed @error('location') border-red-500 @enderror"
                            value="{{ auth()->user()->city }}">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="contact_phone"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Kontakt telefon
                        </label>
                        <input type="text" wire:model="contact_phone" id="contact_phone" readonly
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-100 dark:bg-slate-600 text-slate-900 dark:text-slate-100 cursor-not-allowed">
                        <p class="text-slate-500 dark:text-slate-300 text-sm mt-1">
                            Lokacija i broj telefona su automatski preuzeti iz vašeg profila.
                            <a href="{{ route('profile') }}"
                                class="text-sky-600 dark:text-sky-400 hover:text-sky-500">
                                Ažuriraj profil
                            </a>
                        </p>
                        <p class="text-slate-500 dark:text-slate-300 text-sm mt-1">
                            Telefon je vidljiv u oglasima samo ako je označen kao vidljiv u profilu.
                        </p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-200 dark:border-slate-600">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Otkaži
                    </a>

                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <span wire:loading.remove wire:target="save">
                            @if ($listingType === 'giveaway')
                                Objavi poklon (Besplatno)
                            @elseif($listingType === 'auction')
                                Kreiraj aukciju
                                ({{ \App\Models\Setting::get('listing_fee_enabled') ? \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD' : 'Besplatno' }})
                            @else
                                Objavi oglas
                                ({{ \App\Models\Setting::get('listing_fee_enabled') ? \App\Models\Setting::get('listing_fee_amount', 10) . ' RSD' : 'Besplatno' }})
                            @endif
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Objavljujem...
                        </span>
                    </button>
                </div>
