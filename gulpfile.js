// Project configuration
var project     = 'Daily-Market-Report-Plugin';

// include gulp
var gulp        = require('gulp'); 

// include plug-ins
var jshint      = require('gulp-jshint');
var changed     = require('gulp-changed');
var connect     = require('gulp-connect-php');
var browserSync = require('browser-sync');
var modRewrite  = require('connect-modrewrite');
var plumber     = require('gulp-plumber');
var notify      = require('gulp-notify');

var plumberErrorHandler = { errorHandler: notify.onError({
 
    title: 'Gulp',
 
    message: 'Error: <%= error.message %>'
 
  })
 
};

gulp.task('connect', function() {
 
  var files = [
    './*.php'
  ];

  connect.server({}, function (){

    browserSync.init (files, {

      proxy: "localhost:3000/magnaoptions",

      notify: false

 
/*
      startPath: '/',

      middleware: [

        // mod_rewrite equivalent found in .htaccess
        // so that we can get clean urls
        modRewrite(
          [
            '^(\/)$ / [NC,L]',
            '^([^\.]+)$ $1.php [NC,L]'
          ]
        )        
      ]
*/
    });

  });

  gulp.watch('./**/*.php').on('change', browserSync.reload);


});

// default gulp task
gulp.task('default', ['connect']);

