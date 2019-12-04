import Book from "./routes/Book.svelte";
import Search from "./routes/Search.svelte";
import Dashboard from "./routes/Dashboard.svelte";
import { wrap } from "svelte-spa-router";

const routes = {
    "/search/:term": Search,
    "/": Search,
    "/item/:id": Book,
    "/dashboard": Dashboard
};

export default routes;
