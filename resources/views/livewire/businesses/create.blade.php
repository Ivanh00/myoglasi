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
            @php
                $user = auth()->user();
                $hasBusinessPlan = $user->payment_plan === 'business'
                    && $user->plan_expires_at
                    && $user->plan_expires_at->isFuture()
                    && $user->business_plan_total > 0;

                $businessFeeEnabled = \App\Models\Setting::get('business_fee_enabled', false);
                $businessFeeAmount = \App\Models\Setting::get('business_fee_amount', 2000);
                $businessPlanLimit = $user->business_plan_total;
                $activeBusinessCount = $user->businesses()->where('status', 'active')->count();
            @endphp

            <!-- Business Plan Status Box -->
            @if ($hasBusinessPlan)
                <div class="mt-2 p-4 bg-purple-50 dark:bg-purple-900 border border-purple-300 dark:border-purple-700 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-3 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-purple-800 dark:text-purple-200">
                                <i class="fas fa-briefcase mr-1"></i>
                                Biznis plan aktivan!
                            </p>
                            <div class="mt-2 flex items-center">
                                <div class="flex-1">
                                    @if ($activeBusinessCount < $businessPlanLimit)
                                        <div class="flex justify-between text-xs text-purple-700 dark:text-purple-200 mb-1">
                                            <span>Aktivni biznisi: {{ $activeBusinessCount }} / {{ $businessPlanLimit }}</span>
                                            <span class="font-semibold">{{ $businessPlanLimit - $activeBusinessCount }} slobodnih</span>
                                        </div>
                                        <div class="w-full bg-purple-300/50 dark:bg-purple-600/50 rounded-full h-2">
                                            <div class="bg-purple-600 dark:bg-purple-200 h-2 rounded-full transition-all"
                                                style="width: {{ $businessPlanLimit > 0 ? ($activeBusinessCount / $businessPlanLimit) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex justify-between text-xs text-purple-700 dark:text-purple-200 mb-1">
                                            <span>Aktivni biznisi: {{ $activeBusinessCount }} / {{ $businessPlanLimit }}</span>
                                            <span class="font-semibold text-orange-600 dark:text-orange-400">Plan popunjen</span>
                                        </div>
                                        <div class="w-full bg-purple-300/50 dark:bg-purple-600/50 rounded-full h-2">
                                            <div class="bg-purple-600 dark:bg-purple-200 h-2 rounded-full transition-all"
                                                style="width: 100%">
                                            </div>
                                        </div>
                                        @if ($businessFeeEnabled)
                                            <p class="text-xs text-orange-700 dark:text-orange-300 mt-2">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Svaki sledeći biznis se naplaćuje: <strong>{{ number_format($businessFeeAmount, 0, ',', '.') }} RSD</strong>
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 flex justify-between items-center text-xs text-purple-700 dark:text-purple-200">
                                <span>
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Važi do: <strong>{{ $user->plan_expires_at->format('d.m.Y') }}</strong>
                                </span>
                                <span>
                                    <i class="fas fa-wallet mr-1"></i>
                                    Kredit: <strong>{{ number_format($user->balance, 0, ',', '.') }} RSD</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-2 p-3 bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 rounded">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-purple-800 dark:text-purple-200">
                                Vaš trenutni balans: <strong>{{ number_format($user->balance, 2) }} RSD</strong>
                            </p>
                            @if ($businessFeeEnabled)
                                <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Cena postavljanja: <strong>{{ number_format($businessFeeAmount, 2) }} RSD</strong>
                                </p>
                            @else
                                <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Postavljanje je <strong>besplatno</strong>
                                </p>
                            @endif
                            @if (\App\Models\Setting::get('business_plan_enabled', false))
                                <p class="text-xs text-purple-600 dark:text-purple-300 mt-2">
                                    💡 <a href="{{ route('balance.plan-selection') }}" class="underline font-semibold">Kupite Biznis plan</a> za {{ number_format(\App\Models\Setting::get('business_plan_price', 10000), 0, ',', '.') }} RSD i dobijte {{ \App\Models\Setting::get('business_plan_limit', 10) }} business-a!
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
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
                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20">
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
                        <span class="text-xs text-slate-500">(iz profila)</span>
                    </label>
                    <input type="text" wire:model="location" id="location" readonly
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-600 text-slate-900 dark:text-slate-100 rounded-lg cursor-not-allowed @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address_1" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Adresa 1
                    </label>
                    <input type="text" wire:model="address_1" id="address_1"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('address_1') border-red-500 @enderror"
                        placeholder="Ulica i broj">
                    @error('address_1')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address_2" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                        Adresa 2
                    </label>
                    <input type="text" wire:model="address_2" id="address_2"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('address_2') border-red-500 @enderror"
                        placeholder="Dodatne informacije o adresi">
                    @error('address_2')
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
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Kontakt informacije</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Kontakt telefon
                            <span class="text-xs text-slate-500">(iz profila)</span>
                        </label>
                        <input type="text" wire:model="contact_phone" id="contact_phone" readonly
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-600 text-slate-900 dark:text-slate-100 rounded-lg cursor-not-allowed @error('contact_phone') border-red-500 @enderror">
                        @error('contact_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            Kontakt email
                            <span class="text-xs text-slate-500">(iz profila)</span>
                        </label>
                        <input type="email" wire:model="contact_email" id="contact_email" readonly
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-600 text-slate-900 dark:text-slate-100 rounded-lg cursor-not-allowed @error('contact_email') border-red-500 @enderror">
                        @error('contact_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Contacts -->
                <div class="border-t border-slate-200 dark:border-slate-600 pt-4">
                    <h4 class="text-md font-medium text-slate-700 dark:text-slate-200 mb-3">Dodatni kontakti (opciono)</h4>

                    <!-- Contact 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="contact_name_2" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Ime kontakt osobe 2
                            </label>
                            <input type="text" wire:model="contact_name_2" id="contact_name_2"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_name_2') border-red-500 @enderror"
                                placeholder="Ime i prezime">
                            @error('contact_name_2')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_phone_2" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
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

                    <!-- Contact 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_name_3" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                Ime kontakt osobe 3
                            </label>
                            <input type="text" wire:model="contact_name_3" id="contact_name_3"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('contact_name_3') border-red-500 @enderror"
                                placeholder="Ime i prezime">
                            @error('contact_name_3')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_phone_3" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
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
                    @if (!empty($images))
                        <span class="text-purple-600 dark:text-purple-400">({{ count($images) }}/{{ \App\Models\Setting::get('max_images_per_business', 10) }})</span>
                    @endif
                </label>

                <!-- Upload Area -->
                @php $maxImages = \App\Models\Setting::get('max_images_per_business', 10); @endphp
                @if (count($images ?? []) < $maxImages)
                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <input type="file" wire:model="tempImages" multiple accept="image/*" class="hidden" id="business-images">
                        <label for="business-images" class="cursor-pointer">
                            <span class="text-purple-600 hover:text-purple-500 font-medium">Kliknite za dodavanje slika</span>
                            <span class="text-slate-500 dark:text-slate-300"> ili prevucite ovde</span>
                        </label>
                        <p class="text-slate-400 text-sm mt-2">PNG, JPG, JPEG do 5MB po slici</p>
                    </div>
                @else
                    <div class="border-2 border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center bg-slate-50 dark:bg-slate-800">
                        <i class="fas fa-images text-slate-400 text-4xl mb-2"></i>
                        <p class="text-slate-600 dark:text-slate-400 font-medium">Dostigli ste maksimum od {{ $maxImages }} slika</p>
                        <p class="text-slate-500 dark:text-slate-300 text-sm">Obrišite neku sliku da biste dodali novu</p>
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
                                    class="w-full h-24 object-cover rounded-lg border border-slate-300 dark:border-slate-600">
                                <button type="button" wire:click="removeImage({{ $index }})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ×
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
