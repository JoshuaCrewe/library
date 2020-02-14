<script>
    import {link} from 'svelte-spa-router'
</script>

<header>
    <a href="/" use:link>
        <h1 class="title gamma">Library.</h1>
    </a>

    <ul class="menu">
        <li>
            <a href="/dashboard" use:link>Dashboard</a>
        </li>
    </ul>
</header>
