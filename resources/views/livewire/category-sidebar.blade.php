<div class="w-64 bg-white dark:bg-slate-800 shadow-lg h-screen sticky top-0 overflow-y-auto sidebar-scroll" x-data="{ openSection: null }">
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .sidebar-scroll::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .sidebar-scroll {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Ensure consistent width */
        .sidebar-content {
            padding-right: 0;
        }
    </style>
    @php
        // Load categories at the beginning so they're available for all sections
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
        <div class="mb-2">
            <div class="flex items-center bg-amber-600 rounded-lg {{ request()->routeIs('auctions.index') ? 'bg-amber-700' : '' }}">
                <a href="{{ route('auctions.index') }}"
                    class="flex-1 flex items-center px-4 py-3 text-white hover:bg-amber-700 transition-colors rounded-l-lg">
                    <i class="fas fa-gavel mr-3"></i>
                    Aukcije
                </a>
                <button @click="openSection = openSection === 'auctions' ? null : 'auctions'"
                    class="px-3 py-3 text-white hover:bg-amber-700 transition-colors rounded-r-lg border-l border-amber-700">
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'transform rotate-90': openSection === 'auctions' }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <!-- Auction categories dropdown -->
            <div class="mt-1 overflow-hidden transition-all duration-200"
                x-data="{ openAuctionCategory: null }"
                :class="{ 'max-h-0': openSection !== 'auctions', 'max-h-none': openSection === 'auctions' }">

                @foreach ($categoryTree as $category)
                    <div class="mt-1">
                        <a href="{{ route('auctions.index', ['selectedCategory' => $category->id]) }}"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-amber-50 dark:hover:bg-amber-900/20 {{ request()->get('selectedCategory') == $category->id ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-300' : '' }}">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-amber-600 dark:text-amber-400 mr-2 w-4"></i>
                            @else
                                <i class="fas fa-folder text-amber-600 dark:text-amber-400 mr-2 w-4"></i>
                            @endif
                            <span class="flex-1">{{ $category->name }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-300">
                                @php
                                    $auctionCount = \App\Models\Listing::where('listing_type', 'auction')
                                        ->where('status', 'active')
                                        ->where(function($q) use ($category) {
                                            $categoryIds = $category->getAllCategoryIds();
                                            $q->whereIn('category_id', $categoryIds)
                                              ->orWhereIn('subcategory_id', $categoryIds);
                                        })->count();
                                @endphp
                                ({{ $auctionCount }})
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Usluge -->
        <div class="mb-2">
            <div class="flex items-center services-button rounded-lg {{ request()->routeIs('services.*') ? 'active' : '' }}">
                <a href="{{ route('services.index') }}"
                    class="flex-1 flex items-center px-4 py-3 text-white hover:opacity-90 transition-opacity rounded-l-lg">
                    <i class="fas fa-tools mr-3"></i>
                    Usluge
                </a>
                <button @click="openSection = openSection === 'services' ? null : 'services'"
                    class="px-3 py-3 text-white hover:opacity-90 transition-opacity rounded-r-lg border-l border-slate-700">
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'transform rotate-90': openSection === 'services' }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <!-- Service categories dropdown -->
            <div class="mt-1 overflow-hidden transition-all duration-200"
                x-data="{ openServiceCategory: null }"
                :class="{ 'max-h-0': openSection !== 'services', 'max-h-none': openSection === 'services' }">

                @php
                    $serviceCategories = \App\Models\ServiceCategory::with([
                        'children' => function ($query) {
                            $query->where('is_active', true)->orderBy('sort_order');
                        },
                    ])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
                @endphp

                @foreach ($serviceCategories as $serviceCategory)
                    <div class="mt-1">
                        <a href="{{ route('services.index', ['selectedCategory' => $serviceCategory->id]) }}"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-slate-50 dark:hover:bg-slate-600">
                            @if ($serviceCategory->icon)
                                <i class="{{ $serviceCategory->icon }} text-slate-600 dark:text-slate-400 mr-2 w-4"></i>
                            @else
                                <i class="fas fa-tools text-slate-600 dark:text-slate-400 mr-2 w-4"></i>
                            @endif
                            <span class="flex-1">{{ $serviceCategory->name }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-300">
                                @php
                                    $serviceCount = \App\Models\Service::where('status', 'active')
                                        ->where(function($q) use ($serviceCategory) {
                                            $categoryIds = [$serviceCategory->id];
                                            if($serviceCategory->children) {
                                                $categoryIds = array_merge($categoryIds, $serviceCategory->children->pluck('id')->toArray());
                                            }
                                            $q->whereIn('service_category_id', $categoryIds)
                                              ->orWhereIn('subcategory_id', $categoryIds);
                                        })->count();
                                @endphp
                                ({{ $serviceCount }})
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Poklanjam -->
        <div class="mb-2">
            <div class="flex items-center bg-green-600 rounded-lg {{ request()->routeIs('giveaways.*') ? 'bg-green-700' : '' }}">
                <a href="{{ route('giveaways.index') }}"
                    class="flex-1 flex items-center px-4 py-3 text-white hover:bg-green-700 transition-colors rounded-l-lg">
                    <i class="fas fa-gift mr-3"></i>
                    Poklanjam
                </a>
                <button @click="openSection = openSection === 'giveaways' ? null : 'giveaways'"
                    class="px-3 py-3 text-white hover:bg-green-700 transition-colors rounded-r-lg border-l border-green-700">
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'transform rotate-90': openSection === 'giveaways' }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <!-- Giveaway categories dropdown -->
            <div class="mt-1 overflow-hidden transition-all duration-200"
                x-data="{ openGiveawayCategory: null }"
                :class="{ 'max-h-0': openSection !== 'giveaways', 'max-h-none': openSection === 'giveaways' }">

                @foreach ($categoryTree as $category)
                    <div class="mt-1">
                        <a href="{{ route('giveaways.index', ['selectedCategory' => $category->id]) }}"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-green-50 dark:hover:bg-green-900/20 {{ request()->get('selectedCategory') == $category->id ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-300' : '' }}">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-green-600 dark:text-green-400 mr-2 w-4"></i>
                            @else
                                <i class="fas fa-folder text-green-600 dark:text-green-400 mr-2 w-4"></i>
                            @endif
                            <span class="flex-1">{{ $category->name }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-300">
                                @php
                                    $giveawayCount = \App\Models\Listing::where('listing_type', 'giveaway')
                                        ->where('status', 'active')
                                        ->where(function($q) use ($category) {
                                            $categoryIds = $category->getAllCategoryIds();
                                            $q->whereIn('category_id', $categoryIds)
                                              ->orWhereIn('subcategory_id', $categoryIds);
                                        })->count();
                                @endphp
                                ({{ $giveawayCount }})
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Oglasi -->
        <div class="mb-2">
            <div class="flex items-center bg-sky-600 rounded-lg {{ request()->routeIs('listings.index') && !request()->get('selectedCategory') ? 'bg-sky-700' : '' }}">
                <a href="{{ route('listings.index') }}"
                    class="flex-1 flex items-center px-4 py-3 text-white hover:bg-sky-700 transition-colors rounded-l-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14-8H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
                    </svg>
                    Oglasi
                </a>
                <button @click="openSection = openSection === 'listings' ? null : 'listings'"
                    class="px-3 py-3 text-white hover:bg-sky-700 transition-colors rounded-r-lg border-l border-sky-700">
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'transform rotate-90': openSection === 'listings' }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <!-- Listings categories dropdown -->
            <div class="mt-1 overflow-hidden transition-all duration-200"
                :class="{ 'max-h-0': openSection !== 'listings', 'max-h-none': openSection === 'listings' }">

                @foreach ($categoryTree as $category)
                    <div class="mt-1">
                        <a href="{{ route('listings.index', ['selectedCategory' => $category->id]) }}"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 dark:text-slate-300 rounded hover:bg-sky-50 dark:hover:bg-sky-900/20 {{ request()->get('selectedCategory') == $category->id ? 'bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-300' : '' }}">
                            @if ($category->icon)
                                <i class="{{ $category->icon }} text-sky-600 dark:text-sky-400 mr-2 w-4"></i>
                            @else
                                <i class="fas fa-folder text-sky-600 dark:text-sky-400 mr-2 w-4"></i>
                            @endif
                            <span class="flex-1">{{ $category->name }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-300">
                                (@if (method_exists($category, 'getAllListingsCount'))
                                    {{ $category->getAllListingsCount() }}
                                @else
                                    {{ $category->listings_count ?? 0 }}
                                @endif)
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
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
        // Use global setTheme function from app layout
        if (window.setTheme) {
            window.setTheme(theme);
        } else {
            // Fallback if global function not available yet
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
        updateThemeButtons();
    }

    function updateThemeButtons() {
        const isDark = document.documentElement.classList.contains('dark');
        // Get ALL theme buttons (including mobile sidebar)
        const lightBtns = document.querySelectorAll('.light-theme');
        const darkBtns = document.querySelectorAll('.dark-theme');

        // Update all light buttons
        lightBtns.forEach(lightBtn => {
            // Remove all ring classes first
            lightBtn.classList.remove('ring-2', 'ring-sky-500', 'ring-sky-400', 'ring-sky-600');

            // Apply ring if light mode is active
            if (!isDark) {
                lightBtn.classList.add('ring-2', 'ring-sky-500');
            }
        });

        // Update all dark buttons
        darkBtns.forEach(darkBtn => {
            // Remove all ring classes first
            darkBtn.classList.remove('ring-2', 'ring-sky-500', 'ring-sky-400', 'ring-sky-600');

            // Apply ring if dark mode is active
            if (isDark) {
                darkBtn.classList.add('ring-2', 'ring-sky-400');
            }
        });
    }

    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Just update buttons, theme is already applied by global script
        setTimeout(updateThemeButtons, 10);
    });

    // Also update buttons after Livewire navigation
    document.addEventListener('livewire:navigated', function() {
        setTimeout(updateThemeButtons, 10);
    });

    // Update buttons when theme changes
    window.addEventListener('theme-changed', function() {
        setTimeout(updateThemeButtons, 10);
    });
</script>
