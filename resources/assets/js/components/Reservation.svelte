<script>
    import { createEventDispatcher } from 'svelte';
    export let item;

    const dispatch = createEventDispatcher();

    let removed = false;
    let removing = false;

    async function cancelReservation() {
        removing = true;
        let url = '/api/dashboard/cancel/' + (item.id);
        const response = await fetch(url, {
            method : 'POST'
        });
        const json = await response.json();
        if (json.result) {
            removing = false;
            removed = true;
            dispatch('remove');
        }
    }
</script>
<div class="flex hover:bg-gray-100 py-4 px-2 relative">
    <div class="w-1/3 pr-4">
        <img src="{item.image}" alt="{item.title}" class="mb-0">
    </div>
    <div class="w-2/3">
        <a class="card-link" href="#/item/{item.id}">
            <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-8 lg:text-2xl md:mb-4">
                {item.title}
            </h2>
        </a>
        <h3 class="text-base mb-2 leading-4 body-font md:text-lg md:mb-4">
            {item.author}
        </h3>
        <p class="text-xs sm:text-sm bg-green-200 rounded px-1 inline-block mb-2">
            Position : {item.position}
        </p>
        <p class="text-sm leading-5 sm:text-base">
            Reserved on : {item.reserveDate}
        </p>
        <button class="button bg-red-100 border-red-500 mt-8 relative z-10" on:click={cancelReservation}>
            {#if removing}
                Cancelling ...
            {:else if removed}
                Canceled!
            {:else}
                Cancel
            {/if}
        </button>
    </div>
</div>
