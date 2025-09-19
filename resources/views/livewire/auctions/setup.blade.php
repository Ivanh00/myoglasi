<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Postavite aukciju</h1>
                <p class="text-slate-600">Konfigurirajte parametere aukcijske prodaje</p>
            </div>

            <!-- Listing Preview -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-16 w-16 mr-4">
                        @if ($listing->images->count() > 0)
                            <img src="{{ $listing->images->first()->url }}" alt="{{ $listing->title }}"
                                class="h-16 w-16 rounded-lg object-cover">
                        @else
                            <div class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center">
                                <i class="fas fa-image text-slate-400"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-amber-900">{{ $listing->title }}</h3>
                        <p class="text-amber-800 text-sm">Trenutna cena:
                            {{ number_format($listing->price, 0, ',', '.') }} RSD</p>
                        <p class="text-amber-700 text-sm">{{ $listing->category->name }} • {{ $listing->location }}</p>
                    </div>
                </div>
            </div>

            <!-- Auction Form -->
            <form wire:submit.prevent="createAuction">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Pricing -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Cene</h3>

                        <!-- Starting Price -->
                        <div class="mb-6">
                            <label for="startingPrice" class="block text-sm font-medium text-slate-700 mb-2">
                                Početna cena aukcije <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="startingPrice" id="startingPrice" step="1"
                                min="1"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <p class="text-xs text-slate-500 mt-1">Preporučeno: 70% od trenutne cene
                                ({{ number_format($listing->price * 0.7, 0, ',', '.') }} RSD)</p>
                            @error('startingPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buy Now Price -->
                        <div class="mb-6">
                            <label for="buyNowPrice" class="block text-sm font-medium text-slate-700 mb-2">
                                Kupi odmah cena (opciono)
                            </label>
                            <input type="number" wire:model="buyNowPrice" id="buyNowPrice" step="1"
                                min="1"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <p class="text-xs text-slate-500 mt-1">Kupci mogu kupiti odmah po ovoj ceni</p>
                            @error('buyNowPrice')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column: Timing -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Vremenski okvir</h3>

                        <!-- Start Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Kada počinje aukcija?</label>
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="startType" value="immediately"
                                        name="startType" class="mr-3 h-4 w-4 text-amber-600 focus:ring-amber-500">
                                    <span class="text-slate-700">Odmah</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="startType" value="scheduled" name="startType"
                                        class="mr-3 h-4 w-4 text-amber-600 focus:ring-amber-500">
                                    <span class="text-slate-700">Zakazano</span>
                                </label>
                            </div>
                        </div>

                        <!-- Scheduled Start -->
                        @if ($startType === 'scheduled')
                            <div class="mb-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="startDate"
                                            class="block text-sm font-medium text-slate-700 mb-2">Datum</label>
                                        <input type="date" wire:model="startDate" id="startDate"
                                            min="{{ $minDate }}"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                        @error('startDate')
                                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="startTime"
                                            class="block text-sm font-medium text-slate-700 mb-2">Vreme</label>
                                        <input type="time" wire:model="startTime" id="startTime"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                        @error('startTime')
                                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Duration -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Trajanje aukcije</label>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach ($durationOptions as $days => $label)
                                    <label
                                        class="flex flex-col items-center cursor-pointer p-3 border-2 rounded-lg transition-all
                                        {{ $duration == $days ? 'border-amber-500 bg-amber-50' : 'border-slate-300 hover:border-amber-300' }}">
                                        <input type="radio" wire:model.live="duration" value="{{ $days }}"
                                            class="sr-only">
                                        <span class="text-2xl mb-1">⏰</span>
                                        <span
                                            class="text-sm font-medium {{ $duration == $days ? 'text-amber-700' : 'text-slate-700' }}">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('duration')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Auction Rules Info -->
                <div class="mt-8 p-6 bg-sky-50 border border-sky-200 rounded-lg">
                    <h4 class="text-sky-900 font-semibold mb-3">Pravila aukcije</h4>
                    <ul class="text-sky-800 text-sm space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Aukcija se automatski produžava za 3 minuta ako stigne ponuda u poslednje 3
                                minuta</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Maksimalno 10 produžavanja po aukciji</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Svi učesnici će biti obavešteni kada ih neko nadmaši</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Nakon završetka aukcije, pobednik ima 48h da kontaktira</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('listings.show', $listing) }}"
                        class="px-6 py-3 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Nazad na oglas
                    </a>

                    <button type="submit"
                        class="px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition-colors">
                        <i class="fas fa-gavel mr-2"></i>
                        Kreiraj aukciju
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
