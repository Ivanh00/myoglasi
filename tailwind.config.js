import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Enable class-based dark mode

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    safelist: [
        // Dark mode badge colors
        'dark:bg-sky-700',
        'dark:bg-amber-600',
        'dark:bg-red-800',
        'dark:bg-green-800',
        'dark:bg-purple-800',
        'dark:bg-orange-800',
        'dark:bg-slate-500',
        'dark:bg-slate-800',
        'dark:text-sky-100',
        'dark:text-amber-100',
        'dark:text-red-200',
        'dark:text-green-200',
        'dark:text-purple-200',
        'dark:text-orange-200',
        'dark:text-slate-100',
        // Light mode badge colors
        'bg-sky-500',
        'bg-amber-500',
        'bg-red-500',
        'bg-green-500',
        'bg-purple-500',
        'bg-orange-500',
        'bg-slate-200',
        // Ensure sky colors work in dark mode
        'dark:bg-sky-500',
        'dark:bg-sky-600',
        'dark:bg-sky-700',
        'dark:bg-sky-800',
        'dark:hover:bg-sky-600',
        'dark:hover:bg-sky-700',
        'dark:hover:bg-sky-800',
        'dark:text-sky-400',
        'dark:text-sky-300',
        'dark:border-sky-600',
        'dark:ring-sky-500',
    ],

    plugins: [forms],
};
