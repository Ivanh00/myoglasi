<!-- Start of Selection -->
<div>
    <!-- Public Notifications -->
    @php
        $publicNotification = \App\Models\PublicNotification::active()->latest()->first();
    @endphp

    @if($publicNotification)
        <div x-data="{
            show: !localStorage.getItem('hidden_public_notification_' + {{ $publicNotification->id }}),
            hideNotification() {
                this.show = false;
                localStorage.setItem('hidden_public_notification_' + {{ $publicNotification->id }}, 'true');
            }
        }"
        x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="bg-green-700 text-white text-center py-3 px-4 relative z-[60]" style="display: none;">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex-1 flex items-center justify-center">
                    <i class="fas fa-bullhorn mr-3"></i>
                    <div>
                        <div class="font-semibold">{{ $publicNotification->title }}</div>
                        <div class="text-sm text-green-100">{{ $publicNotification->message }}</div>
                    </div>
                </div>
                <button @click="hideNotification()" class="text-green-200 hover:text-white ml-4 p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Credit Received Toast Notification -->
@if(session()->has('credit_received'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" 
         class="fixed top-4 right-4 z-[9999] bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-coins text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">
                    Kredit primljen!
                </p>
                <p class="text-xs opacity-90">
                    {{ session('credit_received')['sender_name'] }} vam je poslao {{ session('credit_received')['amount'] }}
                </p>
            </div>
            <div class="ml-4">
                <button @click="show = false" class="text-green-200 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
@endif

<nav class="bg-white dark:bg-gray-800 shadow-lg sticky top-0 z-50 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600 dark:text-blue-400">
                    MyOglasi
                </a>
            </div>

            <!-- Search Bar -->
            @include('livewire.layout.search-new')

            <!-- Right Section - Desktop -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Postavi dropdown - samo za obične korisnike (ne admin) -->
                @auth
                    @if(!auth()->user()->is_admin)
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Postavi
                                <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('listings.create') }}" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Oglas
                                    </a>
                                    <a href="{{ route('listings.create') }}?type=auction" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-yellow-50 dark:hover:bg-yellow-900 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors">
                                        <i class="fas fa-gavel text-yellow-600 mr-3"></i>
                                        Aukcija
                                    </a>
                                    <a href="{{ route('services.create') }}" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        <i class="fas fa-tools text-gray-600 mr-3"></i>
                                        Usluga
                                    </a>
                                    <a href="{{ route('listings.create') }}?type=giveaway" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                        <i class="fas fa-gift text-green-600 mr-3"></i>
                                        Poklon
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Admin Panel Icon Button -->
                        <div>
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm bg-white dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                <i class="fas fa-cog text-gray-700 dark:text-gray-200 text-lg"></i>
                            </a>
                        </div>
                    @endif

                    <!-- User dropdown -->
                    <div class="relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 border border-gray-300 dark:border-gray-600 rounded-md hover:border-gray-400 dark:hover:border-gray-500 bg-white dark:bg-gray-700">
                                    @if (auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="px-4 py-2 rounded-full bg-gray-500 flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="ml-2 text-gray-700 dark:text-gray-200 text-sm font-medium">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <svg class="ml-1 h-4 w-4 text-gray-700 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if(auth()->user()->is_admin)
                                    <!-- Admin dropdown menu -->
                                    <x-dropdown-link href="{{ route('profile') }}">
                                        Moj profil
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('messages.inbox') }}">
                                        Poruke
                                        @if ($this->unreadMessagesCount > 0)
                                            <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">{{ $this->unreadMessagesCount }}</span>
                                        @endif
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('notifications.index') }}">
                                        Obaveštenja
                                        @if ($this->unreadNotificationsCount > 0)
                                            <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">{{ $this->unreadNotificationsCount }}</span>
                                        @endif
                                    </x-dropdown-link>
                                @else
                                    <!-- Regular user dropdown menu -->
                                    <x-dropdown-link href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt mr-2"></i>
                                        Dashboard
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('profile') }}">
                                        Moj profil
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('listings.my') }}" class="!bg-blue-600 !text-white hover:!bg-blue-700 dark:hover:!bg-gray-800">
                                        <i class="fas fa-list mr-2"></i>
                                        Moji oglasi
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('auctions.my') }}" class="!bg-yellow-600 !text-white hover:!bg-yellow-700 dark:hover:!bg-gray-800">
                                        <i class="fas fa-gavel mr-2"></i>
                                        Moje aukcije
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('services.my') }}" class="!bg-gray-600 !text-white hover:!bg-gray-700 dark:hover:!bg-gray-800">
                                        <i class="fas fa-tools mr-2"></i>
                                        Moje usluge
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('favorites.index') }}">
                                        Omiljeni
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('messages.inbox') }}">
                                        Poruke
                                        @if ($this->unreadMessagesCount > 0)
                                            <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">{{ $this->unreadMessagesCount }}</span>
                                        @endif
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('notifications.index') }}">
                                        Obaveštenja
                                        @if ($this->unreadNotificationsCount > 0)
                                            <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">{{ $this->unreadNotificationsCount }}</span>
                                        @endif
                                    </x-dropdown-link>
                                    
                                    <x-dropdown-link href="{{ route('ratings.my') }}">
                                        Moje ocene
                                        @php
                                            $totalRatings = auth()->user()->total_ratings_count ?? 0;
                                        @endphp
                                        @if ($totalRatings > 0)
                                            <span class="ml-2 bg-green-600 text-white rounded px-2 py-1 text-xs font-medium">
                                                {{ $totalRatings }}
                                            </span>
                                        @endif
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('balance.index') }}">
                                        Balans
                                    </x-dropdown-link>
                                    
                                    <x-dropdown-link href="{{ route('admin.contact') }}">
                                        Piši Adminu
                                    </x-dropdown-link>
                                @endif
                                
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="!bg-red-600 !text-white hover:!bg-red-700 dark:hover:!bg-gray-800">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Odjavi se
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <!-- Login/Register links -->
                    <div class="flex space-x-2">
                        <a href="{{ route('login') }}"
                            class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium">
                            Prijavi se
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 dark:bg-blue-500 text-white hover:bg-blue-700 dark:hover:bg-blue-600 px-4 py-2 rounded-md text-sm font-medium">
                            Registruj se
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile buttons -->
            <div class="md:hidden flex items-center space-x-3">
                @auth
                    <!-- Mobile Add Listing Button -->
                    <a href="{{ route('listings.create') }}"
                        class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        <svg class="h-5 w-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </a>

                    <!-- Mobile User Avatar Button -->
                    <button type="button" id="mobile-user-menu-button"
                        class="flex items-center justify-center w-10 h-10 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 border-2 border-gray-300 hover:border-gray-400">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-500 flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </button>
                @else
                    <!-- Mobile Login Button -->
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 rounded-full shadow-sm bg-white hover:bg-blue-50 focus:outline-none">
                        <svg class="h-5 w-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white dark:bg-gray-800 shadow-lg border-t border-gray-200 dark:border-gray-700">
                {{-- <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'bg-gray-100 text-indigo-600' : 'text-gray-700 dark:text-gray-200 hover:text-indigo-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Početna
                </a>
                <a href="{{ route('listings.index') }}"
                    class="{{ request()->routeIs('listings.index') ? 'bg-gray-100 text-indigo-600' : 'text-gray-700 dark:text-gray-200 hover:text-indigo-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Svi oglasi
                </a>
                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.index') ? 'bg-gray-100 text-indigo-600' : 'text-gray-700 dark:text-gray-200 hover:text-indigo-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Kategorije
                </a> --}}

                @auth
                    <!-- User profile section in mobile menu -->
                    <div class="border-t border-gray-200 pt-4 pb-2">
                        <div class="flex items-center px-3 py-2">
                            <div
                                class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-medium text-lg">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('profile') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Moj profil
                    </a>
                    <a href="{{ route('listings.create') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Postavi oglas
                    </a>
                    <a href="{{ route('listings.my') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Moji oglasi
                    </a>
                    <a href="{{ route('auctions.my') }}"
                        class="bg-yellow-600 text-white hover:bg-yellow-700 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                        <i class="fas fa-gavel mr-2"></i>
                        Moje aukcije
                    </a>
                    <a href="{{ route('services.my') }}"
                        class="bg-gray-600 text-white hover:bg-gray-700 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                        <i class="fas fa-tools mr-2"></i>
                        Moje usluge
                    </a>
                    <a href="{{ route('favorites.index') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Omiljeni
                    </a>
                    <a href="{{ route('messages.inbox') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Poruke
                        @if ($this->unreadMessagesCount > 0)
                            <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">
                                {{ $this->unreadMessagesCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('notifications.index') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Obaveštenja
                        @if ($this->unreadNotificationsCount > 0)
                            <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">
                                {{ $this->unreadNotificationsCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('ratings.my') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Moje ocene
                        @auth
                            @php
                                $totalRatings = auth()->user()->total_ratings_count ?? 0;
                            @endphp
                            @if ($totalRatings > 0)
                                <span class="ml-1 bg-green-600 text-white rounded px-2 py-1 text-xs font-medium">
                                    {{ $totalRatings }}
                                </span>
                            @endif
                        @endauth
                    </a>
                    <a href="{{ route('balance.index') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Balans
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 hover:text-red-800 block px-3 py-2 rounded-md text-base font-medium">
                            Odjavi se
                        </a>
                    </form>
                @else
                    <div class="border-t border-gray-200 pt-4"></div>
                    <a href="{{ route('login') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">
                        Prijavi se
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium">
                        Registruj se
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('mobile-user-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (userMenuButton && mobileMenu) {
                userMenuButton.addEventListener('click', function() {
                    // Toggle menu visibility
                    mobileMenu.classList.toggle('hidden');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !userMenuButton.contains(event.target) && !mobileMenu
                        .classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                });

                // Close menu when clicking on a link
                const mobileLinks = mobileMenu.querySelectorAll('a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</nav>
</div>
