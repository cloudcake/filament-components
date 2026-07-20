import {defineConfig} from 'vite';

export default defineConfig({
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            input: [
                'resources/css/icon-picker.css',
                'resources/js/icon-picker.js',
            ],
            output: {
                entryFileNames: 'js/[name].js',
                assetFileNames: 'css/[name].[ext]',
            },
        },
    },
});