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
    gulp.watch('./src/Intracto/SecretSantaBundle/Resources/public/scss/**/*.scss', ['sass']);
});

gulp.task('sass', function() {

    var styles = gulp.src('./src/Intracto/SecretSantaBundle/Resources/public/scss/**/*.scss')
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(autoprefixer({
            browsers: ['last 4 versions'],
            cascade: false
        }))
        .pipe(livereload())
        .pipe(gulp.dest('./src/Intracto/SecretSantaBundle/Resources/public/css'));
});

gulp.task('uglify', function() {
    gulp.src(['./src/Intracto/SecretSantaBundle/Resources/public/js/*.js', '!./src/Intracto/SecretSantaBundle/Resources/public/js/*.min.js'])
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./src/Intracto/SecretSantaBundle/Resources/public/js'));
});

gulp.task('minifyCSS', function () {
    gulp.src(['./src/Intracto/SecretSantaBundle/Resources/public/css/*.css', '!./src/Intracto/SecretSantaBundle/Resources/public/css/*.min.css', '!./src/Intracto/SecretSantaBundle/Resources/public/css/main.css'])
        .pipe(minifyCSS())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./src/Intracto/SecretSantaBundle/Resources/public/css'));
});

gulp.task('build', [
    'sass',
    'uglify',
    'minifyCSS'
]);
