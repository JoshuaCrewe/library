<script>
    /* import Router from 'svelte-spa-router' */
    /* import routes from './routes' */

    import { Router, Route } from 'svero';

    import Book from "./routes/Book.svelte";
    import Search from "./routes/Search.svelte";
    import Dashboard from "./routes/Dashboard.svelte";

    import Header from './components/Header.svelte'

</script>

<Header />
<Router>
  <Route path="/search/:term" >
    <Search />
  </Route>
  <Route path="/" component={Search} />
  <Route path="/item/:id" component={Book} />
  <Route path="/dashboard">
    <Dashboard/>
  </Route>
</Router>
