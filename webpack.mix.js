const mix = require('laravel-mix');
require('laravel-vue-i18n/mix');
const path = require('path');
require('dotenv').config();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/main.js', 'public/js')
    .options({
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    })
    .vue()
    .postCss("resources/css/app.css", "public/css", [require("tailwindcss")])
    .i18n()
    .sass('resources/sass/app.scss', 'public/css')
    .browserSync({
        proxy: 'app:80',
        files: ['./resources/**/*', './public/**/*', './app/**/*', './lang/**/*'],
        open: false
    });
