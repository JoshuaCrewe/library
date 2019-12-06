<script>
    import {link} from 'svelte-spa-router'

    import Form from './../components/Form.svelte'

    import {items} from './../stores';

    let results = $items.results;

    export let params = {}
</script>

<Form {params}/>

{#if $items.results.length !== 0}
    <div class="items">
        {#each $items.results as item}
            <a href="#/item/{item.id}" class="item">
                <div class="item-image">
                    <img src="{item.image}" alt="{item.title}">
                </div>
                <div class="item-details">
                    <h2>
                        {item.title}
                    </h2>
                    <p class="item-format">
                        {item.format}
                    </p>
                    <p>
                        {item.summary}
                    </p>
                </div>
            </a>
        {/each}
    </div>
{/if}


<style>
    h2 {
        margin-bottom: 0;
    }

    .items {
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        max-width: 600px;
    }

    .item {
        display: flex;
        width : 100%;
        flex: 1 1 100%;
        padding: 1rem 0;
    }

    .item-image {
        position: relative;
        padding-bottom: 75%;
        flex: 1;
    }

    .item-details {
        flex: 1;
        padding-left: 1rem;
    }

    .item-image img {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        object-fit: cover;
    }
    
    .item-format {
        border: 1px solid green;
        border-radius: 5px;
        color: green;
        display: inline-block;
        padding: .25rem .5rem;
    }
</style>
