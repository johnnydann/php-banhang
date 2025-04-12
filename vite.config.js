import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'


export default defineConfig({
    plugins: [
        tailwindcss(), // Tailwind CSS plugin
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'], // Input files for Vite
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [tailwindcss] // Ensure Tailwind is properly processed
        }
    }
});

