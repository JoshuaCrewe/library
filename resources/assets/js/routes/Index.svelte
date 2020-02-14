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

    <div class="pagination" >
        <button>
            Next
        </button>
        <button>
            Previous
        </button>
        
    </div>
{/if}
