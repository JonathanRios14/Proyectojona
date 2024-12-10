import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],  // Archivos de entrada para Vite
            refresh: true,  // Habilita la recarga automática en el navegador
        }),
    ],
});
