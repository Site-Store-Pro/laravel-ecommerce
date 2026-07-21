import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/tinymce',
                    dest: ''
                },
                {
                    src: 'resources/js/tinymce/plugins/supercode/*',
                    dest: 'node_modules/tinymce/plugins/supercode',
                    rename: { stripBase: true }
                }
            ]
        })
    ],
});
