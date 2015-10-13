module.exports = function (grunt) {
  grunt.initConfig({
    //These watch tasks are executed on "$ grunt"
    watch: {

      //tasks: [xxx -> xxx -> xxx]

      //example: compiles sass -> autoprefix css -> trigger notification
      sass: {
        files: ['../scss/**/*.{scss,sass}'],
        tasks: [
          'sass:dev',
          //'autoprefixer:dev',
          'notify:finished'
        ]
      },

      //watches .css files in css/ folder and triggers livereload
      css: {
        files: ['../css/*.css'],
        options: {
          livereload: true
        }
      },

      //watch for js plugins and bundle them
      js: {
        files: ['../js/plugins/**/*.js'],
        tasks: ['uglify:dev']
      },

      //watch for bower downloaded files and move them to plugins folder
      //bower: {
      //    files: ['../bower_components/**/*.js'],
      //    tasks: ['bower:install']
      //},

      //watches .tpl files in any folder for changes and triggers livereload
      files: {
        files: ['../**/*.{tpl,php,twig,tpl.php}'],
        options: {
          livereload: true
        }
      },

      //watches svg files and starts cleaning previous minified svg's and recompiles everything
      svg: {
        files: ['../img/src/**/*.svg'],
        tasks: ['clean:svg', 'svg', 'sass:dev', 'autoprefixer:dev', 'notify:finished']
      },

      //watch images and compress only added images
      //images: {
      //    files: ['../images/src/**/*.{png,jpg,gif}'],
      //    tasks: ['newer:imagemin','notify:finishedimages']
      //}
    },

    //Sass task that configures how the css should be build
    sass: {

      //Dev version (triggers on $ grunt)
      dev: {
        options: {

          //This enables sourcemaps
          sourceMap: true,

          //This prints out the image base path
          //Usage: background image-url('file.png');
          imagePath: '../img/dist'
        },

        //Compiles the scss/main.scss to -> tmp/main.css
        files: {
          '../css/main.css': '../scss/main.scss',
        }
      },

      //Production version (triggers on $ grunt build)
      dist: {
        options: {
          //Disables the sourcemaps as they are not needed on production
          sourceMap: false,
          imagePath: '../images/dist'
        },
        files: {
          '../tmp/main.css': '../scss/main.scss',
        }
      }
    },

    //Simple notifications
    notify: {
      finished: {
        options: {
          enabled: true,
          message: 'Compiled',
        }
      },
      finishedimages: {
        options: {
          enabled: true,
          message: 'Compressed images',
        }
      },
      finishedbuild: {
        options: {
          enabled: true,
          message: 'Build Complete',
        }
      },
      finishedsvg: {
        options: {
          enabled: true,
          message: 'SVGs converted',
        }
      }
    },

    //Automatic prefixes css
    autoprefixer: {

      //Dev version (triggers on $ grunt)
      dev: {
        files: [
          {
            expand: true,
            cwd: '../tmp/',
            src: '{,*/}*.css',
            dest: '../css/'
          }
        ],
        options: {
          //Disabled sourcemaps
          map: true
        }
      },

      //Production version (also disabled sourcemaps)
      dist: {
        files: [
          {
            expand: true,
            cwd: '../tmp/',
            src: '{,*/}*.css',
            dest: '../css/'
          }
        ],
        options: {
          //Disabled sourcemaps
          map: false
        }
      }
    },

    //Compiles plugins in js/plugins/ and outputs to js/plugins.js
    //Extra compression is handled by Drupal
    uglify: {
      dev: {
        options: {
          beautify: true,
          sourceMap: true
        },
        files: {
          '../js/plugins.js': ['../js/plugins/**/*.js']
        }
      },
      dist: {
        options: {
          beautify: false,
          sourceMap: false
        },
        files: {
          '../js/plugins.js': ['../js/plugins/**/*.js']
        }
      }
    },

    //converts px to rem
    px_to_rem: {
      dist: {
        options: {
          base: 16,
          fallback: false,
          fallback_existing_rem: true,
          ignore: ['border', 'box-shadow', 'outline', 'transform', 'width', 'max-width', 'height', 'max-height']
        },
        files: {
          '../tmp/main.css': ['../tmp/main.css']
        }
      }
    },

    //Combines media queries to minimize file size (runs on $ grunt build)
    cmq: {
      your_target: {
        files: {
          '../tmp': ['../tmp/main.css']
        }
      }
    },

    //Removes duplicate css to minimize file size (runs on $ grunt build)
    cssshrink: {
      your_target: {
        files: {
          '../tmp': ['../tmp/main.css']
        }
      }
    },

    //image optimizer
//    imagemin: {                            // Task
//      dynamic: {                         // Another target
//        options: {
//          progressive: true
//        },
//        files: [
//          {
//            expand: true,                         // Enable dynamic expansion
//            cwd: '../images/src/',                   // Src matches are relative to this path
//            src: ['**/*.{png,jpg,gif}'],          // Actual patterns to match
//            dest: '../images/dist/'                  // Destination path prefix
//          }
//        ]
//      }
//    },

    //Minifies svg's
    svgmin: {
      options: {
        plugins: [
          {removeViewBox: false},
          {removeUselessStrokeAndFill: false},
          {convertPathData: {straightCurves: false}},
          {removeXMLProcInst: false}
        ]
      },
      dist: {
        files: [
          {
            expand: true,
            cwd: '../images/src',
            src: ['*.svg'],
            dest: '../images/dist/svg',
            ext: '.svg'
          }
        ]
      },
      dataurl: {
        files: [
          {
            expand: true,
            cwd: '../images/src/data-url',
            src: ['*.svg'],
            dest: '../images/dist/data-url/',
            ext: '.svg'
          }
        ]
      },
    },

    //Install predefined plugins in bower.json
    bower: {
      install: {
        options: {
          targetDir: '../js/plugins',
          layout: 'byType',
          install: true,
          verbose: false,
          cleanTargetDir: false,
          cleanBowerDir: false,
          bowerOptions: {}
        }
      }
    },

    //Removes other insignificant files
    clean: {
      //enabling force to delete files outside working directory
      options: { force: true },

      //removes unneeded files
      build: {
        src: [ '../css/**/*.map', '../js/**/*.map', '../images/dist/data-url/*', '!../images/dist/data-url/_data-url.svg.scss' ]
      },

      //removes previous minified svg files that might not be needed anymore to avoid unused svg files in production
      svg: {
        src: [ '../images/dist/**/*.svg']
      },

      //removes tmp folder from generating css
      tmp: {
        src: ['../tmp']
      }
    }
  });

  //Loads your tasks from packages.json only when they're needed
  require('jit-grunt')(grunt, {
    bower: 'grunt-bower-task',
    cmq: 'grunt-combine-media-queries',
    notify_hooks: 'grunt-notify'
  });

  //default taks (triggers on $ grunt)
  grunt.registerTask('default', ['watch', 'sass:dist']);

  //build task (triggers on $ grunt build)
  grunt.registerTask('build', [
    //Compiles sass
    'sass:dist',

    //Combines media queries
    'cmq',

    //Removes duplicate css
    'cssshrink',

    //Adds vendor prefixes
    'autoprefixer:dist',

    //Converts px to rem
    //'px_to_rem:dist',

    //Removes tmp folder
    'clean:tmp',

    //Compile plugins to single plugin file
    'uglify:dist',

    //Optimize new images
    //'newer:imagemin',

    //Finished notification
    'notify:finishedbuild'
  ]);

  //svg build task (triggers on $ grunt svg)
  grunt.registerTask('svg', [

    //remove previous minified svg's
    'clean:svg',

    //minifies original svg's
    'svgmin',

    //creates data urls from svg's
    //removes unneeded files
    'clean:build',

    //Finished notification
    'notify:finishedsvg'
  ]);
};
