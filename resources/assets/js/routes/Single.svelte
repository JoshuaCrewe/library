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


<style>
    .form-wrap {
        margin: 2rem 0;
        transition: margin .3s ease;
        padding: 0 2rem;
    }

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }

    :global(.jsHidden),
    :global(.hidden) {
        display: none;
    }

    .item {
        margin: 0 auto;
        max-width: 75%;
    }

    .item-title {
        margin-bottom: .25rem;
    }

    .item-author {
        margin-bottom: .25rem;
    }

    .item-wrap {
        display: flex;
    }

    .item-details {
        flex: 1;
    }

    .item-image {
        width: 200px;
        flex: 1;
    }

    .item-genres  {
        list-style: none;
    }

    .item-status {
        margin-top: 4rem;
        list-style: none;
        padding-left: 0;
    }

    .item-footer {
        max-width: 75%;
        margin: 0 auto;
        display: flex;
        justify-content: flex-end;
    }

    .button {
        margin: 2rem 0 ;
    }

    @media (max-width: 900px) {
        .item {
            max-width: 100%;
            padding: 0 2rem;
        }

    }
    @media (max-width: 600px) {
        .item-wrap {
            flex-direction: column;
        }

        .item-genres {
            margin-bottom: 2rem;
        }
    }

</style>
