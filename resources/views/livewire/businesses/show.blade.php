<div class="max-w-7xl mx-auto py-6 px-2 sm:px-4 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Business Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Logo Section -->
                @if ($business->logo)
                    <div
                        class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 md:p-8">
                        <div class="flex justify-center">
                            <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}"
                                class="max-h-32 w-auto object-contain">
                        </div>
                    </div>
                @endif

                <!-- Business Info -->
                <div class="p-4 md:p-6">
                    <!-- Title and Category -->
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                            {{ $business->name }}
                        </h1>

                        @if ($business->slogan)
                            <p class="text-lg text-purple-600 dark:text-purple-400 italic mb-3">
                                "{{ $business->slogan }}"
                            </p>
                        @endif

                        <!-- Category Badge -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if ($business->category)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                    @if ($business->category->icon)
                                        <i class="{{ $business->category->icon }} mr-1"></i>
                                    @endif
                                    {{ $business->category->name }}
                                </span>
                            @endif

                            @if ($business->subcategory)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ $business->subcategory->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $business->location }}
                            </div>
                            @if ($business->established_year)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Posluje od {{ $business->established_year }}
                                </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $business->views }} pregleda
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $business->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="prose dark:prose-invert max-w-none mb-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">O nama</h3>
                        <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $business->description }}
                        </p>
                    </div>

                    <!-- Social Links -->
                    @if ($business->website_url || $business->facebook_url || $business->instagram_url || $business->youtube_url)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Pratite nas</h3>
                            <div class="flex flex-wrap gap-3">
                                @if ($business->website_url)
                                    <a href="{{ $business->website_url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                        <i class="fas fa-globe mr-2"></i>
                                        Website
                                    </a>
                                @endif
                                @if ($business->facebook_url)
                                    <a href="{{ $business->facebook_url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <i class="fab fa-facebook mr-2"></i>
                                        Facebook
                                    </a>
                                @endif
                                @if ($business->instagram_url)
                                    <a href="{{ $business->instagram_url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center px-4 py-2 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg hover:bg-pink-200 dark:hover:bg-pink-900/50 transition-colors">
                                        <i class="fab fa-instagram mr-2"></i>
                                        Instagram
                                    </a>
                                @endif
                                @if ($business->youtube_url)
                                    <a href="{{ $business->youtube_url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                        <i class="fab fa-youtube mr-2"></i>
                                        YouTube
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Images Gallery -->
            @if ($business->images->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Galerija</h3>
                    @php
                        $imageArray = $business->images
                            ->map(function ($img) use ($business) {
                                return ['url' => Storage::url($img->path), 'alt' => $business->name];
                            })
                            ->toArray();
                    @endphp
                    <x-image-lightbox :images="$imageArray" :title="$business->name">
                        <div class="relative">
                            <!-- Glavna slika -->
                            <div class="mb-4 rounded-lg overflow-hidden relative">
                                <img id="mainBusinessImage" src="{{ Storage::url($business->images->first()->path) }}"
                                    alt="{{ $business->name }}"
                                    class="w-full h-80 object-cover rounded-lg cursor-pointer hover:opacity-95 transition-opacity"
                                    @click="openLightbox(0)">

                                <!-- Zoom icon overlay -->
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 hover:opacity-100 transition-opacity bg-black bg-opacity-20"
                                    @click="openLightbox(0)">
                                    <div class="bg-white bg-opacity-90 rounded-full p-3">
                                        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Mala galerija -->
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($business->images as $index => $image)
                                    <div
                                        class="cursor-pointer border-2 rounded-lg overflow-hidden {{ $index === 0 ? 'border-purple-500' : 'border-slate-200 dark:border-slate-600' }}">
                                        <img src="{{ Storage::url($image->path) }}"
                                            alt="{{ $business->name }} - slika {{ $index + 1 }}"
                                            class="w-full h-20 object-cover"
                                            @click="currentIndex = {{ $index }}; changeMainBusinessImage('{{ Storage::url($image->path) }}', $el)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-image-lightbox>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 md:p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                    <i class="fas fa-address-card mr-2 text-purple-600"></i>
                    Kontakt informacije
                </h3>

                <div class="space-y-4">
                    <!-- Primary Contact -->
                    @if ($business->contact_phone)
                        <div class="flex items-start">
                            <i class="fas fa-phone text-purple-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Telefon</p>
                                <a href="tel:{{ $business->contact_phone }}"
                                    class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400">
                                    {{ $business->contact_phone }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($business->contact_email)
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-purple-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Email</p>
                                <a href="mailto:{{ $business->contact_email }}"
                                    class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400 break-all">
                                    {{ $business->contact_email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Contacts -->
                    @if ($business->contact_phone_2 && $business->contact_name_2)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <div class="flex items-start">
                                <i class="fas fa-phone text-purple-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $business->contact_name_2 }}</p>
                                    <a href="tel:{{ $business->contact_phone_2 }}"
                                        class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400">
                                        {{ $business->contact_phone_2 }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($business->contact_phone_3 && $business->contact_name_3)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <div class="flex items-start">
                                <i class="fas fa-phone text-purple-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $business->contact_name_3 }}</p>
                                    <a href="tel:{{ $business->contact_phone_3 }}"
                                        class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400">
                                        {{ $business->contact_phone_3 }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Owner Info -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Objavio</p>
                        <div class="flex items-center">
                            @if ($business->user->avatar)
                                <img src="{{ $business->user->avatar_url }}" alt="{{ $business->user->name }}"
                                    class="w-10 h-10 rounded-full mr-3">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-medium mr-3">
                                    {{ strtoupper(substr($business->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-slate-900 dark:text-slate-100 font-medium">{{ $business->user->name }}
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Član od
                                    {{ $business->user->created_at->format('Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @auth
                        @if (auth()->id() === $business->user_id)
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                <a href="{{ route('businesses.edit', $business->slug) }}"
                                    class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Izmeni
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Share Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                    <i class="fas fa-share-alt mr-2 text-purple-600"></i>
                    Podeli
                </h3>
                <div class="flex gap-2">
                    <button
                        onclick="shareItem({{ Js::from($business->name) }}, 'Pogledaj ovaj biznis: ' + {{ Js::from($business->name) }}, window.location.href)"
                        class="flex-1 px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        <i class="fas fa-share mr-2"></i>
                        Podeli
                    </button>
                </div>
            </div>

            <!-- Favorite Card -->
            @auth
                @if (auth()->id() !== $business->user_id)
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 md:p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                            <i class="fas fa-heart mr-2 text-purple-600"></i>
                            Omiljeni
                        </h3>
                        <livewire:business-favorite-button :business="$business" />
                    </div>
                @endif
            @else
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                        <i class="fas fa-heart mr-2 text-purple-600"></i>
                        Omiljeni
                    </h3>
                    <livewire:business-favorite-button :business="$business" />
                </div>
            @endauth
        </div>
    </div>
</div>

@include('livewire.components.share-popup')

<script>
    function changeMainBusinessImage(src, element) {
        // Postavi glavnu sliku
        document.getElementById('mainBusinessImage').src = src;

        // Ukloni prethodni border
        document.querySelectorAll('.border-purple-500').forEach(item => {
            item.classList.remove('border-purple-500');
            item.classList.add('border-slate-200', 'dark:border-slate-600');
        });

        // Dodaj border na selektovanu sliku
        if (element && element.parentElement) {
            element.parentElement.classList.remove('border-slate-200', 'dark:border-slate-600');
            element.parentElement.classList.add('border-purple-500');
        }
    }
</script>
