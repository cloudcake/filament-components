import {defineConfig} from 'vite';

export default defineConfig({
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            input: [
                'resources/css/icon-picker.css',
                'resources/css/mermaid.css',
                'resources/js/icon-picker.js',
                'resources/js/svg-pan-zoom.js',
            ],
            output: {
                entryFileNames: 'js/[name].js',
                assetFileNames: 'css/[name].[ext]',
            },
        },
    },
});