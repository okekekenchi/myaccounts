const mix = require('laravel-mix'),

    // caseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin'),

    VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')

const webpackConfig = {
    plugins: [
        // new caseSensitivePathsPlugin({ debug: true }),
        new VuetifyLoaderPlugin()
    ]
}



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

mix.webpackConfig( webpackConfig )
    .js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .postCss('resources/css/app.css', 'public/css')
    .autoload({
        jquery: ['$', 'window.jQuery']
    })
    // .browserSync('https://kenmaxi-erp.com')