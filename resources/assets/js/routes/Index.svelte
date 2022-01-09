<script>
    import { onMount } from 'svelte';

    import {link, push, querystring, location} from 'svelte-spa-router'

    import Form from './../components/Form.svelte'
    import Item from './../components/Item.svelte'
    import FakeItem from './../components/FakeItem.svelte'

    import {items, page} from './../stores';

    let loading = true;
    let atStart;
    let atEnd;
    let qs = new URLSearchParams($querystring);

    onMount(async () => {
        if (qs.has('page')) {
            page.update(() => Number(qs.get('page')));
        }
        atStart = $page <= 1;
        atEnd = $items.limit == $page;
    });

    export let params = {}
    function handlePreviousPage() {
        setTimeout(() => {
            document.querySelector('#list').scrollIntoView({ 
                behavior: 'smooth' 
            },500);
        })
        page.update(page => page - 1 )
    }

    function handleNextPage() {
        setTimeout(() => {
            document.querySelector('#list').scrollIntoView({ 
                behavior: 'smooth' 
            },500);
        })
        page.update(page => page + 1 )
    }

    function handlePagination() {
        if (!loading) {
            atStart = $page <= 1;
            atEnd = $items.limit == $page;
        }
        if ($location !== '/') {
            push(`/search/${$items.currentSearch}?page=${$page}`)
        }
    }
</script>
<div class="min-h-screen">

    <div class="" class:no-results={$items.results.length === 0}>
        <Form {params} on:loading={(event) => {loading = event.detail; handlePagination();} }/>

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
        <div class="layout w-full md:w-3/4 m-auto max-w-3xl" id="list">
            {#if $page >= 2 }
                <p class="center text-base title-font mb-4">
                    Page {$page} of {$items.limit}
                </p>
            {/if}
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

            <p class="center text-base title-font mt-4">
                Page {$page} of {$items.limit}
            </p>

            <nav class="flex justify-between mt-4">
                <button disabled={atStart} class="button" on:click={handlePreviousPage}>
                    <svg class="-ml-2 mr-2" width="18" height="18">
                        <use xlink:href="#icon--chevron-left"></use>
                    </svg>
                    Previous
                </button>
                <button disabled={atEnd} class="button" on:click={handleNextPage}>
                    Next
                    <svg class="ml-2 -mr-2 transform rotate-180" width="18" height="18">
                        <use xlink:href="#icon--chevron-left"></use>
                    </svg>
                </button>
            </nav>
        </div>
    {/if}
</div>
