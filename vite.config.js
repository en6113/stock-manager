import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/menus/create.js',
                'resources/js/pages/menus/edit.js',
                'resources/js/pages/meal-plans/index.js',
            ],
            refresh: true,
        }),
    ],
});
