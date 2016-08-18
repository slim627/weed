/**
 * Created by Alsheuski Alexei on 04/02/16.
 * File: gulpfile.js
 */

(function () {
  'use strict';

  var gulp = require('gulp');
  var sass = require('gulp-sass');
  var concat = require('gulp-concat');
  var sourcemaps = require('gulp-sourcemaps');
  var debug = require('gulp-debug');
  var gulpIf = require('gulp-if');
  var del = require('del');
  var newer = require('gulp-newer');
  var imagemin = require('gulp-imagemin');
  var autoprefixer = require('gulp-autoprefixer');
  var browserSync = require('browser-sync').create();
  var notify = require('gulp-notify');
  var cached = require('gulp-cached');
  var plumber = require('gulp-plumber');
  var jade = require('gulp-jade');
  var livereload = require('gulp-livereload');

  var isDevelopment = !process.env.NODE_ENV || process.env.NODE_ENV == 'development';

  var srcPath = './app/Resources/views/front/';

  /**
   * Задача для компиляции стилей из файлов препроцессора.
   * Если переменная окружения не установлена, либо установлена в
   * 'development', то будут созданы sourcemap-файлы
   */
  gulp.task('sass', function (cb) {

    // bootstrap compilation
    gulp.src(srcPath + 'src/sass/bootstrap.scss')
      //.pipe(newer(srcPath + '/src/sass'))
      .pipe(sass())
      .pipe(gulp.dest(srcPath + 'src/assets/global/plugins/bootstrap/css/')).on('end', cb);

    // select2 compilation using bootstrap variables
    gulp.src(srcPath + 'src/assets/global/plugins/select2/sass/select2-bootstrap.min.scss')
      .pipe(newer(srcPath + '/src/sass'))
      .pipe(sass({outputStyle: 'compressed'}))
      .pipe(gulp.dest(srcPath + 'src/assets/global/plugins/select2/css/')).on('end', cb);

    // global theme stylesheet compilation
    gulp.src(srcPath + 'src/sass/global/*.scss').pipe(newer(srcPath + '/src/sass')).pipe(sass()).pipe(gulp.dest(srcPath + 'src/assets/global/css')).on('end', cb);
    gulp.src(srcPath + 'src/sass/apps/*.scss').pipe(newer(srcPath + '/src/sass')).pipe(sass()).pipe(gulp.dest(srcPath + 'src/assets/apps/css')).on('end', cb);
    gulp.src(srcPath + 'src/sass/pages/*.scss').pipe(newer(srcPath + '/src/sass')).pipe(sass()).pipe(gulp.dest(srcPath + 'src/assets/pages/css')).on('end', cb);

    // theme layouts compilation
    gulp.src(srcPath + 'src/sass/layouts/layout/*.scss')
      .pipe(newer(srcPath + '/src/sass'))
      .pipe(sass())
      .pipe(debug())
      .pipe(gulp.dest(srcPath + 'src/assets/layouts/layout/css'))
      .on('end', cb);
    gulp.src(srcPath + 'src/sass/layouts/layout/themes/*.scss').pipe(newer(srcPath + '/src/sass')).pipe(sass()).pipe(gulp.dest(srcPath + 'src/assets/layouts/layout/css/themes')).pipe(livereload()).on('end', cb);

    // Custom styles
    gulp.src(srcPath + 'src/sass/custom/**/*.scss').pipe(newer(srcPath + '/src/sass')).pipe(sass()).pipe(gulp.dest(srcPath + 'src/assets/custom')).pipe(livereload()).on('end', cb);


    //return gulp.src(srcPath + 'src/sass/main.styl')
    //  .pipe(plumber({
    //    errorHandler: notify.onError(function (err) {
    //      return {
    //        title: 'sass error',
    //        message: err.message
    //      };
    //    })
    //  }))
    //  //.pipe(cached(srcPath + 'src/styles'))
    //  .pipe(gulpIf(isDevelopment, sourcemaps.init()))
    //  //.pipe(debug({title: 'src:     '}))
    //  .pipe(sass())
    //  .pipe(autoprefixer())
    //  //.pipe(debug({title: 'stylus:  '}))
    //  .pipe(gulpIf(isDevelopment, sourcemaps.write()))
    //  .pipe(gulp.dest(srcPath + 'dest/css'));
  });

  /**
   * Задача для JS
   */
  gulp.task('js', function (cb) {
    gulp.src(srcPath + 'src/js/**/*.*')
      .pipe(plumber({
        errorHandler: notify.onError(function (err) {
          return {
            title: 'JS error',
            message: err.message
          };
        })
      }))
      .pipe(cached(srcPath + 'src/js'))
      //.pipe(gulpIf(isDevelopment, sourcemaps.init()))
      //.pipe(gulpIf(isDevelopment, sourcemaps.write()))
      .pipe(gulp.dest(srcPath + 'dest/js'))
      .pipe(gulp.dest('./web/js'))
      .pipe(livereload()).on('end', cb);

    gulp.src(srcPath + 'src/components/**/**/*.*')
        .pipe(plumber({
          errorHandler: notify.onError(function (err) {
            return {
              title: 'Component error',
              message: err.message
            };
          })
        }))
        //.pipe(cached(srcPath + 'src/components'))
        .pipe(gulp.dest(srcPath + 'dest/components'))
        .pipe(gulp.dest('./web/components'))
        .pipe(livereload()).on('end', cb);
  });


  /**
   * Компиляция HTML
   */
  gulp.task('jade', function () {
    return gulp.src(srcPath + 'src/jade/**/*.*')
      .pipe(plumber({
        errorHandler: notify.onError(function (err) {
          return {
            title: 'Jade error',
            message: err.message
          };
        })
      }))
      .pipe(cached(srcPath + 'src/jade'))
      .pipe(jade({
        pretty: true
      }))
      .pipe(gulp.dest(srcPath + 'dest'))
      .pipe(livereload())
  });


  /**
   * Очитска назначения от старых css-файлов
   */
  gulp.task('clean', function () {
    return del(srcPath + 'dest/css');
  });


  /**
   *  При первом выполнении скопируются все файлы, которых нету в месте назначения
   *  либо дата их модификации младше чем дата модификации в месте назначения.
   *  При последующих только то, что изменилось с момента предыдущего выполнения задачи
   */
  gulp.task('images', function () {
    return gulp.src(srcPath + 'src/img/**/*.*')
      .pipe(plumber({
        errorHandler: notify.onError(function (err) {
          return {
            title: 'Images error',
            message: err.message
          };
        })
      }))
      .pipe(newer(srcPath + '/dest/img'))
      .pipe(debug())
      //TODO: Настроить компрессию изображений (см. инфу на странице плагина https://github.com/sindresorhus/gulp-imagemin)
      .pipe(imagemin())
      .pipe(gulp.dest(srcPath + '/dest/img'))
      .pipe(gulp.dest('./web/img'))
  });


  /**
   *  Сразу выполняет очистку и только после ее завершения параллельно
   *  выполняет все остальные задачи для билда
   */
  gulp.task('build', gulp.series('clean', gulp.parallel('sass', 'images', 'jade', 'js')));


  /**
   *  Задача для запуска слежения за изменениями в файлах при разработке
   */
  gulp.task('watch', function () {
    gulp.watch(srcPath + 'src/sass/**/*.scss', gulp.series('sass'));
    gulp.watch(srcPath + 'src/jade/**/*.jade', gulp.series('jade'));
    gulp.watch(srcPath + 'src/img/**/*.*', gulp.series('images'));
    gulp.watch(srcPath + 'src/js/**/*.js', gulp.series('js'));
    gulp.watch(srcPath + 'src/components/**/*.js', gulp.series('js'));
  });


  /**
   * Запуск собственного сервера browsersync. Если нужно запустить через сторонний
   * сервер (например настроен виртуальный хост Apache), то нужно настроить
   * прокси browsersync-а (https://www.browsersync.io/docs/options/#option-proxy)
   */
  gulp.task('serve', function () {
    //browserSync.init({
    //  //server: srcPath + 'dest',
    //  proxy: 'kindcann_front.loc'
    //});
    //
    //browserSync.watch(srcPath + 'dest/**/*.*').on('change', browserSync.reload);
    //browserSync.watch(srcPath + 'src/assets/**/*.*').on('change', browserSync.reload);

    livereload.listen();

  });


  /**
   * Задача запуска заданий галпа при старте разработки. В первую очередь
   * запускается обработка существующих файлов, после нее запускаются
   * наблюдатели за изменением файлов в процессе разработки
   */
  gulp.task('dev', gulp.series('build', gulp.parallel('watch', 'serve')));


})();