import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // "resources/css/app.css",
                "resources/scss/colors.scss",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            host: process.env.HMR_HOST || "localhost",
        },
    },
});
