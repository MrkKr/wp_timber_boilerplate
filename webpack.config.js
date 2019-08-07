const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const path = require("path");
const TerserJSPlugin = require("terser-webpack-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

const config = {
  //Your path to dist accessed from a browser
  distPath: "/wp-content/themes/THEME_NAME/dist/",
  //Your local development url for browsersync
  localUrl: "http://localhost:8000/",
  //Open new window each time 'npm run serve' command is executed 
  openWindow: false,
}

module.exports = {
  optimization: {
    minimizer: [
      new TerserJSPlugin({
        sourceMap: true,
      }),
      new OptimizeCSSAssetsPlugin({
        cssProcessorOptions: {
          map: {
            inline: false,
          },
        },
      }),
    ],
  },
  devtool: "source-map",
  plugins: [
    new MiniCssExtractPlugin({
      filename: "css/style.min.css",
    }),
    new BrowserSyncPlugin({
      proxy: config.localUrl,
      files: ['**/*.php', '**/*.twig'],
      reloadDelay: 0,
      open: config.openWindow,
    })
  ],
  entry: {
    assets: ["@babel/polyfill", "./src/js/app.js",  "./src/js/bootstrap.js", "./src/scss/style.scss"],
  },
  output: {
    path: path.resolve(__dirname, "./dist/"),    
    filename: "js/script.min.js",
    publicPath: config.distPath,
  },
  module: {
    rules: [
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        loader: "babel-loader",
        options: {
          presets: [
            [
              "@babel/preset-env",
              {
                useBuiltIns: "entry",
                corejs: 3,
              },
            ],
          ],
          cwd: "./",
        },
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          "postcss-loader",
          "sass-loader",
        ],
      },
      {
        test: /\.(jpg|svg|woff|woff2|ttf|eot|png|jpeg)(\?.*$|$)/,
        loader: "file-loader",
      },
    ],
  },
};
