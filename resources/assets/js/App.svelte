<script>
import Router from 'svelte-spa-router'

import Book from "./routes/Book.svelte";
import Search from "./routes/Search.svelte";

const routes = {
    "/search/:term": Search,
    "/": Search,
    "/item/:id": Book
};

</script>

<Router {routes}/>
