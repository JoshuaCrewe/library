<style>
    h1 {
        text-align: center;
    }
    :global(.feather) {
        width: 48px;
    }

    .loading {
        text-align: center;
    }

    .loading svg {
        animation: spin infinite 2s linear;
        width: 40px;
        height: 40px;
    }

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }

    :global(.jsHidden),
    :global(.hidden) {
        display: none;
    }
    .book {
        margin: 0 auto;
        max-width: 600px;
    }

    .status {
        list-style: none;
        padding-left: 0;
    }

</style>
{#if data.title}
    <h1>
        <a href="/" use:link>
            {data.title}
        </a>
    </h1>
{:else}
    <h1>
        <a href="/" use:link>
            Library
        </a>
    </h1>
{/if}

{#if loading }
    <p class="loading">
        <svg class="feather feather--loading">
            <use xlink:href="#icon--loading"></use>
        </svg>
    </p>
{:else}
    <div class="book">

        <img src="{data.image}" alt="{data.title}">
        {#if data.author}
            <h3>
                {data.author}
            </h3>
        {/if}
        {#if data.ISBN}
            <p>
                ISBN : {data.ISBN}
            </p>
        {/if}
        {#if data.genres}
            <ul>
                {#each data.genres as genre, i}
                    <li>
                        {genre}
                    </li>
                {/each}
            </ul>
        {/if}
        {#if data.summary}
            <p>
                {data.summary}
            </p>
        {/if}

        {#if data.status}
            <ul class="status">
                {@html data.status}
            </ul>
        {/if}

    </div>
{/if }


<script>
    import { fade } from 'svelte/transition';
    import {link} from 'svelte-spa-router'

    export let params = {}
    export let data = {};
    let loading = false;

    async function getBook() {
        loading = true;

        let url = '/api/item/' + params.id;
        const response = await fetch(url);
        const json = await response.json();
        console.log(json);
        data = json.results;
        loading = false;
    }
    getBook()

</script>
