require('es6-promise').polyfill();
var gulp = require('gulp')
    ,   sass = require('gulp-sass')
    ,   minifyCSS = require('gulp-cssmin')
    ,   concat = require('gulp-concat')
    ,   plumber    = require('gulp-plumber')
    ,   uglify = require('gulp-uglify')
    ,   livereload = require('gulp-livereload')
    ,   rename = require('gulp-rename')
    ,   autoprefixer = require('gulp-autoprefixer');

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
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(autoprefixer({
            browsers: ['last 4 versions'],
            cascade: false
        }))
        .pipe(livereload())
        .pipe(gulp.dest('./css'));
});

gulp.task('uglify', function() {
    gulp.src(['./js/*.js', '!./js/*.min.js'])
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./js'));
});

gulp.task('minifyCSS', function () {
    gulp.src(['./css/*.css', '!./css/*.min.css', '!./css/main.css'])
        .pipe(minifyCSS())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./css'));
});

gulp.task('build', [
    'sass',
    'uglify',
    'minifyCSS'
]);
