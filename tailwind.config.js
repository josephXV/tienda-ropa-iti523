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
                    DEFAULT: '#1F5C4F',
                    dark: '#153F37',
                    light: '#2E7566',
                },
                gold: {
                    DEFAULT: '#C9A227',
                    light: '#E4C662',
                },
                cream: '#F8F4EC',
                card: '#FFFFFF',
            },
        },
    },

    plugins: [forms],
};
