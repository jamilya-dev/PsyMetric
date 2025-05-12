var gulp = require('gulp');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var path = require('path');

// utils
var pumped = require('../../utils/pumped');

// config
var config = require('../../config/images');

/**
 * Compress Images and
 * move them to the
 * built theme
 *
 */

async function loadImagemin() {
  return (await import('gulp-imagemin')).default;
}

module.exports = async function () {
  const imagemin = await loadImagemin();

  return gulp
    .src(config.paths.src, { encoding: false })
    .pipe(
      imagemin([
        imagemin.gifsicle({ interlaced: true }),
        imagemin.mozjpeg({ progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({
          plugins: [{ removeViewBox: true }, { cleanupIDs: false }],
        }),
      ])
    )
    .pipe(gulp.dest(config.paths.dest))
    .pipe(
      notify({
        message: pumped('Images Compressed'),
        onLast: true,
      })
    );
  // .pipe(browserSync.stream());
};
