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
        // host: "192.168.43.98",
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            // host: "192.168.1.3",
            // host: "192.168.43.98",
            host: "40.82.178.113",
        },
    },
});
