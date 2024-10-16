const mix = require('laravel-mix');
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss')
    ])
    .version();
mix.ts('resources/js/utils/functions.ts', 'public/js');
mix.webpackConfig({
    resolve: {
        extensions: [".*",".wasm", ".mjs", ".js", ".jsx", ".json", ".ts", ".tsx", ".vue",".*"]
    }
});
if (!mix.inProduction()) {
    mix.webpackConfig({devtool: 'source-map'})
        .sourceMaps();
}

if (mix.inProduction()) {
    mix.version();
}
