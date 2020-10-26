<script>
    import {onMount} from 'svelte';

    import {location} from 'svelte-spa-router';

    let loading = true;
    let json = {};
    let data = {};
    async function getItems() {
        loading = true;

        let url = window.location.origin + '/api/lists/';
        const response = await fetch(url);
        json = await response.json();
        data = json.results;

        console.log(json);
        loading = false;
    }

    onMount(() => {
        getItems()
    })

</script>
<div class="min-h-screen">
    <section class="layout">
        <div class="center py-20">
            {#if ! loading}
                {#each json.results.items as item}
                    <h2>
                        {item.title}
                    </h2>
                {/each}
            {/if}
        </div>
    </section>
</div>
