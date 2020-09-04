<script>
    import { onMount } from 'svelte';
    import { fade } from 'svelte/transition';
    import { push } from 'svelte-spa-router'
    import items from './../stores';

    export let params = {}

    let loading = false;

    let searchValue = decodeURI($items.currentSearch.replace(/\+/g," ")); 
    console.log($items.page);

    onMount(async () => {
        if (params.term) {
            items.update( items => {
                items.currentSearch = params.term;
                return items;
            });
            if ($items.results.length == 0) {
                getItems();
            }
        }
    })

    async function getItems() {
        loading = true;

        let url = '/api/search/' + $items.currentSearch + '?page=1';
        const response = await fetch(url);
        const json = await response.json();

        items.update( items => {
            items.results = json.results;
            return items;
        })


        loading = false;
        push(`/search/${$items.currentSearch}`)
    }

    const handleSubmit = (form) => {
        const { value } = form.srcElement.elements.search;
        if (value !== '') {
            items.update(items => {
                items.currentSearch = value.replace(/ /g, "+");
                return items;
            })
            getItems();
        } else {
            items.update(items => {
                items.results = [];
                return items;
            })
            push('/');
        }
    }
</script>

<form on:submit|preventDefault={handleSubmit} class="relative flex justify-center pt-20 pb-10 px-4 md:px-8 w-full">
    <div class="relative w-full md:w-1/2">
        <input type="search" id="search" bind:value={searchValue} class="appearance-none rounded-lg bg-gray-200 pl-10 pr-4 py-2 delta w-full" placeholder="Search">
        <button class="absolute left-0 top-0 my-3 ml-2">
            {#if loading }
                <svg class="animate-spin inline" width="25" height="24">
                    <use xlink:href="#icon--loading"></use>
                </svg>
            {:else}
                <svg class="" width="25" height="24">
                    <use xlink:href="#icon--search"></use>
                </svg>
            {/if }
        </button>
    </div>
</form>
