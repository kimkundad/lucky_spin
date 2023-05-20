import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel([
            'resource/scss/app.scss',
            'resources/js/app.js',
        ]),
        vue(),
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '139.59.227.198'
        }
    },
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@': '/resources/js',
        }
    },
});
