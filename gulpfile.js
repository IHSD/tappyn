var gulp = require('gulp')
var concat = require('gulp-concat')
var gutil = require('gulp-util');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var jshint = require('gulp-jshint');

gulp.task('default', ['js', 'watch', 'lint']);

gulp.task('build', ['lint', 'js']);

gulp.task('js', function () {
  gulp.src(['components/**/*.js', 'directives/**/*.js'])
    .pipe(concat('app.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.env.env === 'prod' ? uglify() : gutil.noop())
    .pipe(gulp.dest('.'))
})

gulp.task('lint', function() {
    return gulp.src(['components/**/*.js', 'directives/**/*.js'])
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(jshint.reporter('fail'))
})
gulp.task('watch', ['js'], function () {
  gulp.watch('/components/**/*.js', ['js'])
})
