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
                sans: ['"Work Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Fraunces"', ...defaultTheme.fontFamily.serif],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                ink: '#182430',
                forest: {
                    DEFAULT: '#5f2568',
                    dark: '#3f1846',
                    light: '#9b6c9a',
                },
                gold: {
                    DEFAULT: '#87588e',
                    light: '#b37eb5',
                },
                cream: '#F8F4EC',
                card: '#FFFFFF',
            },
        },
    },

    plugins: [forms],
};
