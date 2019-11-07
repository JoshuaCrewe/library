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
    .svelte();
