"use strict";

const gulp            = require('gulp');
const sass            = require('gulp-sass')(require('sass'));
const concat          = require('gulp-concat');
const prefix          = require('gulp-autoprefixer');
const cssnano         = require('gulp-cssnano');
const rename          = require('gulp-rename');
const webpack         = require('webpack');
const webpackStream   = require('webpack-stream');
const CompressionPlugin = require("compression-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");

function compileCss() {
  return gulp
    .src("sass/screen.sass")
    .pipe(sass().on('error', sass.logError))
    .pipe(cssnano())
    .pipe(concat('main.css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(prefix('last 2 versions'))
    .pipe(gulp.dest("dist"));
};

function compileJs() {
  return gulp.src('./js/main.js')
    .pipe(webpackStream({
      mode: "production",
      output: {
        filename: '[name].min.js',
        chunkFilename: "[name].[chunkhash].js"
      },
      module: {
        rules: [
          {
            test: /\.(js|jsx)$/,
            exclude: /(node_modules)/,
            loader: "babel-loader",
            options: {
              presets: ['@babel/preset-env']
            }
          },
        ],
      },
      externals: {
        jquery: 'jQuery'
      },
      optimization: {
        minimize: true,
        minimizer: [new TerserPlugin()],
        splitChunks: {
          cacheGroups: {
            commons: {
              test: /[\\/]node_modules[\\/]/,
              name: 'vendors',
              chunks: 'all'
            }
          }
        },
      },
      plugins: [
        new CompressionPlugin()
      ]
    }), webpack)
    .pipe(gulp.dest('dist'));
}


function compileVendorCss() {
  return gulp.src('assets/styles/libs/*.css')
        .pipe(concat('vendors.min.css'))
        .pipe(gulp.dest('dist'));
}



function reload() {
  //server.reload();
}

function watchFiles() {
  gulp.watch('./sass/**/*.sass', compileCss);
  gulp.watch('./js/**/*.js', compileJs);
}

const build = gulp.series(compileCss,compileJs, watchFiles);
const watch = gulp.parallel(watchFiles);

// export tasks
exports.compileCss = compileCss;
exports.compileJs = compileJs;
exports.compileVendorCss = compileVendorCss;

exports.watch = watchFiles;
exports.build = build;
exports.default = build;
