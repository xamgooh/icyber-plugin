const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
 mix.options({
    // Don't perform any css url rewriting by default
    processCssUrls: false,
});

mix
  .js("resources/js/comparison-public.js", "public/js")
  .js("resources/admin/js/comparison-admin.js", "admin/js")
  .copy("resources/admin/js/jquery-validate-min.js", "admin/js")
  .sass("resources/sass/comparison-public.scss", "public/css")
  .sass("resources/admin/sass/comparison-admin.scss", "admin/css")
  .sourceMaps();
