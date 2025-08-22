<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Left Section: Logo + Navigation Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                        MyOglasi
                    </a>
                </div>

                {{-- <!-- Primary Navigation - Sakriven na mobilnim, vidljiv na desktopu -->
                <div class="hidden md:ml-6 md:flex md:space-x-4">
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-3 py-2 text-sm font-medium">
                        Početna
                    </a>
                    <a href="{{ route('listings.index') }}"
                        class="{{ request()->routeIs('listings.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-3 py-2 text-sm font-medium">
                        Svi oglasi
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="{{ request()->routeIs('categories.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-3 py-2 text-sm font-medium">
                        Kategorije
                    </a>
                </div> --}}
            </div>

            <!-- Center Section: Search Bar -->
            <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                <div class="w-full max-w-xs lg:max-w-md">
                    <form action="{{ route('search.index') }}" method="GET">
                        <label for="search" class="sr-only">Pretraži oglase</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="query" id="search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Pretraži oglase..." value="{{ request('query') }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Section: Actions + User Menu -->
            <div class="flex items-center">
                <!-- Postavi oglas dugme - samo za ulogovane -->
                @auth
                    <div class="ml-4 hidden md:block">
                        <a href="{{ route('listings.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Postavi oglas
                        </a>
                    </div>
                @endauth

                <!-- User dropdown -->
                <div class="ml-4 flex items-center">
                    @auth
                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button type="button"
                                class="bg-white p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Open menu</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Desktop user menu -->
                        <div class="hidden md:ml-4 md:flex md:items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span class="sr-only">Open user menu</span>
                                        <div
                                            class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <span class="ml-2 text-gray-700 text-sm hidden lg:inline">
                                            {{ auth()->user()->name }}
                                        </span>
                                        <svg class="ml-1 h-4 w-4 text-gray-400 hidden lg:inline" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link href="{{ route('listings.my') }}">
                                        👁️ Moji oglasi
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('favorites.index') }}">
                                        ❤️ Omiljeni
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('messages.index') }}">
                                        💬 Poruke
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('balance.index') }}">
                                        💰 Balans
                                    </x-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="text-red-600 hover:text-red-800">
                                            🚪 Odjavi se
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <!-- Login/Register links -->
                        <div class="flex space-x-2">
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                                Prijavi se
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">
                                Registruj se
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state -->
        <div class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}"
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
                </a>
                @auth
                    <a href="{{ route('listings.create') }}"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium">
                        Postavi oglas
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
