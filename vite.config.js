import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    server: {
        host: '0.0.0.0',
        port: 8000, // ili ostavi 5173 ako ga koristiš
        hmr: {
          host: '192.168.1.21', // <<< obavezno tvoja IP adresa
        },
      },
});