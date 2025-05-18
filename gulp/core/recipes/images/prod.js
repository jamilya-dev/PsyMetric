const gulp = require("gulp");
const notify = require("gulp-notify");
const path = require("path");

// utils
const pumped = require("../../utils/pumped");

// config
const config = require("../../config/images");

// Импортируем плагины отдельно
const imageminGifsicle = require("imagemin-gifsicle");
const imageminMozjpeg = require("imagemin-mozjpeg");
const imageminOptipng = require("imagemin-optipng");
const imageminSvgo = require("imagemin-svgo");

module.exports = async function () {
  const imagemin = await import("gulp-imagemin");

  return gulp
    .src(config.paths.src, { encoding: false })
    .pipe(
      imagemin.default([
        imageminGifsicle({ interlaced: true }),
        imageminMozjpeg({ progressive: true }),
        imageminOptipng({ optimizationLevel: 5 }),
        imageminSvgo({
          plugins: [{ removeViewBox: true }, { cleanupIDs: false }],
        }),
      ]),
    )
    .pipe(gulp.dest(config.paths.dest))
    .pipe(
      notify({
        message: pumped("Images Compressed"),
        onLast: true,
      }),
    );
};
