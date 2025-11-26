import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      host: 'localhost',
      port: 5173,
    },
    watch: {
      usePolling: true,
    },
  },
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',
        'resources/js/admin/main.js',   //Admin SPA용       
      ],
      refresh: true,
    }),
    vue(),
  ],
})
