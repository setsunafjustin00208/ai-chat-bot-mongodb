// filepath: resources/gulpfile.js
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');

const paths = {
    styles: {
        src: 'scss/**/*.scss',
        dest: '../public/css/'
    },
    scripts: {
        src: 'js/**/*.ts',
        dest: '../public/js/'
    }
};

function styles() {
    return gulp.src(paths.styles.src)
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(concat('main.min.css'))
        .pipe(gulp.dest(paths.styles.dest));
}

function scripts() {
    return gulp.src(paths.scripts.src)
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.scripts.dest));
}

function watch() {
    gulp.watch(paths.styles.src, styles);
    gulp.watch(paths.scripts.src, scripts);
}

const build = gulp.series(gulp.parallel(styles, scripts), watch);

exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.build = build;