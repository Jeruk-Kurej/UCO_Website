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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // UCO Orange Palette (Primary)
                'uco-orange': {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f7931e',  // Main UCO Orange
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                    950: '#431407',
                },
                // UCO Yellow Palette (Secondary)
                'uco-yellow': {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#fdb913',  // Main UCO Yellow
                    600: '#ca8a04',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                    950: '#422006',
                },
                // UCO Accent Colors
                'uco-coral': {
                    500: '#ff6b35',
                    600: '#ff5722',
                },
            },
            backgroundImage: {
                // Gradient presets untuk UCO
                'uco-gradient': 'linear-gradient(135deg, #f7931e 0%, #fdb913 100%)',
                'uco-gradient-vertical': 'linear-gradient(to bottom, #f7931e 0%, #fdb913 100%)',
                'uco-gradient-dark': 'linear-gradient(135deg, #ea580c 0%, #ca8a04 100%)',
            },
        },
    },

    plugins: [forms],
};
