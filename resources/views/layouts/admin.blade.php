<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@livewireStyles

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Dark Mode Script (must be in head to prevent flash) -->
    <script>
        // Check for saved theme preference or default to system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
    <!-- Admin Panel Dark Mode Styles -->
    <style>
        /* Admin Navigation Bar Dark Mode */
        .dark nav.bg-gray-800 {
            background-color: rgb(17 24 39) !important; /* gray-900 - darker */
            border-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        .dark nav .text-gray-300 {
            color: rgb(209 213 219) !important; /* gray-300 */
        }
        
        .dark nav .text-gray-300:hover {
            color: rgb(255 255 255) !important; /* white */
        }
        
        .dark nav .hover\\:bg-gray-700:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        /* Admin Dropdown Dark Mode */
        .dark nav .bg-white {
            background-color: rgb(31 41 55) !important; /* gray-800 */
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark nav .text-gray-700 {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark nav .hover\\:bg-gray-100:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        /* Mobile User Menu Dark Mode */
        .dark #mobile-admin-user-menu {
            background-color: rgb(31 41 55) !important; /* gray-800 */
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        .dark #mobile-admin-user-menu .text-gray-500 {
            color: rgb(156 163 175) !important; /* gray-400 */
        }
        
        .dark #mobile-admin-user-menu .text-gray-700 {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark #mobile-admin-user-menu .border-gray-200 {
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        .dark #mobile-admin-user-menu .hover\\:bg-gray-100:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        .dark #mobile-admin-user-menu .hover\\:bg-red-50:hover {
            background-color: rgb(127 29 29) !important; /* red-900 */
        }
        
        /* Admin Sidebar Dark Mode */
        .dark aside.bg-white {
            background-color: rgb(31 41 55) !important; /* gray-800 */
        }
        
        .dark aside .text-gray-700 {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark aside .hover\\:bg-gray-100:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        /* Better hover effects for sidebar links */
        .dark aside a:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        /* Non-active sidebar link colors */
        .dark aside a:not(.bg-blue-50) {
            color: rgb(209 213 219) !important; /* gray-300 */
        }
        
        /* Active sidebar item dark mode */
        .dark aside .bg-blue-50 {
            background-color: rgb(30 58 138) !important; /* blue-900 */
        }
        
        .dark aside .text-blue-700 {
            color: rgb(147 197 253) !important; /* blue-300 */
        }
        
        /* Unread messages and notifications styling - same as user pages */
        .dark .bg-blue-50 {
            background-color: rgb(30 58 138) !important; /* blue-900 - dark blue for unread */
        }
        
        .dark .text-blue-700 {
            color: rgb(147 197 253) !important; /* blue-300 - light blue text */
        }
        
        .dark .text-blue-600 {
            color: rgb(147 197 253) !important; /* blue-300 - consistent blue links */
        }
        
        /* Main content area dark mode */
        .dark main {
            background-color: rgb(17 24 39) !important; /* gray-900 */
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        /* Admin notification cards dark mode */
        .dark .bg-green-500, .dark .bg-red-500, .dark .bg-blue-500, .dark .bg-gray-500 {
            /* Keep notification colors as they are for visibility */
        }
        
        /* General Admin Content Dark Mode Styles */
        .dark .bg-white {
            background-color: rgb(31 41 55) !important; /* gray-800 */
        }
        
        .dark .text-gray-900 {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark .text-gray-800 {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark .text-gray-700 {
            color: rgb(209 213 219) !important; /* gray-300 */
        }
        
        .dark .text-gray-600 {
            color: rgb(156 163 175) !important; /* gray-400 */
        }
        
        .dark .text-gray-500 {
            color: rgb(156 163 175) !important; /* gray-400 */
        }
        
        .dark .border-gray-200 {
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        .dark .border-gray-300 {
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        .dark .bg-gray-50 {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        .dark .bg-gray-100 {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        .dark .hover\\:bg-gray-50:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        .dark .hover\\:bg-gray-100:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        /* Table row hover effects - better styling */
        .dark tr.hover\\:bg-gray-50:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 - consistent hover */
        }
        
        .dark tbody tr:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 - consistent hover */
        }
        
        /* Table dividers dark mode */
        .dark .divide-y > * + * {
            border-top-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        .dark .divide-gray-200 > * + * {
            border-top-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        /* Admin buttons dark mode */
        .dark .bg-blue-600 {
            background-color: rgb(37 99 235) !important; /* blue-600 */
        }
        
        .dark .hover\\:bg-blue-700:hover {
            background-color: rgb(29 78 216) !important; /* blue-700 */
        }
        
        .dark .bg-red-600 {
            background-color: rgb(220 38 38) !important; /* red-600 */
        }
        
        .dark .hover\\:bg-red-700:hover {
            background-color: rgb(185 28 28) !important; /* red-700 */
        }
        
        .dark .bg-green-600 {
            background-color: rgb(22 163 74) !important; /* green-600 */
        }
        
        .dark .hover\\:bg-green-700:hover {
            background-color: rgb(21 128 61) !important; /* green-700 */
        }
        
        .dark .bg-yellow-600 {
            background-color: rgb(202 138 4) !important; /* yellow-600 */
        }
        
        .dark .hover\\:bg-yellow-700:hover {
            background-color: rgb(161 98 7) !important; /* yellow-700 */
        }
        
        .dark .bg-purple-600 {
            background-color: rgb(147 51 234) !important; /* purple-600 */
        }
        
        .dark .hover\\:bg-purple-700:hover {
            background-color: rgb(126 34 206) !important; /* purple-700 */
        }
        
        /* Small buttons and links hover effects */
        .dark .hover\\:bg-gray-50:hover {
            background-color: rgb(55 65 81) !important; /* gray-700 */
        }
        
        .dark .hover\\:bg-red-50:hover {
            background-color: rgb(127 29 29) !important; /* red-900 */
        }
        
        .dark .hover\\:text-gray-800:hover {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark .hover\\:text-gray-700:hover {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        /* Action button text colors - same as user pages */
        .dark .text-blue-600 {
            color: rgb(96 165 250) !important; /* blue-400 */
        }
        
        .dark .text-blue-400 {
            color: rgb(96 165 250) !important; /* blue-400 */
        }
        
        /* Tailwind hover classes for action buttons */
        .dark .text-blue-600.hover\\:text-blue-900:hover {
            color: rgb(191 219 254) !important; /* blue-200 - lighter */
        }
        
        .dark .text-red-600.hover\\:text-red-900:hover {
            color: rgb(254 202 202) !important; /* red-200 - lighter */
        }
        
        .dark .text-green-600.hover\\:text-green-900:hover {
            color: rgb(187 247 208) !important; /* green-200 - lighter */
        }
        
        .dark .text-orange-600.hover\\:text-orange-900:hover {
            color: rgb(254 215 170) !important; /* orange-200 - lighter */
        }
        
        .dark .text-yellow-600.hover\\:text-yellow-900:hover {
            color: rgb(254 240 138) !important; /* yellow-200 - lighter */
        }
        
        .dark .text-purple-600.hover\\:text-purple-900:hover {
            color: rgb(221 214 254) !important; /* purple-200 - lighter */
        }
        
        .dark .text-indigo-600.hover\\:text-indigo-900:hover {
            color: rgb(199 210 254) !important; /* indigo-200 - lighter */
        }
        
        /* Green elements in earning credit section */
        .dark .text-green-800 {
            color: rgb(134 239 172) !important; /* green-300 - readable green */
        }
        
        .dark .bg-green-100 {
            background-color: rgb(20 83 45) !important; /* green-900 - dark green background */
        }
        
        .dark .bg-green-50 {
            background-color: rgb(20 83 45) !important; /* green-900 - dark green background for sections */
        }
        
        .dark .border-green-200 {
            border-color: rgb(34 197 94) !important; /* green-500 - visible green border */
        }
        
        .dark .border-green-300 {
            border-color: rgb(34 197 94) !important; /* green-500 - visible green border */
        }
        
        .dark .text-green-600 {
            color: rgb(134 239 172) !important; /* green-300 - readable green text */
        }
        
        /* Focus ring colors for green elements */
        .dark input:focus.focus\\:ring-green-500 {
            --tw-ring-color: rgb(34 197 94) !important; /* green-500 */
        }
        
        .dark .focus\\:border-green-500:focus {
            border-color: rgb(34 197 94) !important; /* green-500 */
        }
        
        .dark .hover\\:text-blue-800:hover, .dark .hover\\:text-blue-300:hover {
            color: rgb(191 219 254) !important; /* blue-200 - even lighter on hover */
        }
        
        .dark .text-indigo-600 {
            color: rgb(129 140 248) !important; /* indigo-400 */
        }
        
        .dark .text-indigo-400 {
            color: rgb(129 140 248) !important; /* indigo-400 */
        }
        
        .dark .hover\\:text-indigo-800:hover, .dark .hover\\:text-indigo-300:hover {
            color: rgb(199 210 254) !important; /* indigo-200 - even lighter on hover */
        }
        
        .dark .text-green-600 {
            color: rgb(74 222 128) !important; /* green-400 */
        }
        
        .dark .text-green-400 {
            color: rgb(74 222 128) !important; /* green-400 */
        }
        
        .dark .hover\\:text-green-800:hover, .dark .hover\\:text-green-300:hover {
            color: rgb(187 247 208) !important; /* green-200 - even lighter on hover */
        }
        
        .dark .text-orange-600 {
            color: rgb(251 146 60) !important; /* orange-400 */
        }
        
        .dark .text-orange-400 {
            color: rgb(251 146 60) !important; /* orange-400 */
        }
        
        .dark .hover\\:text-orange-800:hover, .dark .hover\\:text-orange-300:hover {
            color: rgb(254 215 170) !important; /* orange-200 - even lighter on hover */
        }
        
        .dark .text-yellow-600 {
            color: rgb(250 204 21) !important; /* yellow-400 */
        }
        
        .dark .text-yellow-400 {
            color: rgb(250 204 21) !important; /* yellow-400 */
        }
        
        .dark .hover\\:text-yellow-800:hover, .dark .hover\\:text-yellow-300:hover {
            color: rgb(254 240 138) !important; /* yellow-200 - even lighter on hover */
        }
        
        .dark .text-red-600 {
            color: rgb(248 113 113) !important; /* red-400 */
        }
        
        .dark .text-red-400 {
            color: rgb(248 113 113) !important; /* red-400 */
        }
        
        .dark .hover\\:text-red-800:hover, .dark .hover\\:text-red-300:hover {
            color: rgb(254 202 202) !important; /* red-200 - even lighter on hover */
        }
        
        .dark .text-purple-600 {
            color: rgb(168 85 247) !important; /* purple-400 */
        }
        
        .dark .text-purple-400 {
            color: rgb(168 85 247) !important; /* purple-400 */
        }
        
        .dark .hover\\:text-purple-800:hover, .dark .hover\\:text-purple-300:hover {
            color: rgb(221 214 254) !important; /* purple-200 - even lighter on hover */
        }
        
        /* Admin action buttons use *-900 hover classes - make them lighter */
        .dark .hover\\:text-blue-900:hover {
            color: rgb(191 219 254) !important; /* blue-200 - lighter on hover */
        }
        
        .dark .hover\\:text-red-900:hover {
            color: rgb(254 202 202) !important; /* red-200 - lighter on hover */
        }
        
        .dark .hover\\:text-green-900:hover {
            color: rgb(187 247 208) !important; /* green-200 - lighter on hover */
        }
        
        .dark .hover\\:text-orange-900:hover {
            color: rgb(254 215 170) !important; /* orange-200 - lighter on hover */
        }
        
        .dark .hover\\:text-yellow-900:hover {
            color: rgb(254 240 138) !important; /* yellow-200 - lighter on hover */
        }
        
        .dark .hover\\:text-purple-900:hover {
            color: rgb(221 214 254) !important; /* purple-200 - lighter on hover */
        }
        
        .dark .hover\\:text-indigo-900:hover {
            color: rgb(199 210 254) !important; /* indigo-200 - lighter on hover */
        }
        
        /* Force hover effects for admin action buttons with maximum specificity */
        .dark button.text-blue-600:hover,
        .dark a.text-blue-600:hover,
        .dark .text-blue-600:hover {
            color: rgb(191 219 254) !important; /* blue-200 */
        }
        
        .dark button.text-red-600:hover,
        .dark a.text-red-600:hover,
        .dark .text-red-600:hover {
            color: rgb(254 202 202) !important; /* red-200 */
        }
        
        .dark button.text-green-600:hover,
        .dark a.text-green-600:hover,
        .dark .text-green-600:hover {
            color: rgb(187 247 208) !important; /* green-200 */
        }
        
        .dark button.text-orange-600:hover,
        .dark a.text-orange-600:hover,
        .dark .text-orange-600:hover {
            color: rgb(254 215 170) !important; /* orange-200 */
        }
        
        .dark button.text-yellow-600:hover,
        .dark a.text-yellow-600:hover,
        .dark .text-yellow-600:hover {
            color: rgb(254 240 138) !important; /* yellow-200 */
        }
        
        .dark button.text-purple-600:hover,
        .dark a.text-purple-600:hover,
        .dark .text-purple-600:hover {
            color: rgb(221 214 254) !important; /* purple-200 */
        }
        
        .dark button.text-indigo-600:hover,
        .dark a.text-indigo-600:hover,
        .dark .text-indigo-600:hover {
            color: rgb(199 210 254) !important; /* indigo-200 */
        }
        
        /* Admin form elements dark mode */
        .dark input[type="text"], 
        .dark input[type="email"], 
        .dark input[type="password"], 
        .dark input[type="number"], 
        .dark textarea, 
        .dark select {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            border-color: rgb(75 85 99) !important; /* gray-600 */
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        
        .dark input:focus, .dark textarea:focus, .dark select:focus {
            border-color: rgb(59 130 246) !important; /* blue-500 */
            box-shadow: 0 0 0 1px rgb(59 130 246) !important;
        }
        
        /* Admin table dark mode */
        .dark table {
            background-color: rgb(31 41 55) !important; /* gray-800 */
        }
        
        .dark th {
            background-color: rgb(55 65 81) !important; /* gray-700 */
            color: rgb(229 231 235) !important; /* gray-200 */
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }
        
        .dark td {
            border-color: rgb(75 85 99) !important; /* gray-600 */
            color: rgb(209 213 219) !important; /* gray-300 */
        }
        
        .dark .bg-yellow-50 {
            background-color: rgb(120 113 108) !important; /* yellow equivalent */
        }
        
        .dark .text-yellow-800 {
            color: rgb(254 240 138) !important; /* yellow-200 */
        }
        
        /* Admin badges and status indicators */
        .dark .bg-red-100 {
            background-color: rgb(127 29 29) !important; /* red-900 */
        }
        
        .dark .text-red-800 {
            color: rgb(252 165 165) !important; /* red-300 */
        }
        
        .dark .bg-green-100 {
            background-color: rgb(20 83 45) !important; /* green-900 */
        }
        
        .dark .text-green-800 {
            color: rgb(134 239 172) !important; /* green-300 */
        }
        
        .dark .bg-blue-100 {
            background-color: rgb(30 58 138) !important; /* blue-900 */
        }
        
        .dark .text-blue-800 {
            color: rgb(147 197 253) !important; /* blue-300 */
        }
        
        .dark .bg-purple-100 {
            background-color: rgb(88 28 135) !important; /* purple-900 */
        }
        
        .dark .text-purple-800 {
            color: rgb(196 181 253) !important; /* purple-300 */
        }
        
        .dark .bg-yellow-100 {
            background-color: rgb(120 113 108) !important; /* yellow equivalent dark */
        }
        
        /* Status badge colors - exactly like user pages */
        .dark .bg-green-200 {
            background-color: rgb(20 83 45) !important; /* green-900 */
        }
        
        .dark .text-green-200 {
            color: rgb(134 239 172) !important; /* green-300 */
        }
        
        .dark .bg-red-200 {
            background-color: rgb(127 29 29) !important; /* red-900 */
        }
        
        .dark .text-red-200 {
            color: rgb(252 165 165) !important; /* red-300 */
        }
        
        .dark .bg-yellow-200 {
            background-color: rgb(120 113 108) !important; /* yellow-900 equivalent */
        }
        
        .dark .text-yellow-200 {
            color: rgb(253 224 71) !important; /* yellow-300 */
        }
        
        /* Additional consistency styles */
        .dark .text-gray-400 {
            color: rgb(156 163 175) !important; /* gray-400 */
        }
        
        .dark .text-gray-300 {
            color: rgb(209 213 219) !important; /* gray-300 */
        }
        
        /* Modal and overlay styles for admin */
        .dark .fixed.inset-0 {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        /* Scrollbar styling for dark mode */
        .dark ::-webkit-scrollbar {
            width: 8px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: rgb(55 65 81); /* gray-700 */
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: rgb(75 85 99); /* gray-600 */
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgb(107 114 128); /* gray-500 */
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Top Navigation -->
        <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Mobile Sidebar Toggle -->
                        <button type="button" id="mobile-admin-sidebar-button"
                            class="md:hidden p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-500 mr-3">
                            <span class="sr-only">Open sidebar</span>
                            <svg id="admin-menu-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="admin-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-4">
                        <!-- Back to site icon -->
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white p-2 rounded-md">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        
                        <!-- Admin dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                @if (auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-500 flex items-center justify-center text-white font-medium text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <svg class="ml-1 h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Odjavi se
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile User Menu -->
                    <div class="md:hidden flex items-center">
                        <button type="button" id="mobile-admin-user-button"
                            class="flex items-center justify-center w-8 h-8 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 border border-gray-600 hover:border-gray-500">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-500 flex items-center justify-center text-white font-medium text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile User Dropdown -->
        <div id="mobile-admin-user-menu" class="md:hidden hidden absolute top-16 right-4 w-48 bg-white rounded-md shadow-lg py-1 z-20 border border-gray-200">
            <div class="px-4 py-2 text-sm text-gray-500 border-b border-gray-200">
                {{ auth()->user()->name }}
            </div>
            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-home mr-2"></i>Vrati se na sajt
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i>Odjavi se
                </button>
            </form>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-admin-sidebar-overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-30 md:hidden hidden"></div>

        <div class="flex">
            <!-- Desktop Sidebar -->
            <aside class="hidden md:block w-64 bg-white dark:bg-gray-800 shadow-sm min-h-screen">
                <div class="p-6">
                    <!-- Theme Switcher -->
                    <div class="mb-6 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tema</div>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="setTheme('light')" 
                                class="flex items-center justify-center px-3 py-2 text-xs font-medium rounded-md transition-colors theme-btn light-theme
                                bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500">
                                <i class="fas fa-sun mr-1"></i>
                                Light
                            </button>
                            <button onclick="setTheme('dark')" 
                                class="flex items-center justify-center px-3 py-2 text-xs font-medium rounded-md transition-colors theme-btn dark-theme
                                bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-moon mr-1"></i>
                                Dark
                            </button>
                        </div>
                    </div>
                    
                    <nav class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5zM8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Korisnici
                        </a>

                        <a href="{{ route('admin.listings.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.listings.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Oglasi
                        </a>

                        <a href="{{ route('admin.auctions.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.auctions.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-gavel w-5 h-5 mr-3"></i>
                            Aukcije
                        </a>

                        <a href="{{ route('admin.transactions.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Transakcije
                        </a>

                        <a href="{{ route('admin.categories.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Kategorije
                        </a>

                        <a href="{{ route('admin.service-categories.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.service-categories.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-tools w-5 h-5 mr-3"></i>
                            Kategorije usluga
                        </a>

                        <a href="{{ route('admin.messages.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.messages.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Poruke
                        </a>

                        <a href="{{ route('admin.notifications.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            Obaveštenja
                        </a>

                        <a href="{{ route('admin.ratings.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.ratings.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Ocene
                        </a>

                        <a href="{{ route('admin.reports.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-flag w-5 h-5 mr-3"></i>
                            Prijave
                        </a>

                        <a href="{{ route('admin.firewall.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.firewall.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-shield-alt w-5 h-5 mr-3"></i>
                            Firewall
                        </a>

                        <a href="{{ route('admin.settings') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.settings') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Podešavanja
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Mobile Sidebar -->
            <aside id="mobile-admin-sidebar" class="fixed left-0 top-16 w-64 bg-white dark:bg-gray-800 shadow-lg min-h-screen z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
                <div class="p-6">
                    <!-- Theme Switcher -->
                    <div class="mb-6 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tema</div>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="setTheme('light')" 
                                class="flex items-center justify-center px-3 py-2 text-xs font-medium rounded-md transition-colors theme-btn light-theme
                                bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500">
                                <i class="fas fa-sun mr-1"></i>
                                Light
                            </button>
                            <button onclick="setTheme('dark')" 
                                class="flex items-center justify-center px-3 py-2 text-xs font-medium rounded-md transition-colors theme-btn dark-theme
                                bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-moon mr-1"></i>
                                Dark
                            </button>
                        </div>
                    </div>
                    
                    <nav class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5zM8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Korisnici
                        </a>

                        <a href="{{ route('admin.listings.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.listings.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Oglasi
                        </a>

                        <a href="{{ route('admin.auctions.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.auctions.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-gavel w-5 h-5 mr-3"></i>
                            Aukcije
                        </a>

                        <a href="{{ route('admin.transactions.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Transakcije
                        </a>

                        <a href="{{ route('admin.categories.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Kategorije
                        </a>

                        <a href="{{ route('admin.service-categories.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.service-categories.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-tools w-5 h-5 mr-3"></i>
                            Kategorije usluga
                        </a>

                        <a href="{{ route('admin.messages.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.messages.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Poruke
                        </a>

                        <a href="{{ route('admin.notifications.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            Obaveštenja
                        </a>

                        <a href="{{ route('admin.ratings.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.ratings.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Ocene
                        </a>

                        <a href="{{ route('admin.reports.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-flag w-5 h-5 mr-3"></i>
                            Prijave
                        </a>

                        <a href="{{ route('admin.firewall.index') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.firewall.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <i class="fas fa-shield-alt w-5 h-5 mr-3"></i>
                            Firewall
                        </a>

                        <a href="{{ route('admin.settings') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.settings') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Podešavanja
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Notification Area -->
    <div id="notification-area" class="fixed top-4 right-4 z-50 space-y-2"></div>

    @livewireScripts
    
    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (data) => {
            console.log('Notification received:', data);
            const notificationArea = document.getElementById('notification-area');
            const type = data.type || 'info';
            const message = data.message || '';
            
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-lg shadow-lg text-white max-w-sm ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'info' ? 'bg-blue-500' :
                'bg-gray-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex justify-between items-start">
                    <p class="text-sm">${message}</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            notificationArea.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        });
    });
    
    // Mobile Admin Sidebar and User Menu JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar functionality
        const sidebarButton = document.getElementById('mobile-admin-sidebar-button');
        const mobileSidebar = document.getElementById('mobile-admin-sidebar');
        const sidebarOverlay = document.getElementById('mobile-admin-sidebar-overlay');
        const menuIcon = document.getElementById('admin-menu-icon');
        const closeIcon = document.getElementById('admin-close-icon');
        
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
        
        // User menu functionality
        const userButton = document.getElementById('mobile-admin-user-button');
        const userMenu = document.getElementById('mobile-admin-user-menu');
        
        if (userButton && userMenu) {
            userButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });
            
            // Close user menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!userButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    });
    
    // Theme Management - same as user pages
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
        
        // Update button states
        updateThemeButtons();
    }
    
    function updateThemeButtons() {
        const isDark = document.documentElement.classList.contains('dark');
        const lightBtn = document.querySelector('.light-theme');
        const darkBtn = document.querySelector('.dark-theme');
        
        if (lightBtn && darkBtn) {
            if (isDark) {
                // Dark mode active
                lightBtn.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                darkBtn.classList.add('ring-2', 'ring-blue-400', 'bg-gray-700');
            } else {
                // Light mode active
                darkBtn.classList.remove('ring-2', 'ring-blue-400', 'bg-gray-700');
                lightBtn.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
            }
        }
    }
    
    // Initialize theme buttons on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateThemeButtons();
    });
    </script>
</body>

</html>