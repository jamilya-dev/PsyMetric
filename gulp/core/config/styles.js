// utils
var deepMerge = require("../utils/deepMerge");

// config
var assets = require("./common").paths.assets;

/**
 * Style Building
 * Configuration
 * Object
 *
 * @type {{}}
 */
module.exports = deepMerge({
  paths: {
    watch: [
      assets.src + "/sass/**/*.sass",
      "!" + assets.src + "/sass/**/*_tmp\\d+.sass",
      // assets.src + '/css/**/*.css'
    ],
    src: [
      assets.src + "/sass/*.sass",
      "!" + assets.src + "/sass/**/_*",
      // assets.src + '/css/**/*.css'
    ],
    dest: assets.dest + "/css",
    clean: assets.dest + "/css/**/*.{css,map}",
  },

  options: {
    sass: {},
    autoprefixer: {
      overrideBrowserslist: ["last 2 version", "ie >= 11", "IOS >= 7"],
    },
    minify: {
      preset: [
        "default",
        {
          discardComments: { removeAll: true },
        },
      ],
    },
  },
});
