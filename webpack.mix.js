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

mix.webpackConfig({
    devtool: '#eval-source-map',
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/assets/js/'),
            Mixins: path.resolve(__dirname, 'resources/assets/js/mixins'),
            Components: path.resolve(__dirname, 'resources/assets/js/components/'),
            Boilerplate: path.resolve(__dirname, 'resources/assets/js/components/boilerplate/'),
            Admin: path.resolve(__dirname, 'resources/assets/js/components/boilerplate/admin'),
        }
    }
});

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}