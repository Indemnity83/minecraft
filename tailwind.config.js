const mix = require('laravel-mix');

module.exports = {
    theme: {
        extend: {
            colors: require('@ky-is/tailwind-color-palette')('#309f70', {grayscale: true, ui: true}),
        },
    },
    variants: {},
    plugins: [
        require('@tailwindcss/ui'),
    ],
    purge: {
        mode: 'all',
        enabled: mix.inProduction(),
        content: [
            './resources/js/**/*.js',
            './resources/js/**/*.vue',
        ],
    },
};
