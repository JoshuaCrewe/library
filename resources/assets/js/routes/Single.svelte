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

        let url = '/items/' + params.id;
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

<div class="form-wrap">
    <Form {params} />
</div>

<div class="item">
    {#if data.title}
        <h1 class="item-title">
            {data.title}
        </h1>
    {/if}
    {#if data.author}
        <h3 class="item-author body-font">
            {data.author}
        </h3>
    {/if}

    <div class="item-wrap">
        
        <div class="item-image">
            <img class="" src="{data.image}" alt="{data.title}">
            {#if data.genres}
                <ul class="item-genres">
                    {#each data.genres as genre, i}
                        <li>
                            {genre}
                        </li>
                    {/each}
                </ul>
            {/if}
        </div>

        <div class="item-details">
            {#if data.summary}
                <p class="item-summary">
                    {data.summary}
                </p>
            {/if}

            <h3>Details</h3>
            {#if data.ISBN}
                <p>
                    ISBN : {data.ISBN}
                </p>
            {/if}

        </div>
    </div>

    {#if data.status}
        <ul class="item-status">
            {@html data.status}
        </ul>
    {/if}

</div>

<div class="item-footer"> 
    {#if $items.currentSearch !== ''}
        <button class="button epsilon" on:click={returnToResults}>
            <svg class="feather feather-button" width="25" height="24">
                <use xlink:href="#icon--chevron-left"></use>
            </svg>

            Return to results 
        </button>
    {/if}
</div>
