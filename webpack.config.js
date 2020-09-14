const path = require('path');
const webpack = require('webpack');
const {
  VueLoaderPlugin
} = require('vue-loader')

module.exports = {
  watch: true,
  //watch: true,
  mode: 'production',
  entry: {
    app: './resources/vuejs/app.js',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/dist/vuejs'),
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      }
    ],
  },
  performance: {
    hints: false
  },
  plugins: [
    new VueLoaderPlugin()
  ],

}
