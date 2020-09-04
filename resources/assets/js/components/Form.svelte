<script>
    import { onMount, createEventDispatcher } from 'svelte';
    import { fade } from 'svelte/transition';
    import { push } from 'svelte-spa-router'

    import {items, page} from './../stores';

	const dispatch = createEventDispatcher();

    let searchInput;

    export let params = {}
    page.subscribe(page => {
        getItems();
    });

    let loading = false;

    let searchValue = decodeURI($items.currentSearch.replace(/\+/g," ")); 
    /* console.log($items.page); */
    /* console.log(searchValue); */

    onMount(async () => {
        if (params.term) {
            items.update( items => {
                items.currentSearch = params.term;
                return items;
            });
            if ($items.results.length == 0) {
                getItems(() => {
                    push(`/search/${$items.currentSearch}?page=${$page}`)
                });

            }
        }
    })

    async function getItems(callback) {
        loading = true;
        dispatch('loading', true);

        if ($items.currentSearch == '') {
            return true;
        }

        let url = '/api/search/' + $items.currentSearch + '?page=' + $page;

        const response = await fetch(url);
        const json = await response.json();

        items.update( items => {
            items.results = json.results;
            items.total = json.total;
            items.limit = Math.ceil(json.total / 10);
            return items;
        })

        searchValue = decodeURI($items.currentSearch.replace(/\+/g," ")); 

        loading = false;
        dispatch('loading', false);

        if (typeof callback == 'function') {
            await callback()
        }
    }

    const handleSubmit = (form) => {
        page.update(() =>1);
        const { value } = form.srcElement.elements.search;
        if (value !== '') {
            items.update(items => {
                items.currentSearch = value.replace(/ /g, "+");
                return items;
            })
            getItems(() => {
                push(`/search/${$items.currentSearch}`)
            });
        } else {
            items.update(items => {
                items.results = [];
                return items;
            })
            push('/');
        }
    }

    function handleClear() {
        searchValue = '';
        searchInput.focus();
    }
</script>

<form on:submit|preventDefault={handleSubmit} class="relative flex justify-center pt-20 pb-10 px-4 md:px-8 w-full">
    <div class="relative w-full md:w-1/2">
        <input type="search" id="search" bind:value={searchValue} class="appearance-none rounded-lg border-gray-200 bg-gray-200 pl-10 pr-8 py-2 w-full placeholder:text-sm border-solid focus:border-gray-300 focus:shadow-sm border-2 focus:outline-none" placeholder="Search" bind:this={searchInput}>
        <button class="absolute left-0 top-0 my-2 ml-2">
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
        
        {#if searchValue != ''}
            <button class="absolute right-0 top-0 my-2 mr-2" on:click|preventDefault={handleClear}>
                <svg class="" width="25" height="25">
                    <use xlink:href="#icon--close"></use>
                </svg>
            </button>
        {/if}
    </div>
</form>
