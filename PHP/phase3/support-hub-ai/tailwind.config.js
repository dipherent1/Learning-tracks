import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '1.5rem',
                lg: '2rem',
            },
        },

        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                primary: {
                    50: '#f5f7ff',
                    100: '#ecefff',
                    200: '#c7d0ff',
                    300: '#9baeff',
                    400: '#6f8bff',
                    500: '#4a6bff',
                    600: '#3f57e6',
                    700: '#3346b4',
                    800: '#273285',
                    900: '#1a2056',
                },

                muted: {
                    50: '#fbfbfc',
                    100: '#f5f6f8',
                    200: '#eff1f4',
                    300: '#e6e9ee',
                    400: '#d1d6df',
                    500: '#afb6c2',
                },
            },

            fontSize: {
                '2xs': ['0.625rem', { lineHeight: '0.9rem' }],
                xs: ['0.75rem', { lineHeight: '1rem' }],
                sm: ['0.875rem', { lineHeight: '1.25rem' }],
                base: ['1rem', { lineHeight: '1.5rem' }],
                lg: ['1.125rem', { lineHeight: '1.75rem' }],
                xl: ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
            },
        },
    },

    plugins: [forms, typography],
};
