var gulp = require("gulp");
var plumber = require("gulp-plumber");
var sass = require("gulp-sass")(require("sass"));
var autoprefixer = require("autoprefixer");
var cleanCSS = require("gulp-clean-css");
var cssnano = require("cssnano");
var postcss = require("gulp-postcss");
var notify = require("gulp-notify");

// utils
var pumped = require("../../utils/pumped");

// config
var config = require("../../config/styles");

var plugins = [
  autoprefixer(config.options.autoprefixer),
  cssnano(config.options.minify),
];

/**
 * Compile SCSS to CSS
 * and Minify
 *
 */
module.exports = function () {
  return gulp
    .src(config.paths.src) // Собираем SCSS и CSS
    .pipe(plumber())
    .pipe(sass.sync().on("error", sass.logError)) // Компиляция SCSS
    .pipe(postcss([autoprefixer()])) // Авто-префикс
    .pipe(cleanCSS()) // Минификация CSS
    .pipe(gulp.dest(config.paths.dest)) // Сохранение скомпилированных файлов
    .pipe(
      notify({
        message: "SCSS and CSS Compiled & Minified",
        onLast: true,
      }),
    );
};
