<style>
    
</style>

<Router {routes}/>


<script>
import Router from 'svelte-spa-router'

import Book from "./routes/Book.svelte";
import Search from "./routes/Search.svelte";

const routes = {
    // Exact path
    "/": Search,

    // Wildcard parameter
    "/item/:id": Book
};

</script>
