import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/themes/fotospeed/app.js',
            refresh: true,
            buildDirectory: 'build/showroom',
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
    build: {
        commonjsOptions: {
            transformMixedEsModules: true,
        },
    },
    optimizeDeps: {
        include: ['jquery'],
    },
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
