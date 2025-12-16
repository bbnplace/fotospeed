import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/themes/fotospeed/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '192.168.1.106',
        https: {
            key: fs.readFileSync('certs/server.key'),
            cert: fs.readFileSync('certs/server.crt'),
        },
        cors: true,
        hmr: {
            host: '192.168.1.106',
        },
    },
});
