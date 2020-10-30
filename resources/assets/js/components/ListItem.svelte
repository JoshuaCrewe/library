<script>
    export let item;
    let removed = false;
    let removing = false;

    async function removeFromList() {
        removing = true;
        let url = '/api/list/' + item.id + '/remove/';
        const response = await fetch(url, {
            method : 'POST'
        });
        const json = await response.json();
        if (json.result) {
            removing = false;
            removed = true;
        }
    }
</script>
<div class="flex hover:bg-gray-100 py-4 px-2 flex-col text-center" class:hidden={removed}>
    <div class="mb-2">
        <img src="{item.image}" alt="{item.title}" class="mb-0 w-full">
    </div>
    <div class="px-2">
        <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-6 lg:text-2xl md:mb-4">
            {item.title}
        </h2>
        <h3 class="text-base mb-2 leading-6 body-font md:text-lg md:mb-4">
            {item.author}
        </h3>
    </div>
    <div class="flex items-center hidden">
        <button class="button bg-green-100 border-green-500 mx-auto">
            Reserve
        </button>
    </div>
    <div class="">
        <button class="button bg-red-100 border-red-500 mx-auto" on:click={removeFromList}>
            {#if removing}
                Removing ...
            {:else}
                Remove
            {/if}
        </button>
    </div>
    <div class="">
        <div class="inline-block mx-auto mt-2">
            <a class="button" href="#/item/{item.id}">
                View Item
            </a>
        </div>
    </div>
</div>
