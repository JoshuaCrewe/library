<script>
    import { createEventDispatcher } from 'svelte';
    export let item;

    const dispatch = createEventDispatcher();

    export let canRemove = false;

    let removed = false;
    let removing = false;

    let saving = false;
    let saved = false;

    let reserving = false;
    let reserved = false;

    let actions = false;

    async function removeFromList() {
        removing = true;
        let url = '/api/list/' + item.id + '/remove';
        const response = await fetch(url, {
            method : 'POST'
        });
        const json = await response.json();
        if (json.result) {
            removing = false;
            dispatch('remove');
        }
    }

    async function addToList() {
        saving = true;
        let url = '/api/list/' + item.id + '/add';
        const response = await fetch(url, {
            method : 'POST'
        });
        const json = await response.json();
        if (json.result) {
            saving = false;
            saved = true;
        }
    }

    async function reserveItem() {
        reserving = true;
        let url = '/api/dashboard/' + item.id;
        const response = await fetch(url, {
            method : 'POST'
        });
        const json = await response.json();
        if (json.result) {
            reserving = false;
            reserved = true;
        }
    }

</script>
<div class="absolute bottom-0 right-0 z-50">
    <button class="inline-block p-2 mb-2" on:click={() => actions = !actions}>
        <svg class="w-6 h-6">
            <use xlink:href="#icon--actions"></use>
        </svg>
    </button>
</div>
<ul class="{actions ? '' : 'hidden'} absolute bg-gray-200 right-0 left-0 bottom-0 p-2 transform translate-y-full z-50 triangle shadow">
    <li class="mb-2">
        <button class="button mx-auto" on:click={reserveItem}>
            {#if reserving}
                Reserving ...
            {:else if reserved}
                Reserved!
            {:else}
                Reserve
            {/if}
        </button>
    </li>
    {#if canRemove}
        <li class="mb-2">
            <button class="button bg-red-100 border-red-500 mx-auto" on:click={removeFromList}>
                {#if removing}
                    Removing ...
                {:else}
                    Remove
                {/if}
            </button>
        </li>
    {:else}
        <li class="">
            <button class="button bg-white mx-auto { saved ? 'bg-green-100' : ''}" on:click={addToList}>
                {#if saving}
                    Saving ...
                {:else if saved}
                    Saved!
                {:else}
                    Add to List
                {/if}
            </button>
        </li>
    {/if}
</ul>
