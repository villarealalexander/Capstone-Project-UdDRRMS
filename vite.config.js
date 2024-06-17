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
        host: '192.168.100.56', // Listen on all network interfaces
        port: 3000, // Change this to your desired port if needed
        hmr: {
            host: '192.168.100.56', // Connect to the specified IP address
            port:3000,
    },
},
});
