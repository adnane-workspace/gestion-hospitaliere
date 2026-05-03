import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                premium: {
                    50: '#f0f4ff',
                    100: '#e0e9fe',
                    200: '#c1d2fe',
                    300: '#91affd',
                    400: '#5a82fa',
                    500: '#3252f5',
                    600: '#2136e9',
                    700: '#1a29d5',
                    800: '#1b23ad',
                    900: '#1b2389',
                    950: '#151a54',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
        },
    },

    plugins: [forms],
};
