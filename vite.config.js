import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Grafik statistik (React + shadcn/ui charts + Recharts)
                'resources/css/statistik.css',
                'resources/js/statistik.jsx',
            ],
            refresh: true,
        }),
        tailwindcss(),
        react(),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
});
