<script>
    import {link} from 'svelte-spa-router'
</script>

<header class="layout bg-gray-900 text-white flex justify-center w-full">
    <div class="flex justify-between w-full max-w-screen-lg">
        <a href="/" class="hover:text-gray-500 focus:text-gray-500">
            <h1 class="title gamma">Library.</h1>
        </a>

        <ul class="flex justify-center items-center">
            <li class="">
                <a href="/dashboard" use:link class="hover:text-gray-500 focus:text-gray-500 pr-4">
                    Dashboard
                </a>
            </li>
            <li class="">
                <a href="/lists" use:link class="hover:text-gray-500 focus:text-gray-500">
                    Lists
                </a>
            </li>
        </ul>
    </div>
</header>
