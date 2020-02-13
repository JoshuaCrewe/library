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

<style>
    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem;
        background-color: var(--white);
        border-bottom: 2px solid #edf2f7;
    }

    h1 {
        margin-bottom: 0;
    }

    .menu {
        list-style: none;
    }

</style>
