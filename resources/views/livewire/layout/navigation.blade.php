<div>
    <!-- Placeholder to maintain layout when nav is fixed -->
    <div class="h-16"></div>

    <!-- Credit Received Toast Notification -->
    @if (session()->has('credit_received'))
        <div x-data="{ show: true }" x-show="show" x-cloak x-transition x-init="setTimeout(() => show = false, 5000)"
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
                        {{ session('credit_received')['sender_name'] }} vam je poslao
                        {{ session('credit_received')['amount'] }}
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

    <!-- Public Notifications -->
    @php
        $publicNotification = \App\Models\PublicNotification::active()->latest()->first();
    @endphp

    <nav x-data="{
        notificationShown: @if ($publicNotification) !localStorage.getItem('hidden_public_notification_' + {{ $publicNotification->id }}) @else false @endif
    }" :class="notificationShown ? 'top-[60px]' : ''"
        class="bg-white dark:bg-slate-800 shadow-lg fixed top-0 left-0 right-0 z-[90] border-b border-slate-200 dark:border-slate-700 transition-all duration-300">

        @if ($publicNotification)
            <div x-data="{
                show: !localStorage.getItem('hidden_public_notification_' + {{ $publicNotification->id }}),
                hideNotification() {
                    this.show = false;
                    localStorage.setItem('hidden_public_notification_' + {{ $publicNotification->id }}, 'true');
                    $dispatch('notification-hidden');
                }
            }" x-show="show" x-cloak x-init="$watch('show', value => { notificationShown = value })"
                @notification-hidden.window="notificationShown = false" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="bg-green-700 text-white text-center py-3 px-4 fixed top-0 left-0 right-0 z-[60]">
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
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <!-- Light theme logo -->
                        <img src="{{ asset('images/logo-light.svg') }}" alt="PazAriO"
                            class="h-8 md:h-10 w-auto dark:hidden">
                        <!-- Dark theme logo -->
                        <img src="{{ asset('images/logo-dark.svg') }}" alt="PazAriO"
                            class="h-8 md:h-10 w-auto hidden dark:block">
                    </a>
                </div>

                <!-- Search Bar - Desktop only -->
                <div class="hidden md:flex flex-1 px-4">
                    @include('livewire.layout.search-new')
                </div>

                <!-- Right Section - Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Postavi dropdown - samo za obične korisnike (ne admin) -->
                    @auth
                        @if (!auth()->user()->is_admin)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Postavi
                                    <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak @click.away="open = false" x-transition
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-slate-700 ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('listings.create') }}" @click="open = false"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-sky-50 dark:hover:bg-sky-900 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                                            <svg class="w-5 h-5 mr-3 text-sky-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                </path>
                                            </svg>
                                            Oglas
                                        </a>
                                        <a href="{{ route('listings.create') }}?type=auction" @click="open = false"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-amber-50 dark:hover:bg-amber-900 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                            <i class="fas fa-gavel text-amber-600 mr-3"></i>
                                            Aukcija
                                        </a>
                                        <a href="{{ route('services.create') }}" @click="open = false"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                            <i class="fas fa-tools text-slate-600 dark:text-slate-400 mr-3"></i>
                                            Usluga
                                        </a>
                                        <a href="{{ route('listings.create') }}?type=giveaway" @click="open = false"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-green-50 dark:hover:bg-green-900 hover:text-green-600 dark:hover:text-green-400 transition-colors">
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
                                    class="inline-flex items-center justify-center w-10 h-10 border border-slate-300 dark:border-slate-600 rounded-full shadow-sm bg-white dark:bg-slate-700 hover:bg-sky-50 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400">
                                    <i class="fas fa-cog text-slate-700 dark:text-slate-200 text-lg"></i>
                                </a>
                            </div>
                        @endif

                        <!-- User dropdown -->
                        <div class="relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="flex items-center text-sm font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400 border border-slate-300 dark:border-slate-600 rounded-md hover:border-slate-400 dark:hover:border-slate-500 bg-white dark:bg-slate-700">
                                        @if (auth()->user()->avatar)
                                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-600 overflow-hidden">
                                                <img src="{{ auth()->user()->avatar_url }}" alt=""
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div
                                                class="px-4 py-2 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="ml-2 text-slate-700 dark:text-slate-200 text-sm font-medium">
                                            {{ auth()->user()->name }}
                                        </span>
                                        <svg class="ml-1 h-4 w-4 text-slate-700 dark:text-slate-200" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="max-h-[70vh] overflow-y-auto scrollbar-hide">
                                        <style>
                                            /* Hide scrollbar but keep functionality */
                                            .scrollbar-hide::-webkit-scrollbar {
                                                display: none;
                                            }
                                            .scrollbar-hide {
                                                -ms-overflow-style: none;  /* IE and Edge */
                                                scrollbar-width: none;  /* Firefox */
                                            }
                                        </style>
                                        @if (auth()->user()->is_admin)
                                            <!-- Admin dropdown menu -->
                                            <x-dropdown-link href="{{ route('profile') }}">
                                                Moj profil
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('messages.inbox') }}">
                                                Poruke
                                                @if ($this->unreadMessagesCount > 0)
                                                    <span
                                                        class="ml-2 bg-red-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">{{ $this->unreadMessagesCount }}</span>
                                                @endif
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('notifications.index') }}">
                                                Obaveštenja
                                                @if ($this->unreadNotificationsCount > 0)
                                                    <span
                                                        class="ml-2 bg-red-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">{{ $this->unreadNotificationsCount }}</span>
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
                                            <x-dropdown-link href="{{ route('listings.my') }}"
                                                class="!bg-sky-50 !text-sky-700 hover:!bg-sky-100 dark:!bg-sky-900/50 dark:!text-sky-300 dark:hover:!bg-sky-900/30">
                                                <i class="fas fa-list mr-2"></i>
                                                Moji oglasi
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('auctions.my') }}"
                                                class="!bg-amber-50 !text-amber-700 hover:!bg-amber-100 dark:!bg-amber-900/50 dark:!text-amber-300 dark:hover:!bg-amber-900/30">
                                                <i class="fas fa-gavel mr-2"></i>
                                                Moje aukcije
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('services.my') }}"
                                                class="!bg-slate-100 !text-slate-700 hover:!bg-slate-200 dark:!bg-slate-800/70 dark:!text-slate-300 dark:hover:!bg-slate-800/50">
                                                <i class="fas fa-tools mr-2"></i>
                                                Moje usluge
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('favorites.index') }}">
                                                Omiljeni
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('messages.inbox') }}">
                                                Poruke
                                                @if ($this->unreadMessagesCount > 0)
                                                    <span
                                                        class="ml-2 bg-red-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">{{ $this->unreadMessagesCount }}</span>
                                                @endif
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('notifications.index') }}">
                                                Obaveštenja
                                                @if ($this->unreadNotificationsCount > 0)
                                                    <span
                                                        class="ml-2 bg-red-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">{{ $this->unreadNotificationsCount }}</span>
                                                @endif
                                            </x-dropdown-link>

                                            <x-dropdown-link href="{{ route('ratings.my') }}">
                                                Moje ocene
                                                @php
                                                    $totalRatings = auth()->user()->total_ratings_count ?? 0;
                                                @endphp
                                                @if ($totalRatings > 0)
                                                    <span
                                                        class="ml-2 bg-green-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">
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

                                        <div class="border-t border-slate-100"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="!bg-red-50 !text-red-700 hover:!bg-red-100 dark:!bg-red-900/50 dark:!text-red-300 dark:hover:!bg-red-900/30">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                Odjavi se
                                            </x-dropdown-link>
                                        </form>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <!-- Login/Register links -->
                        <div class="flex space-x-2">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Prijavi se
                            </a>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center bg-sky-600 dark:bg-sky-500 text-white hover:bg-sky-700 dark:hover:bg-sky-600 px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user-plus mr-2"></i>
                                Registruj se
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile buttons -->
                <div class="md:hidden flex items-center space-x-3 relative z-50">
                    @auth
                        <!-- Mobile Add Listing Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center justify-center w-10 h-10 border border-slate-300 dark:border-slate-600 rounded-full shadow-sm bg-white dark:bg-slate-700 hover:bg-green-50 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400">
                                <svg class="h-5 w-5 text-slate-700 dark:text-slate-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-slate-700 ring-1 ring-black ring-opacity-5 z-[100]">
                                <div class="py-1">
                                    <a href="{{ route('listings.create') }}" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-sky-50 dark:hover:bg-sky-900 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-sky-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                        Oglas
                                    </a>
                                    <a href="{{ route('listings.create') }}?type=auction" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-amber-50 dark:hover:bg-amber-900 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                        <i class="fas fa-gavel text-amber-600 mr-3"></i>
                                        Aukcija
                                    </a>
                                    <a href="{{ route('services.create') }}" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                        <i class="fas fa-tools text-slate-600 dark:text-slate-400 mr-3"></i>
                                        Usluga
                                    </a>
                                    <a href="{{ route('listings.create') }}?type=giveaway" @click="open = false"
                                        class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-green-50 dark:hover:bg-green-900 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                        <i class="fas fa-gift text-green-600 mr-3"></i>
                                        Poklon
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile User Avatar Button -->
                        <button type="button" id="mobile-user-menu-button"
                            class="flex items-center justify-center w-10 h-10 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400 border-2 border-slate-300 hover:border-slate-400">
                            @if (auth()->user()->avatar)
                                <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-600 overflow-hidden">
                                    <img src="{{ auth()->user()->avatar_url }}" alt=""
                                        class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </button>
                    @else
                        <!-- Mobile Login/Register Buttons -->
                        <div class="flex items-center space-x-2">
                            <!-- Mobile Login Button -->
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center w-10 h-10 border border-slate-300 dark:border-slate-600 rounded-full shadow-sm bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none">
                                <i class="fas fa-sign-in-alt text-slate-700 dark:text-slate-200"></i>
                            </a>
                            <!-- Mobile Register Button -->
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center w-10 h-10 bg-sky-600 dark:bg-sky-500 rounded-full shadow-sm text-white hover:bg-sky-700 dark:hover:bg-sky-600 focus:outline-none">
                                <i class="fas fa-user-plus"></i>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Search Bar - Full width below navigation -->
            <div class="md:hidden px-0 md:px-2 py-3 border-t border-slate-200 dark:border-slate-700">
                @include('livewire.layout.search-new')
            </div>

            <!-- Mobile menu (hidden by default) -->
            <div id="mobile-menu" class="md:hidden hidden">
                <div
                    class="px-2 pt-2 pb-3 space-y-1 bg-white dark:bg-slate-800 shadow-lg border-t border-slate-200 dark:border-slate-700 max-h-[calc(100vh-8rem)] overflow-y-auto scrollbar-hide">
                    {{-- <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'bg-slate-100 text-sky-600' : 'text-slate-700 dark:text-slate-200 hover:text-sky-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Početna
                </a>
                <a href="{{ route('listings.index') }}"
                    class="{{ request()->routeIs('listings.index') ? 'bg-slate-100 text-sky-600' : 'text-slate-700 dark:text-slate-200 hover:text-sky-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Svi oglasi
                </a>
                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.index') ? 'bg-slate-100 text-sky-600' : 'text-slate-700 dark:text-slate-200 hover:text-sky-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Kategorije
                </a> --}}

                    @auth
                        <!-- User profile section in mobile menu -->
                        <div class="border-t border-slate-200 pt-4 pb-2">
                            <div class="flex items-center px-3 py-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-medium text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-slate-800 dark:text-slate-200">
                                        {{ auth()->user()->name }}</div>
                                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                        {{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('dashboard') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('profile') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Moj profil
                        </a>
                        <a href="{{ route('listings.create') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Postavi oglas
                        </a>
                        <a href="{{ route('listings.my') }}"
                            class="!bg-sky-50 !text-sky-700 hover:!bg-sky-100 dark:!bg-sky-900/50 dark:!text-sky-300 dark:hover:!bg-sky-900/30 block px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-list mr-2"></i>
                            Moji oglasi
                        </a>
                        <a href="{{ route('auctions.my') }}"
                            class="!bg-amber-50 !text-amber-700 hover:!bg-amber-100 dark:!bg-amber-900/50 dark:!text-amber-300 dark:hover:!bg-amber-900/30 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                            <i class="fas fa-gavel mr-2"></i>
                            Moje aukcije
                        </a>
                        <a href="{{ route('services.my') }}"
                            class="!bg-slate-100 !text-slate-700 hover:!bg-slate-200 dark:!bg-slate-800/70 dark:!text-slate-300 dark:hover:!bg-slate-800/50 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                            <i class="fas fa-tools mr-2"></i>
                            Moje usluge
                        </a>
                        <a href="{{ route('favorites.index') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Omiljeni
                        </a>
                        <a href="{{ route('messages.inbox') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Poruke
                            @if ($this->unreadMessagesCount > 0)
                                <span class="ml-2 bg-red-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">
                                    {{ $this->unreadMessagesCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('notifications.index') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Obaveštenja
                            @if ($this->unreadNotificationsCount > 0)
                                <span class="ml-2 bg-red-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">
                                    {{ $this->unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('ratings.my') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Moje ocene
                            @auth
                                @php
                                    $totalRatings = auth()->user()->total_ratings_count ?? 0;
                                @endphp
                                @if ($totalRatings > 0)
                                    <span class="ml-1 bg-green-600 text-white rounded px-1.5 py-0.5 text-sm font-semibold">
                                        {{ $totalRatings }}
                                    </span>
                                @endif
                            @endauth
                        </a>
                        <a href="{{ route('balance.index') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-slate-900 block px-3 py-2 rounded-md text-base font-medium">
                            Balans
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="!bg-red-50 !text-red-700 hover:!bg-red-100 dark:!bg-red-900/50 dark:!text-red-300 dark:hover:!bg-red-900/30 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Odjavi se
                            </a>
                        </form>
                    @else
                        <div class="border-t border-slate-200 pt-4"></div>
                        <a href="{{ route('login') }}"
                            class="text-slate-700 dark:text-slate-200 hover:text-sky-600 block px-3 py-2 rounded-md text-base font-medium">
                            Prijavi se
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-sky-600 text-white hover:bg-sky-700 block px-3 py-2 rounded-md text-base font-medium">
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
                        if (!mobileMenu.contains(event.target) && !userMenuButton.contains(event.target) && !
                            mobileMenu
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
