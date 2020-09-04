<script>
    import {link} from 'svelte-spa-router'
</script>

<header class="layout bg-gray-900 text-white flex justify-between">
    <a href="/" use:link class="hover:text-gray-500 focus:text-gray-500">
        <h1 class="title gamma">Library.</h1>
    </a>

    <ul class="flex justify-center items-center">
        <li class="">
            <a href="/dashboard" use:link class="hover:text-gray-500 focus:text-gray-500">
                Dashboard
            </a>
        </li>
    </ul>
</header>
