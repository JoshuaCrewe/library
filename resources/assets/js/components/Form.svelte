<style>
    form {
        width: 66%;
        margin: 0 auto;
        box-sizing: border-box;
        border-radius: 4px;
        display: flex;
        position: relative;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,0.15);
        box-shadow: 0 2px 3px rgba(0,0,0,0.06);
        padding: .5rem .75rem;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    input {
        height: 100%;
        margin: 0;
        padding: 0;
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
        /* height: 24px; */
        /* width: 24px; */
        cursor: pointer;
    }

    :global(.feather) {
        width: 24px;
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
        width: 35px;
        height: 35px;
    }

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }
</style>

<form on:submit|preventDefault={handleSubmit}>
    <input type="search" id="search">
    <button>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    </button>
</form>

{#if loading }
    <p class="loading" transition:fade>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
    </p>
{/if }

<script>
    import { createEventDispatcher } from 'svelte';
    import { fade } from 'svelte/transition';

    let searchTerm = '';
    let loading = false;
    export let data = {};

    const dispatch = createEventDispatcher();

	function sendData(loading) {
		dispatch('books', {
			books: data
		});
	}


    async function getBooks() {
        loading = true;

        let url = '/api/search/' + searchTerm;
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
            searchTerm = "__";
        } else {
            searchTerm = escape(value.replace(" ","+"));
        }
        getBooks();
    }
</script>
