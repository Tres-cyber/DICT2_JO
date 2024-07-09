import { defineConfig, splitVendorChunkPlugin } from "vite";
import liveReload from "vite-plugin-live-reload";
import path from "node:path";

export default defineConfig({
  plugins: [
    liveReload([__dirname + "/../public/**/*.{css,php,html,js,twig}"]),
    splitVendorChunkPlugin(),
  ],

  root: "src",
  base: process.env.APP_ENV === "development" ? "/" : "/dist/",

  build: {
    outDir: "../../public/dist",
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, "src/main.ts"),
    },
  },

  server: {
    strictPort: true,
    port: 5133,
  },

  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
    },
  },
});
