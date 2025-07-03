import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Đảm bảo file này được bao gồm
                'resources/js/app.js'    // Đảm bảo file này được bao gồm
            ],
            refresh: true,
        }),
    ],
});
