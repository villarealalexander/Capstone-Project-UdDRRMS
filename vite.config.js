import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

//     server: {
//         host: '10.10.0.127', 
//         port: 3000, 
//         hmr: {
//             host: '10.10.0.127',
//             port:3000,
//     },
// },

});
