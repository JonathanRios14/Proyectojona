import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', // Asegúrate de que los archivos JS también se incluyan si usas clases Tailwind en JS
        './resources/css/**/*.css', // Incluir los archivos CSS si los usas
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'indigo-laravel': '#4F46E5',
                'blue-laravel': '#3B82F6',
                'red-laravel': '#EF4444',
            },
            transitionProperty: {
                'height': 'height',
                'spacing': 'margin, padding',
                'colors': 'color, background-color, border-color, text-decoration-color, fill, stroke',
                'opacity': 'opacity',
                'transform': 'transform',
            },
            transitionDuration: {
                300: '300ms',
            },
            scale: {
                105: '1.05',
            },
        },
    },
    plugins: [forms],  // Si estás utilizando el plugin de formularios de Tailwind
};
