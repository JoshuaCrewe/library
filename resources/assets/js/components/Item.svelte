<script>
    export let item;
    let saving = false;
    let saved = false;

    async function addToList() {
        saving = true;
        let url = '/api/list/' + item.id + '/add/';
        const response = await fetch(url, {
            method : 'POST'
        });
        const json = await response.json();
        if (json.result) {
            saving = false;
            saved = true;
        }
    }

</script>
<a href="#/item/{item.id}" class="flex hover:bg-gray-100 py-4 px-2">
    <div class="w-1/3 pr-4">
        <img src="{item.image}" alt="{item.title}" class="mb-0">
    </div>
    <div class="w-2/3">
        <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-8 lg:text-2xl md:mb-4">
            {item.title}
        </h2>
        <h3 class="text-base mb-2 leading-4 body-font md:text-lg md:mb-4">
            {item.author}
        </h3>
        <p class="text-xs sm:text-sm bg-green-200 rounded px-1 inline-block mb-2">
            {item.format}
        </p>
        <p class="text-sm leading-5 sm:text-base">
            {item.summary}
        </p>
    </div>
</a>
<button class="button { saved ? 'bg-green-100' : ''}" on:click={addToList}>
    {#if saving}
        Saving ...
    {:else if saved}
        Saved!
    {:else}
        Add to List
    {/if}
</button>
