var gulp = require('gulp')
var concat = require('gulp-concat')


gulp.task('default', ['js', 'watch']);

gulp.task('js', function () {
  gulp.src(['public/components/**/*.js', 'public/directives/**/*.js'])
    .pipe(concat('public/app.js'))
    .pipe(gulp.dest('.'))
})

gulp.task('watch', ['js'], function () {
  gulp.watch('public/components/**/*.js', ['js'])
})
