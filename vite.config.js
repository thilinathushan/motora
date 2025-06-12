import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), "");

    const config = {
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
    };

    if (command === "serve") {
        config.server = {
            host: env.APP_URL,
            port: 5173,
            strictPort: true,
            hmr: {
                host: env.APP_URL,
            },
        };
    }

    return config;
});
