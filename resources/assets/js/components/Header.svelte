<style lang="sass">
    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
    }

    .menu {
        list-style: none;
    }

</style>
<header>
    <a href="/" use:link>
        <h1>Library.</h1>
    </a>

    <ul class="menu">
        <li>
            <a href="/dashboard" use:link>Dashboard</a>
        </li>
    </ul>

</header>

<script>
    import {link, pop} from 'svelte-spa-router'

    function clearSearch(e) {
        e.preventDefault();
        pop('/');
    }
</script>
