<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@livewireStyles

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1869044751556706"
        crossorigin="anonymous"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        @auth
            @php
                $unreadNotificationsCount = \App\Models\Message::where('receiver_id', auth()->id())
                    ->where('is_system_message', true)
                    ->where('is_read', false)
                    ->count();
            @endphp
            <script>
                window.unreadNotificationsCount = {{ $unreadNotificationsCount }};
            </script>
        @endauth

        <!-- Mobile Sidebar Toggle Button -->
        <div class="md:hidden fixed top-20 left-2 z-40">
            <button type="button" id="mobile-sidebar-button"
                class="bg-white p-2 rounded-md shadow-lg border border-gray-300 text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span class="sr-only">Open sidebar</span>
                <!-- Hamburger icon -->
                <svg id="sidebar-menu-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <!-- X icon (hidden by default) -->
                <svg id="sidebar-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-30 md:hidden hidden"></div>

        <!-- Main Content with Sidebar -->
        <div class="flex">
            <!-- Desktop Sidebar -->
            <aside class="hidden md:block w-64 bg-white shadow-md sticky top-16 h-screen">
                <livewire:category-sidebar />
            </aside>

            <!-- Mobile Sidebar -->
            <aside id="mobile-sidebar"
                class="fixed left-0 top-16 w-64 bg-white shadow-lg h-screen z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
                <livewire:category-sidebar />
            </aside>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Footer -->
    <livewire:layout.footer />
    @livewireScripts

    <!-- Mobile Sidebar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarButton = document.getElementById('mobile-sidebar-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const sidebarOverlay = document.getElementById('mobile-sidebar-overlay');
            const menuIcon = document.getElementById('sidebar-menu-icon');
            const closeIcon = document.getElementById('sidebar-close-icon');

            if (sidebarButton && mobileSidebar && sidebarOverlay) {
                // Toggle sidebar
                sidebarButton.addEventListener('click', function() {
                    const isOpen = !mobileSidebar.classList.contains('-translate-x-full');

                    if (isOpen) {
                        // Close sidebar
                        mobileSidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    } else {
                        // Open sidebar
                        mobileSidebar.classList.remove('-translate-x-full');
                        sidebarOverlay.classList.remove('hidden');
                        menuIcon.classList.add('hidden');
                        closeIcon.classList.remove('hidden');
                    }
                });

                // Close sidebar when clicking overlay
                sidebarOverlay.addEventListener('click', function() {
                    mobileSidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                });

                // Close sidebar when clicking on links
                const sidebarLinks = mobileSidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileSidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</body>

</html>
