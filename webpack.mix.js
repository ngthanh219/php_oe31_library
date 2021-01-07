const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles('resources/css/login.css', 'public/css/login.css')
    .styles('resources/css/all.css', 'public/css/all.css')
    .styles('resources/css/client.css', 'public/css/client.css')
    .js('resources/js/sweet-alert.js', 'public/js')
    .js('resources/js/select2.js', 'public/js')
    .js('resources/js/user_menu.js', 'public/js');
