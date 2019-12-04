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
    <p class="loading" transition:fade>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
    </p>
{:else}
    <div class="book" transition:fade>

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
