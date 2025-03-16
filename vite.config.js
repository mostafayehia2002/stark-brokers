import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/main.jsx'],
            refresh: true,
        }),
        react()
    ],
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx']
    },
    server: {
        hmr: {
            host: 'localhost',
        },
        https: false,
        proxy: {
            '/api/v1': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: true,
            }
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Example: Create a separate chunk for vendor libraries
                    vendor: ['react', 'react-dom'],
                },
            },
        },
        chunkSizeWarningLimit: 1000, // Increase the warning limit to 1MB
    },
});
