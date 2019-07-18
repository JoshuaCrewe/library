let mix = require("laravel-mix");
let svelte = require("laravel-mix-svelte");

mix.js("resources/assets/js/app.js", "public/dist/").svelte();
