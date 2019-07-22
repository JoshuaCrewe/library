# Library - A Lumen Project

The existing Library catalogue is a nice resource but is lacking with its interface. There are some annoying setting enabled which make it hard to navigate using a phone. This project is a learning exercise in web scraping, back end frameworks and front end SPAs.

## Approach

Ideally there would be an API available that could facilitate requests to a [Svelte](https://svelte.dev/) instance on the front end. This being absent we are using the existing website to create an API based on [Lumen](https://lumen.laravel.com).

## TODO

-   [x] Create at least one end point to get data from
-   [x] Initialise Svelte with laravel mix
-   [x] Implement a search bar to recreate search from main site
-   [ ] Add an endpoint to get one specific book information (including availability)
-   [ ] Handle pagination

## Eventually

-   [ ] Reserve books using a Library ID
