/*
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


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

mix.options({processCssUrls: false})
    .js('src/app.js', 'public/js')
    .js('src/admin.js', 'public/js')
    .js('src/trip.js', 'public/js')
    .js('src/art.js', 'public/js')
    .sass('src/style/app.scss', 'public/css')
    .sass('src/style/admin.scss', 'public/css');
