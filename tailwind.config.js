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
        // Ensure sky colors work in dark mode
        'dark:bg-sky-500',
        'dark:bg-sky-600',
        'dark:bg-sky-700',
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
