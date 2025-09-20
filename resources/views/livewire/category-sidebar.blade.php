<div class="w-64 bg-white dark:bg-slate-800 shadow-lg h-screen sticky top-0 overflow-y-auto " x-data="{ openCategory: null }">
    <div class="p-4 border-b border-slate-200 dark:border-slate-700">
        <!-- Header removed for cleaner look -->
    </div>

    <div class="p-2">
        <!-- Theme Switcher -->
        <div class="mb-4 p-3 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">
                Tema</div>
            <div class="grid grid-cols-2 gap-2">
                <button onclick="setTheme('light')"
                    class="flex items-center justify-center px-3 py-2 text-xs font-medium rounded-md transition-colors theme-btn light-theme
                    bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600">
                    <i class="fas fa-sun mr-1"></i>
                    Light
                </button>
                <button onclick="setTheme('dark')"
                    class="flex items-center justify-center px-3 py-2 text-xs font-medium rounded-md transition-colors theme-btn dark-theme
                    bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700">
                    <i class="fas fa-moon mr-1"></i>
                    Dark
                </button>
            </div>
        </div>

        <!-- Aukcije -->
        <a href="{{ route('auctions.index') }}"
            class="flex items-center px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors mb-2 {{ request()->routeIs('auctions.index') ? 'bg-amber-700' : '' }}">
            <i class="fas fa-gavel mr-3"></i>
            Aukcije
        </a>

        <!-- Usluge -->
        <a href="{{ route('services.index') }}"
            class="flex items-center px-4 py-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors mb-2 {{ request()->routeIs('services.*') ? 'bg-slate-700' : '' }}">
            <i class="fas fa-tools mr-3"></i>
            Usluge
        </a>

        <!-- Poklanjam -->
        <a href="{{ route('giveaways.index') }}"
            class="flex items-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors mb-2 {{ request()->routeIs('giveaways.*') ? 'bg-green-700' : '' }}">
            <i class="fas fa-gift mr-3"></i>
            Poklanjam
        </a>

        <!-- Globalni "Oglasi" -->
        <a href="{{ route('listings.index') }}"
            class="flex items-center px-4 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors mb-2 {{ request()->routeIs('listings.index') && !request()->get('selectedCategory') ? 'bg-sky-700' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14-8H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
            </svg>
            Oglasi
        </a>

        @php
            // Učitaj kategorije direktno ovde ako nisu prosleđene
            $categoryTree = isset($categoryTree)
                ? $categoryTree
                : \App\Models\Category::with([
                    'children' => function ($query) {
                        $query->where('is_active', true)->orderBy('sort_order');
                    },
                ])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
        @endphp

        @foreach ($categoryTree as $category)
            <div class="mt-1">
                <!-- Glavna kategorija - ceo red je klikabilan za otvaranje/zatvaranje -->
                <div class="flex items-center justify-between group cursor-pointer rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 {{ request()->get('selectedCategory') == $category->id ? 'bg-sky-50 dark:bg-slate-700' : '' }}"
                    @click="openCategory = openCategory === '{{ $category->id }}' ? null : '{{ $category->id }}'">

                    <!-- Levi deo - ikonica i naziv -->
                    <div
                        class="flex items-center flex-1 px-3 py-2 text-slate-700 dark:text-slate-200 {{ request()->get('selectedCategory') == $category->id ? 'text-sky-600 dark:text-sky-400' : '' }}">
                        @if ($category->icon)
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <i class="{{ $category->icon }} text-sky-600 dark:text-sky-400"></i>
                            </div>
                        @else
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                        @endif
                        <span class="flex-1">{{ $category->name }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-300 ml-2">
                            (@if (method_exists($category, 'getAllListingsCount'))
                                {{ $category->getAllListingsCount() }}
                            @else
                                {{ $category->listings_count ?? 0 }}
                            @endif)
                        </span>
                    </div>

                    <!-- Desni deo - strelica samo ako ima podkategorija -->
                    @if ($category->children->count() > 0)
                        <div class="p-2 text-slate-500 dark:text-slate-300">
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'transform rotate-90': openCategory === '{{ $category->id }}' }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Podkategorije i "Svi oglasi" link -->
                @if ($category->children->count() > 0)
                    <div class="mt-1 overflow-hidden transition-all duration-200"
                        :class="{ 'max-h-0': openCategory !== '{{ $category->id }}', 'max-h-96': openCategory === '{{ $category->id }}' }">

                        <!-- "Svi oglasi" za ovu kategoriju -->
                        <a href="{{ route('listings.index', ['selectedCategory' => $category->id]) }}"
                            class="block px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-slate-100 dark:hover:bg-slate-600 {{ request()->get('selectedCategory') == $category->id ? 'bg-sky-100 dark:bg-slate-600 text-sky-600 dark:text-sky-300' : '' }}">
                            📁 Svi oglasi u {{ $category->name }}
                        </a>

                        @foreach ($category->children as $child)
                            <a href="{{ route('listings.index', ['selectedCategory' => $child->id]) }}"
                                class="block px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-slate-100 dark:hover:bg-slate-600 {{ request()->get('selectedCategory') == $child->id ? 'bg-sky-100 dark:bg-slate-600 text-sky-600 dark:text-sky-300' : '' }}">
                                • {{ $child->name }}
                                <span class="text-xs text-slate-500 dark:text-slate-300 ml-1">
                                    (@if (method_exists($child, 'getAllListingsCount'))
                                        {{ $child->getAllListingsCount() }}
                                    @else
                                        {{ $child->listings_count ?? 0 }}
                                    @endif)
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <!-- Ako nema podkategorija, "Svi oglasi" link bude uvek vidljiv -->
                    <a href="{{ route('listings.index', ['selectedCategory' => $category->id]) }}"
                        class="block px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-slate-100 dark:hover:bg-slate-600 {{ request()->get('selectedCategory') == $category->id ? 'bg-sky-100 dark:bg-slate-600 text-sky-600 dark:text-sky-300' : '' }}">
                        📁 Svi oglasi u {{ $category->name }}
                    </a>
                @endif
            </div>
        @endforeach
    </div>

    @auth
        @if (auth()->user()->is_admin)
            <!-- Admin Sidebar -->
            <div class="border-t mt-4 pt-4 p-2">
                <div class="px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                    Admin opcije</div>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2 text-red-500 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                    Admin Panel
                </a>

                <a href="{{ route('messages.inbox') }}"
                    class="flex items-center px-3 py-2 text-sky-600 dark:text-sky-400 rounded-lg hover:bg-sky-50 dark:hover:bg-slate-700 mt-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                    Poruke
                    @php
                        $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
                            ->where('is_read', false)
                            ->where('is_system_message', false)
                            ->count();
                    @endphp
                    @if ($unreadMessagesCount > 0)
                        <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">
                            {{ $unreadMessagesCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('notifications.index') }}"
                    class="flex items-center px-3 py-2 text-sky-600 dark:text-sky-400 rounded-lg hover:bg-sky-50 dark:hover:bg-slate-700 mt-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    Obaveštenja
                    @php
                        $unreadNotificationsCount = \App\Models\Message::where('receiver_id', auth()->id())
                            ->where('is_read', false)
                            ->where('is_system_message', true)
                            ->count();
                    @endphp
                    @if ($unreadNotificationsCount > 0)
                        <span class="ml-2 bg-red-600 text-white rounded px-2 py-1 text-xs font-medium">
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
                </a>
            </div>
        @else
            <!-- Regular User Sidebar -->
            <div class="border-t mt-4 pt-4 p-2">
                <div class="px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                    Brze akcije</div>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2 text-red-500 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('listings.create') }}"
                    class="flex items-center px-3 py-2 text-green-600 dark:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-slate-700 mt-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    Dodaj oglas
                </a>

                <a href="{{ route('listings.my') }}"
                    class="flex items-center px-3 py-2 text-sky-600 dark:text-sky-400 rounded-lg hover:bg-sky-50 dark:hover:bg-slate-700 mt-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Moji oglasi
                </a>

                <!-- U sidebar.blade.php -->
                <a href="{{ route('messages.inbox') }}"
                    class="flex items-center px-3 py-2 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-slate-700 mt-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                    Moje poruke
                    @auth
                        @php
                            $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->where('is_system_message', false)
                                ->count();
                        @endphp
                        @if ($unreadMessagesCount > 0)
                            <span class="ml-2 bg-red-500 text-white rounded px-2 py-1 text-xs font-medium">
                                {{ $unreadMessagesCount }}
                            </span>
                        @endif
                    @endauth
                </a>

                <a href="{{ route('notifications.index') }}"
                    class="flex items-center px-3 py-2 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-slate-700 mt-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    Obaveštenja
                    @auth
                        @php
                            // Samo obaveštenja
                            $unreadNotificationsCount = \App\Models\Message::where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->where('is_system_message', true)
                                ->count();
                        @endphp
                        @if ($unreadNotificationsCount > 0)
                            <span class="ml-2 bg-red-500 text-white rounded px-2 py-1 text-xs font-medium">
                                {{ $unreadNotificationsCount }}
                            </span>
                        @endif
                    @endauth
                </a>

            </div>
        @endif
    @endauth
</div>

<script>
    // Theme Management
    function setTheme(theme) {
        console.log('Setting theme to:', theme);

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            console.log('Dark mode activated');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            console.log('Light mode activated');
        }
        updateThemeButtons();
    }

    function updateThemeButtons() {
        const isDark = document.documentElement.classList.contains('dark');
        const lightBtn = document.querySelector('.light-theme');
        const darkBtn = document.querySelector('.dark-theme');

        if (lightBtn && darkBtn) {
            if (isDark) {
                lightBtn.classList.remove('ring-2', 'ring-sky-500', 'bg-sky-50');
                darkBtn.classList.add('ring-2', 'ring-sky-400', 'bg-slate-700');
            } else {
                darkBtn.classList.remove('ring-2', 'ring-sky-400', 'bg-slate-700');
                lightBtn.classList.add('ring-2', 'ring-sky-500', 'bg-sky-50');
            }
        }
    }

    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            setTheme('dark');
        } else {
            setTheme('light');
        }
    });
</script>
