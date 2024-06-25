const { defineConfig } = require("@vue/cli-service");

module.exports = defineConfig({
  pages: {
    index: {
      entry: "src/index.js",
      template: "public/index.php",
      filename: "index.php",
    },
  },
  publicPath: "./",
  outputDir: "html",
  assetsDir: "dist",
  transpileDependencies: true,
  productionSourceMap: false,
  runtimeCompiler: true,
  configureWebpack: {
    output: {
      filename: "dist/js/[name].[contenthash:8].js",
      chunkFilename: "dist/js/[name].[contenthash:8].js",
      clean: process.env.NODE_ENV === "production",
    },
  },
  chainWebpack: (config) => {
    config.plugin("copy").tap((args) => {
      args[0].patterns[0].transform = (input, path) => {
        if (
          process.env.NODE_ENV === "production" &&
          /lib\/views\/.*?\.php$/.test(path)
        ) {
          return minify(input.toString(), {
            collapseWhitespace: true,
            minifyCSS: true,
            minifyJS: true,
            removeComments: true,
            continueOnParseError: true,
            sortAttribues: true,
            sortClassName: true,
            removeRedundantAttributes: true,
          });
        }
        return input;
      };

      return args;
    });
  },
  css: {
    extract: true,
  },
});
