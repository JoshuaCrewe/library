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
        box-shadow: 0 2px 3px rgba(0,0,0,0.06);

        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;

        transition: box-shadow .3s ease;
    }

    form:focus-within {
        box-shadow: 0 2px 30px rgba(0,0,0,0.06);
        transition: box-shadow .3s ease;
    }

    input {
        display: block;
        height: 100%;
        margin: 0;
        padding: .5rem 48px .5rem .75rem;
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
        text-align: center;
    }

    .loading svg {
        animation: spin infinite 2s linear;
        width: 70px;
        height: 70px;
    }

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }
</style>

<form on:submit|preventDefault={handleSubmit}>
    <input type="search" id="search" bind:value="{searchValue}">
    <button>
        <svg class="feather feather-search" width="25" height="24">
            <use xlink:href="#icon--search"></use>
        </svg>
    </button>
</form>


{#if loading }
    <p class="loading">
        <svg>
            <use xlink:href="#icon--loading"></use>
        </svg>
    </p>
{/if }

<script>
    import { createEventDispatcher, onMount } from 'svelte';
    import { fade } from 'svelte/transition';
    import { push } from 'svelte-spa-router'

    export let data = {};
    export let params = {}

    let currentSearch = params.term || '';
    let loading = false;

    let searchValue = decodeURI(currentSearch.replace("+"," "));

    onMount(async () => {
        if (currentSearch !== '') {
            getBooks();
        }
    })

    const dispatch = createEventDispatcher();

	function sendData(loading) {
        push(`/search/${currentSearch}`)

		dispatch('books', {
			books: data
		});
	}

    async function getBooks() {
        loading = true;

        let url = '/api/search/' + currentSearch;
        const response = await fetch(url);
        const json = await response.json();
        console.log(json);
        data = json;
        loading = false;
        sendData();
    }

    const handleSubmit = (form) => {
        const { value } = form.srcElement.elements.search;
        if (value == '') {
            currentSearch = "__";
        } else {
            currentSearch = value.replace(" ","+");
            console.log(currentSearch);
        }
        getBooks();
    }
</script>
