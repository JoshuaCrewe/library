<script>
    import {push} from 'svelte-spa-router'
    let barcode = '';
    let error = false;
    async function handleLogin(e) {

        let url = window.location.origin + '/api/barcode/' + barcode;

        const response = await fetch(url);
        const json = await response.json();
        if (!json.success) {
            error = true;
        } else {
            error = false;
            push(`/dashboard`);
        }

    }
</script>
<div class="min-h-screen">
    <section class="layout">
        <div class="center py-20">
            <form class:border-red-500={error} class="border-solid border-2 border-transparent p-4 w-1/2 mx-auto my-0" action="" on:submit|preventDefault={handleLogin}>
                <label class="" for="barcode">
                    <h2 class="text-xl mb-4">
                        Enter your library card number
                    </h2>
                </label>
                <input class="appearance-none rounded-lg border-gray-200 bg-gray-200 text-center p-2 w-full placeholder:text-sm border-solid focus:border-gray-300 focus:shadow-sm border-2 focus:outline-none mb-8" type="text" bind:value={barcode} name="barcode" id="barcode">
                <button class="button my-0 mx-auto">
                    Log in
                </button>
            </form>
            {#if error}
                <p class="text-red-500">
                    Sorry, we didn't recognise your borrower number. Please try again.
                </p>
            {/if}
        </div>
    </section>
</div>
