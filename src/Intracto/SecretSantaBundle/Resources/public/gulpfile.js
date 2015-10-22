var gulp = require('gulp')
    ,   sass = require('gulp-sass')
    ,   minifyCSS = require('gulp-minify-css')
    ,   concat = require('gulp-concat')
    ,   plumber    = require('gulp-plumber')
    ,   uglify = require('gulp-uglify')
    ,   livereload = require('gulp-livereload')
    ,   rename = require('gulp-rename');

var onError = function(err) {
    console.log(err);
}

/* Watch for scss changes and compile 'styles' task */
gulp.task('watch', function() {
    livereload.listen();
    gulp.watch('./scss/**/*.scss', ['sass']);
});

gulp.task('sass', function() {

    var styles = gulp.src('./scss/**/*.scss')
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sass())
        .pipe(minifyCSS({keepBreaks:true}))
        .pipe(rename({suffix: '.min'}))
        .pipe(livereload())
        .pipe(gulp.dest('./css'));
});

gulp.task('build', [
    'sass'
]);
