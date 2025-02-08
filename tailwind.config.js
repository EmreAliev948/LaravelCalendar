import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                "hanken-grotesk": ["Hanken Grotesk", "sans-serif"]
            },
            colors:{
                "black":"#060606"
            },
            fontSize:{
                "2xs":".625rem"
            },
            darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            dark: {
                                100: '#1a1a1a',
                                200: '#2c2c2c',
                                300: '#333333',
                                400: '#444444',
                            },
                        },
                    },
                },
            },
        
    },
    plugins: [],
};
