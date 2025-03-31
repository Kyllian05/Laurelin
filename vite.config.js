import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue'; // Import du plugin Vue

export default defineConfig({
    base: '/Laurelin/',
    build: {
        outDir: 'dist'
    },
    plugins: [
        vue()
    ],
});
