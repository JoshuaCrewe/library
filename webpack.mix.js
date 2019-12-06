let mix = require("laravel-mix");
let svelte = require("laravel-mix-svelte");

mix.options({
    notifications: {
        onSuccess: false,
        onFailure: true
    }
});

mix.js("resources/assets/js/app.js", "public/dist/js")
    .sass("resources/assets/scss/app.scss", "public/dist/css")
    .svelte({ withSass: true });

// Create a sprite sheet based on a folder of SVGs
const SVGSpritemapPlugin = require("svg-spritemap-webpack-plugin");
mix.webpackConfig({
    plugins: [
        new SVGSpritemapPlugin("resources/assets/icons/**/*.svg", {
            output: {
                filename: "partials/sprite.php",
                chunk: {
                    name: "dist/icons/spritemap"
                }
            },
            sprite: {
                prefix: "icon--"
            }
        })
    ]
});
