const config = require("../../config/sprite");
const gulp = require("gulp");
const svgSprite = require("gulp-svg-sprite");

module.exports = function () {
  return gulp
    .src(config.paths.watch) // Убедитесь, что здесь верный путь к SVG файлам
    .pipe(
      svgSprite(config.options.svgSprite("default")), // Используйте вашу SVG конфигурацию
    )
    .on("error", function (err) {
      console.error("Error while creating sprite:", err.message);
    })
    .pipe(gulp.dest(config.paths.dest));
};
