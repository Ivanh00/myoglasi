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
    
    <!-- Global Dark Mode Styles for Custom Components -->
    <style>
        /* Dark mode for notification modal only - make it transparent */
        .dark .notification-modal {
            background-color: transparent !important;
            border-color: transparent !important;
        }
        .dark .notification-modal .modal-content {
            background-color: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
        }
        
        /* Specific fixes for notification and conversation windows */
        .dark .notification-popup-header, .dark .conversation-header {
            background-color: rgb(31 41 55) !important; /* gray-800 */
            color: rgb(229 231 235) !important;
            border-bottom: 1px solid rgb(75 85 99) !important;
        }
        .dark .notification-popup, .dark .conversation-window {
            background-color: rgb(17 24 39) !important; /* gray-900 */
        }
        .dark .notification-cards, .dark .info-cards {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            border: 1px solid rgb(75 85 99) !important;
        }
        .dark .message-input-area {
            background-color: rgb(31 41 55) !important; /* gray-800 */
            border-top: 1px solid rgb(75 85 99) !important;
        }
        
        /* Override white backgrounds only in notification modal */
        .dark .notification-modal [class*="bg-white"], 
        .dark .notification-modal div[style*="background: white"], 
        .dark .notification-modal div[style*="background-color: white"],
        .dark .notification-modal div[style*="background:#fff"], 
        .dark .notification-modal div[style*="background-color:#fff"],
        .dark .notification-modal div[style*="background: #fff"], 
        .dark .notification-modal div[style*="background-color: #ffffff"] {
            background-color: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
        }
        
        /* Force dark mode on notification modal content only */
        .dark .notification-modal .modal-dialog, 
        .dark .notification-modal .modal-body,
        .dark .notification-modal[role="dialog"], 
        .dark .notification-modal[role="alertdialog"] {
            background-color: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
        }
        
        /* Dark mode for notification modal parts only */
        .dark .notification-modal .modal-header, 
        .dark .notification-modal .modal-footer,
        .dark .notification-modal .modal-body {
            background-color: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
            border-color: rgb(75 85 99) !important;
        }
        
        /* Message and admin contact specific */
        .dark .chat-window, .dark .conversation-view, .dark .admin-chat {
            background-color: rgb(17 24 39) !important; /* gray-900 */
        }
        
        /* Info sections in popups */
        .dark .listing-info, .dark .user-info-section, .dark .notification-details {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            border-color: rgb(75 85 99) !important;
        }
        
        /* Target specific notification popup elements only */
        .dark .notification-modal .modal-content, 
        .dark .notification-modal .modal-header, 
        .dark .notification-modal .modal-body, 
        .dark .notification-modal .modal-footer {
            background-color: rgb(31 41 55) !important; /* gray-800 */
            color: rgb(229 231 235) !important;
            border-color: rgb(75 85 99) !important;
        }
        
        .dark .notification-modal .notification-details {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            color: rgb(229 231 235) !important;
            border: 1px solid rgb(75 85 99) !important;
            border-radius: 0.5rem !important;
            padding: 1rem !important;
            margin: 0.5rem 0 !important;
        }
        
        .dark .notification-modal .notification-listing-info, 
        .dark .notification-modal .notification-user-info, 
        .dark .notification-modal .notification-time-info {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            color: rgb(229 231 235) !important;
            border: 1px solid rgb(75 85 99) !important;
            border-left: 4px solid rgb(59 130 246) !important; /* blue-500 left border */
            border-radius: 0.5rem !important;
            padding: 1rem !important;
            margin: 0.5rem 0 !important;
        }
        
        .dark .notification-modal .listing-link {
            color: rgb(59 130 246) !important; /* blue-500 - brighter blue */
        }
        
        .dark .notification-modal .view-listing-btn {
            background-color: rgb(37 99 235) !important; /* blue-600 */
            color: white !important;
        }
        
        /* Specific red auction button in notification popup only */
        .dark .notification-modal .modal-footer a[href*="auction"], 
        .dark .notification-modal .modal-body a[href*="auction"] {
            background-color: rgb(220 38 38) !important; /* red-600 */
            color: white !important;
        }
        
        .dark .notification-modal .close-btn {
            background-color: rgb(75 85 99) !important; /* gray-600 */
            color: rgb(229 231 235) !important;
        }
        .dark .notification-modal .close-btn:hover {
            background-color: rgb(107 114 128) !important; /* gray-500 */
        }
        
        /* Specific overrides for white cards only in notification modal */
        .dark .notification-modal div[class*="bg-white"],
        .dark .notification-modal div[class*="rounded"] {
            background-color: rgb(55 65 81) !important;
            color: rgb(229 231 235) !important;
            border-color: rgb(75 85 99) !important;
        }
        
        /* Headers and titles only in notification modal */
        .dark .notification-modal h1, .dark .notification-modal h2, .dark .notification-modal h3,
        .dark .notification-modal h4, .dark .notification-modal h5, .dark .notification-modal h6 {
            color: rgb(229 231 235) !important;
        }
        
        /* Buttons only in notification modal */
        .dark .notification-modal button[class*="bg-gray"],
        .dark .notification-modal .btn-secondary {
            background-color: rgb(75 85 99) !important;
            color: rgb(229 231 235) !important;
            border-color: rgb(107 114 128) !important;
        }
        
        /* Force text to be light only in notification modal - exclude overlay */
        .dark .notification-modal *:not(.modal-overlay) {
            color: rgb(229 231 235) !important;
        }
        
        /* Ensure modal overlay remains transparent in dark mode */
        .dark .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        
        
        /* Preserve colored text only in notification modal */
        .dark .notification-modal .text-blue-600, 
        .dark .notification-modal .text-blue-700, 
        .dark .notification-modal .text-blue-800 {
            color: rgb(147 197 253) !important; /* blue-300 */
        }
        .dark .notification-modal .text-green-600, 
        .dark .notification-modal .text-green-700 {
            color: rgb(134 239 172) !important; /* green-300 */
        }
        .dark .notification-modal .text-red-600, 
        .dark .notification-modal .text-red-700 {
            color: rgb(252 165 165) !important; /* red-300 */
        }
        .dark .notification-modal .text-yellow-600, 
        .dark .notification-modal .text-yellow-700 {
            color: rgb(253 224 71) !important; /* yellow-300 */
        }
        
        /* Close button styling only in notification modal */
        .dark .notification-modal button[class*="bg-gray-"], 
        .dark .notification-modal .btn-cancel, 
        .dark .notification-modal .btn-close {
            background-color: rgb(75 85 99) !important; /* gray-600 */
            color: rgb(229 231 235) !important;
            border: 1px solid rgb(107 114 128) !important; /* gray-500 */
        }
        .dark .notification-modal button[class*="bg-gray-"]:hover, 
        .dark .notification-modal .btn-cancel:hover, 
        .dark .notification-modal .btn-close:hover {
            background-color: rgb(107 114 128) !important; /* gray-500 - lighter on hover */
            color: rgb(255 255 255) !important;
        }
        
        /* X close button specific only in notification modal */
        .dark .notification-modal .close-button, 
        .dark .notification-modal button[type="button"]:not([class*="bg-blue"]):not([class*="bg-red"]):not([class*="bg-green"]):not([class*="bg-yellow"]) {
            background-color: rgb(75 85 99) !important;
            color: rgb(229 231 235) !important;
        }
        .dark .notification-modal .close-button:hover {
            background-color: rgb(107 114 128) !important;
            color: rgb(255 255 255) !important;
        }
    </style>

    <!-- Dark Mode Script (must be in head to prevent flash) -->
    <script>
        // Check for saved theme preference or default to system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1869044751556706"
        crossorigin="anonymous"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
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
            <aside class="hidden md:block w-64 bg-white dark:bg-gray-800 shadow-md sticky top-16 h-screen">
                <livewire:category-sidebar />
            </aside>

            <!-- Mobile Sidebar -->
            <aside id="mobile-sidebar"
                class="fixed left-0 top-16 w-64 bg-white dark:bg-gray-800 shadow-lg h-screen z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
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
