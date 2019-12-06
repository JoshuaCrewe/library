<script>
    import { onMount } from 'svelte';
    import { fade } from 'svelte/transition';
    import { push } from 'svelte-spa-router'
    import items from './../stores';

    export let data = {};
    export let params = {}

    let loading = false;

    let searchValue = decodeURI($items.currentSearch.replace(/\+/g," ")); 

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

        let url = '/api/search/' + $items.currentSearch;
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

<form on:submit|preventDefault={handleSubmit}>
    <input type="search" id="search" bind:value={searchValue}>
    <button>
    {#if loading }
        <svg class="feather feather-search loading" width="25" height="24">
            <use xlink:href="#icon--loading"></use>
        </svg>
    {:else}
        <svg class="feather feather-search" width="25" height="24">
            <use xlink:href="#icon--search"></use>
        </svg>
    {/if }
    </button>
</form>

<style>
    form {
        width: 66%;
        margin: 0 auto;
        box-sizing: border-box;
        display: flex;
        position: relative;
        background-color: #fff;

        border-radius: 4px;
        border: 1px solid rgba(0,0,0,0.15);

        justify-content: space-between;
        align-items: center;

        transition: box-shadow .3s ease;
    }

    form:focus-within {
        transition: box-shadow .3s ease;
        /* border: 1px solid rgba(0,0,0,.5); */
    }

    input {
        display: block;
        height: 100%;
        margin: 0;
        padding: 1rem 48px 1rem .75rem;
        border: 0;
        font-size: 1.5rem;
        width: 100%;
        min-width: 50%;
        font-family: 'ITF-Medium';
    }


    button {
        background-color: transparent;
        border: none;
        padding: 0;
        margin: 0;
        cursor: pointer;

        position: absolute;
        right: 0;
    }

    button:focus {
        background-color: #eee;
    }

    .feather {
        width: 48px;
        padding: 12px;
        height: 48px;
    }

    @media(max-width: 600px) {
        form {
            width: 90%;
        }
    }


    .loading {
        animation: spin infinite 2s linear;
    }

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }
</style>
