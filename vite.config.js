import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],

            // Ganti refresh: true dengan spesifik folder saja
            refresh: [
                'resources/views/**',
                'routes/**',
            ],
        }),
        tailwindcss(),
    ],

    server: {
        // Kurangi beban Vite saat development
        hmr: {
            host: 'localhost',
        },
    },
});