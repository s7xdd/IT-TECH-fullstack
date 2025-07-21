import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css',
                // 'resources/js/app.js',
                "dist/assets/app-198d7db2.css",
                "dist/assets/app-afc193cb.css",
                "dist/assets/app-cb80af6a.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: "dist",
        emptyOutDir: true,
    },
});
