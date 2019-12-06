<script>
    import {link} from 'svelte-spa-router'

    import Form from './../components/Form.svelte'
    import Item from './../components/Item.svelte'

    import {items} from './../stores';

    let results = $items.results;

    export let params = {}
</script>
<div class="form-wrap" class:no-results={$items.results.length === 0}>
    <Form {params}/>

    {#if $items.results.length === 0 }
        <div class="introduction">
            <h2 class="gamma subtitle">Search for books, music, films and more.</h2>
            <p class="hide">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>
    {/if}
</div>

{#if $items.results.length !== 0}
    <div class="items">
        {#each $items.results as item}
            <Item {item}/>
        {/each}
    </div>
{/if}


<style>
    h2 {
        margin-bottom: 0;
    }

    .items {
        margin: 0 auto 0rem;
        display: flex;
        flex-wrap: wrap;
        max-width: 75%;
    }

    .form-wrap {
        margin: 2rem 0;
        transition: margin .3s ease;
        padding: 0 2rem;
    }

    .no-results {
        margin: 8rem 0;
        transition: margin .3s ease;
    }

    .introduction {
        width: 66%;
        margin: 0 auto;
        margin-top: 2rem;
        text-align: center;
    }

    .subtitle {
        margin-bottom: 1.5rem;
    }
    @media (max-width: 900px) {
        .items {
            max-width: 100%;
            padding: 0 2rem;
        }

    }
</style>
