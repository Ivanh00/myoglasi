<div class="max-w-4xl mx-auto py-6 px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900">Dodaj novi oglas</h1>
            <p class="text-gray-600 mt-2">Popunite sva polja i dodajte slike vašeg proizvoda</p>
            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">
                        Vaš trenutni balans: <strong>{{ number_format(auth()->user()->balance, 2) }} RSD</strong>
                        | Cena oglasa: <strong>10,00 RSD</strong>
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
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
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

                {{-- <!-- DEBUG PANEL -->
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm">
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

            <!-- Condition -->
            <select wire:model="condition_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('condition_id') border-red-500 @enderror">
                <option value="">Odaberite stanje</option>
                @foreach ($conditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
            @error('condition_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Opis oglasa <span class="text-red-500">*</span>
                </label>
                <textarea id="description" rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Detaljno opišite vaš proizvod...">{{ $description }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>
