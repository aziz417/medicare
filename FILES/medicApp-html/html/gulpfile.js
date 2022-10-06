'use strict';

var gulp = require('gulp'),
  sass = require('gulp-sass'),
  postcss = require('gulp-postcss'),
  autoprefixer = require('autoprefixer'),
  cssnano = require('cssnano'),
  sourcemaps = require('gulp-sourcemaps'),
  htmlmin = require('gulp-htmlmin'),
  fileinclude = require('gulp-file-include');

function style() {
  // Where should gulp look for the sass files?
  // My .sass files are stored in the styles folder
  // (If you want to use scss files, simply look for *.scss files instead)
  return (
    gulp
      .src('dev/sass/**/*.scss')

      // Initialize sourcemaps before compilation starts
      .pipe(sourcemaps.init())
      .pipe(sass())
      .on('error', sass.logError)
      // Use postcss with autoprefixer and compress the compiled file using cssnano
      .pipe(postcss([autoprefixer(), cssnano()]))
      // Now add/write the sourcemaps
      .pipe(sourcemaps.write())
      .pipe(gulp.dest('assets/css'))
  );
}

function html() {
  return (
    gulp.src(['./dev/html/pages/**/*.html'])
      .pipe(fileinclude({
        prefix: '@@',
        basepath: '@file',
        indent: true
      }))
      // .pipe(htmlmin({ collapseWhitespace: true }))
      .pipe(gulp.dest('./dist'))
  );
}

function htmlHorizontal() {
  return (
    gulp.src(['./dev/html/pages-horizontal/**/*.html'])
      .pipe(fileinclude({
        prefix: '@@',
        basepath: '@file',
        indent: true
      }))
      // .pipe(htmlmin({ collapseWhitespace: true }))
      .pipe(gulp.dest('./dist-horizontal'))
  );
}

function watch(){
  // gulp.watch takes in the location of the files to watch for changes
  // and the name of the function we want to run on change
  gulp.watch('dev/sass/**/*.scss', style);
  gulp.watch('dev/html/**/*.html', html);
  gulp.watch('dev/html/**/*.html', htmlHorizontal);
}

exports.style = style;
exports.html = html;
exports.htmlHorizontal = htmlHorizontal;
exports.watch = watch;
