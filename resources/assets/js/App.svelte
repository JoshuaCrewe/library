<script>
    import Router, {wrap} from 'svelte-spa-router'
    import routes from './routes'

    import './global.css'

    import Header from './components/Header.svelte'
    import Footer from './components/Footer.svelte'

    import items from './stores';

    items.subscribe(value => {
        console.log(value);
    });

    function routeLoaded(event) {
        if (event.detail.location ==='/') {
            items.set({
                currentSearch: "",
                results: []
            })
        }
    }
</script>

<Header />

<main id="main">
    <Router {routes} on:routeLoaded={routeLoaded} />
</main>

<Footer />
