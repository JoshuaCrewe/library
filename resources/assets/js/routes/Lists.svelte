<script>
    import {onMount} from 'svelte';

    import {location} from 'svelte-spa-router';

    import ListItem from '../components/ListItem.svelte';
    import Form from './../components/Form.svelte';

    export let params = {}

    let loading = true;
    let empty = null;
    let json = {};
    let data = {};
    async function getItems() {
        loading = true;

        let url = window.location.origin + '/api/lists';
        const response = await fetch(url);
        json = await response.json();

        data = json.results;

        if (data.items.length == 0) {
            empty = true;
        } else {
            empty = false;
        }

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

        {#if empty }
            <p class="text-center">
                It looks like there are no items in your list. Are you sure you are <a class="underline" href="#/login/" >logged in</a> ?
            </p>
        {/if}
        <ul class="flex flex-wrap">
            {#if ! loading}
                {#each json.results.items as item}
                    <li class="w-1/2 lg:w-1/3" class:hidden={item.hidden}>
                        <ListItem {item} on:remove={() => item.hidden = true} />
                    </li>
                {/each}
            {/if}
        </ul>
    </section>
</div>
