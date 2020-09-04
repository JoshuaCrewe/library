<script>
    import {link, push} from 'svelte-spa-router'
    import Form from './../components/Form.svelte';
    import {items} from './../stores';
    import {onMount} from 'svelte';

    export let params = {}
    export let data = {};
    let loading = false;

    async function getItem() {
        loading = true;

        let url = '/api/items/' + params.id;
        const response = await fetch(url);
        const json = await response.json();
        data = json.results;
        loading = false;
    }

    onMount(() => {
        getItem()
    })

    function returnToResults() {
        if ($items.currentSearch !== '') {
            push(`/search/${$items.currentSearch}`)
        } else {
            push('/');
        }
    }


</script>

<div class="min-h-screen">
    <div class="">
        <Form {params} />
    </div>

    
    <div class="layout flex flex-col lg:w-3/4 m-auto max-w-screen-md">
        {#if loading }
            <!-- image -->
            <div class="bg-gray-200 m-auto mb-4 " style="width:200px; height:304px;"> </div>

            <!-- Book Title -->
            <div class="h-10 w-1/2 m-auto bg-gray-200 mb-2"> </div>
            <!-- Book Author -->
            <div class="h-8 w-1/3 m-auto bg-gray-200 mb-4"> </div>

            <!-- Book Meta -->
            <ul class="flex justify-center items-center mb-4">
                <li class="bg-gray-200 rounded px-2 mr-2 text-xs h-6 w-12"> </li>
                <li class="bg-gray-200 rounded px-2 mr-2 text-xs h-6 w-8"> </li>
            </ul>
        {/if}
        <div class="mb-4">
            <img class="m-auto" src="{data.image}" alt="{data.title}" height="304" width="200">
        </div>

        <header class="center">
            {#if data.title}
                <h1 class="text-4xl">
                    {data.title}
                </h1>
            {/if}
            {#if data.author}
                <h3 class="text-2xl body-font mb-4">
                    {data.author}
                </h3>
            {/if}
        </header>

        <div class="">
            
            <div class="">
                {#if data.genres}
                    <ul class="flex justify-center items-center mb-4">
                        {#each data.genres as genre, i}
                            <li class="bg-blue-200 rounded px-2 mr-2 text-xs">
                                {genre}
                            </li>
                        {/each}
                    </ul>
                {/if}
            </div>

            <div class="mb-2">
                {#if data.summary}
                    <p class="item-summary">
                        {data.summary}
                    </p>
                {/if}

                {#if data.ISBN}
                    <h3>Details</h3>
                    <p>
                        ISBN : {data.ISBN}
                    </p>
                {/if}

            </div>
        </div>

        {#if data.status}
            <ul class="markup">
                {@html data.status}
            </ul>
        {/if}

    </div>

    <div class="layout lg:w-3/4 m-auto max-w-screen-md">
        {#if $items.currentSearch !== ''}
            <button class="button" on:click={returnToResults}>
                <svg class="" width="18" height="18">
                    <use xlink:href="#icon--chevron-left"></use>
                </svg>

                Return to results 
            </button>
        {/if}
    </div>
</div>
