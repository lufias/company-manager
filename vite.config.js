import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ command }) => {
    const config = {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
    };

    // Development server configuration
    if (command === 'serve') {
        config.server = {
            host: '0.0.0.0',
            port: 5173,
            hmr: {
                host: 'localhost',
            },
        };
    }

    return config;
});
