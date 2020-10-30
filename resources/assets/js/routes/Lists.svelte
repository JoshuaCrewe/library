<script>
    import {onMount} from 'svelte';

    import {location} from 'svelte-spa-router';

    import ListItem from '../components/ListItem.svelte';
    import Form from './../components/Form.svelte';

    export let params = {}

    let loading = true;
    let json = {};
    let data = {};
    async function getItems() {
        loading = true;

        let url = window.location.origin + '/api/lists/';
        const response = await fetch(url);
        json = await response.json();
        data = json.results;

        loading = false;
    }

    onMount(() => {
        getItems()
    })

</script>
<div class="min-h-screen">
    <div class="">
        <Form {params} />
    </div>
    <section class="layout w-full md:w-3/4 m-auto max-w-3xl" id="list">
        <ul class="flex flex-wrap">
            {#if ! loading}
                {#each json.results.items as item}
                    <li class="w-full sm:w-1/2 lg:w-1/3">
                        <ListItem {item} />
                    </li>
                {/each}
            {/if}
        </ul>
    </section>
</div>
