let mix = require('laravel-mix');

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

mix.copyDirectory('resources/assets/images/favicon', 'public/images/favicon');
mix.copyDirectory('resources/assets/images/static', 'public/images/static');
mix.copyDirectory('resources/assets/images/www', 'public/images/www');

mix.sass('resources/assets/sass/app.scss', 'public/css').version();
mix.sass('resources/assets/sass/www/app.scss', 'public/css/www').version();

mix.js('resources/assets/js/app.js', 'public/js').version().extract([
    'vue', 'lodash', 'axios', 'cookie', 'vue-echo', 'vue-moment', 'moment', 'bootstrap-vue', 'vuex', 'vuex-router-sync', 'vee-validate', 'vue-deepset', 'vue-router', 'vue-axios', 'vue-analytics'
]);

mix.copy('resources/assets/js/www/wow.min.js', 'public/js/www').version();

mix.browserSync({
    proxy: process.env.MIX_SENTRY_DSN_PUBLIC,
    open: false,
    browser: "Google Chrome",
    port: 8000
});


