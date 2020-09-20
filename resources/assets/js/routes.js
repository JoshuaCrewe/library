import Single from "./routes/Single.svelte";
import Index from "./routes/Index.svelte";
import Login from "./routes/Login.svelte";
import Lists from "./routes/Lists.svelte";
import Dashboard from "./routes/Dashboard.svelte";

const routes = {
    "/search/:term": Index,
    "/": Index,
    "/login": Login,
    "/item/:id": Single,
    "/dashboard": Dashboard,
    "/lists": Lists
};

export default routes;
