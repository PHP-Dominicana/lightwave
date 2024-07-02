import fs from "fs";
import { defineConfig } from "vite";
import { resolve } from "path";
import tailwindcss from "tailwindcss";
import autoprefixer from "autoprefixer";
import liveReload from "vite-plugin-live-reload";

const refreshPaths = [
  "resources/views/**",
  "resources/css/**",
  "resources/js/**",
  "public/index.php",
].filter((path) => fs.existsSync(path.replace(/\*\*$/, "")));

export default defineConfig({
  plugins: [
    liveReload([
      __dirname + "/**/*.php",
      __dirname + "/resources/views/**/*.twig",
      __dirname + "/resources/js/**/*.js",
      __dirname + "/resources/css/main.css",
    ]),
  ],

  base: "./",
  sourcemap: true,
  publicDir: "./public/build",
  refresh: { paths: refreshPaths },
  css: {
    postcss: {
      plugins: [tailwindcss, autoprefixer],
    },
  },
  build: {
    outDir: "dist",
    manifest: "manifest.json",
    rollupOptions: {
      input: {
        main: resolve(__dirname, "./resources/js/main.js"),
        style: resolve(__dirname, "./resources/css/main.css"),
      },
      output: {
        dir: "public/assets",
        entryFileNames: "js/bundle-[hash].js",
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith(".css")) {
            return "css/bundle-[hash][extname]";
          }
          return "assets/[name]-[hash][extname]";
        },
      },
    },
  },
});
