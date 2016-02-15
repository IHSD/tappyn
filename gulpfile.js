var gulp = require('gulp')
var concat = require('gulp-concat')


gulp.task('default', ['js', 'watch']);

gulp.task('js', function () {
  gulp.src(['components/**/*.js', 'directives/**/*.js'])
    .pipe(concat('app.js'))
    .pipe(gulp.dest('.'))
})


gulp.task('watch', ['js'], function () {
  gulp.watch('/components/**/*.js', ['js'])
})