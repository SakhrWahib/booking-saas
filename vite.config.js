import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // تحسين حجم الملفات
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        // تقسيم الملفات لتحسين التحميل
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['chart.js'],
                },
            },
        },
        // تحسين الأداء
        target: 'es2015',
        cssCodeSplit: true,
        sourcemap: false,
    },
    // تحسين سرعة التطوير
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    // تحسين الأداء
    optimizeDeps: {
        include: ['chart.js'],
    },
});
