import Single from "./routes/Single.svelte";
import Index from "./routes/Index.svelte";
import Dashboard from "./routes/Dashboard.svelte";

const routes = {
    "/search/:term": Index,
    "/": Index,
    "/item/:id": Single,
    "/dashboard": Dashboard
};

export default routes;
