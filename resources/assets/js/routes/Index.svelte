<script>
    import {link} from 'svelte-spa-router'

    import Form from './../components/Form.svelte'
    import Item from './../components/Item.svelte'
    import FakeItem from './../components/FakeItem.svelte'

    import {items, page} from './../stores';

    let results = $items.results;
    let loading = false;

    let atStart = $page <= 1;
    let atEnd = $items.limit == $page;

    export let params = {}
    function handlePreviousPage() {
        page.update(page => page - 1 )
    }

    function handleNextPage() {
        page.update(page => page + 1 )
    }

    function handlePagination() {
        if (!loading) {
            atStart = $page <= 1;
            atEnd = $items.limit == $page;
        }
    }
</script>
<div class="min-h-screen">
    
    <div class="" class:no-results={$items.results.length === 0}>
        <Form {params} on:loading={(event) => {loading = event.detail;} }/>

        {#if $items.results.length === 0 }
            <div class="layout w-full md:w-3/4 center m-auto mb-4">
                <h2 class="text-2xl leading-8 mb-4">Search for books, music, films and more.</h2>
                <p class="hidden">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>
        {/if}
    </div>

    {#if $items.results.length !== 0}
        <div class="layout w-full md:w-3/4 m-auto max-w-3xl">
            <ul class="">
                {#if loading}
                    <li class="">
                        <FakeItem/>
                    </li>
                    <li class="">
                        <FakeItem/>
                    </li>
                    <li class="">
                        <FakeItem/>
                    </li>
                {:else}
                    {#each $items.results as item}
                        <li class="">
                            <Item {item}/>
                        </li>
                    {/each}
                {/if}
            </ul>

            <nav class="flex justify-between mt-4">
                <button disabled={atStart} class="button" on:click={handlePreviousPage}>
                    <svg class="" width="18" height="18">
                        <use xlink:href="#icon--chevron-left"></use>
                    </svg>
                    Previous
                </button>
                <button disabled={atEnd} class="border border-gray-400 rounded-md px-3 py-1 flex items-center disabled:bg-white disabled:opacity-50 disabled:cursor-default " on:click={handleNextPage}>
                    Next
                    <svg class="ml-2 -mr-2 transform rotate-180" width="18" height="18">
                        <use xlink:href="#icon--chevron-left"></use>
                    </svg>
                </button>
            </nav>
        </div>
    {/if}
</div>
