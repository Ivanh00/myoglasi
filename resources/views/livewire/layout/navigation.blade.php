<!-- Start of Selection -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                    MyOglasi
                </a>
            </div>

            <!-- Search Bar -->
            @include('livewire.layout.search')

            <!-- Right Section - Desktop -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Postavi oglas dugme - samo za ulogovane -->
                @auth
                    <div>
                        <a href="{{ route('listings.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-green-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Postavi oglas
                        </a>
                    </div>

                    <!-- User dropdown -->
                    <div class="relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 border border-gray-300 rounded-md hover:border-gray-400">
                                    <div
                                        class="px-4 py-2 rounded-full bg-gray-500 flex items-center justify-center text-white font-medium ">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="ml-2 text-gray-700 text-sm font-medium">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <svg class="ml-1 h-4 w-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link href="{{ route('profile') }}">
                                    Moj profil
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('listings.my') }}">
                                    Moji oglasi
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('favorites.index') }}">
                                    Omiljeni
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('messages.inbox') }}">
                                    Poruke
                                    @auth
                                        @php
                                            $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())
                                                ->where('is_read', false)
                                                ->count();
                                        @endphp
                                        @if ($unreadCount > 0)
                                            <span class="unread-badge">{{ $unreadCount }}</span>
                                        @endif
                                    @endauth
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('balance.index') }}">
                                    Balans
                                </x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 hover:text-red-800">
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
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                            Prijavi se
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                            Registruj se
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button type="button" id="mobile-menu-button"
                    class="bg-white p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700">
                    <span class="sr-only">Open menu</span>
                    <!-- Hamburger icon -->
                    <svg id="menu-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- X icon (hidden by default) -->
                    <svg id="close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white shadow-lg">
                {{-- <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'bg-gray-100 text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Početna
                </a>
                <a href="{{ route('listings.index') }}"
                    class="{{ request()->routeIs('listings.index') ? 'bg-gray-100 text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} block px-3 py-2 rounded-md text-base font-medium">
                    Svi oglasi
                </a>
                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.index') ? 'bg-gray-100 text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} block px-3 py-2 rounded-md text-base font-medium">
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
                                <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile') }}"
                        class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Moj profil
                    </a>
                    <a href="{{ route('listings.create') }}"
                        class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Postavi oglas
                    </a>
                    <a href="{{ route('listings.my') }}"
                        class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Moji oglasi
                    </a>
                    <a href="{{ route('favorites.index') }}"
                        class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Omiljeni
                    </a>
                    <a href="{{ route('messages.index') }}"
                        class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                        Poruke
                    </a>
                    <a href="{{ route('balance.index') }}"
                        class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
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
                        class="text-gray-700 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">
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
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');

            menuButton.addEventListener('click', function() {
                // Toggle menu visibility
                mobileMenu.classList.toggle('hidden');

                // Toggle icons
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !menuButton.contains(event.target) && !mobileMenu
                    .classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });

            // Close menu when clicking on a link
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                });
            });
        });
    </script>
</nav>
