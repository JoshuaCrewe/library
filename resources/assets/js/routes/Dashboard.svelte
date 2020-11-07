<script>
    import {onMount} from 'svelte';

    import {push} from 'svelte-spa-router'
    let loading = true;
    let json = {};
    async function getDashboard() {
        loading = true;

        let url = window.location.origin + '/api/dashboard';
        console.log(url);
        const response = await fetch(url);
        json = await response.json();

        if (!json.results.length) {
            push(`/login`)
        }

        loading = false;
    }
    
    onMount(() => {
        getDashboard()
    })

</script>
<div class="min-h-screen">
    <section class="layout">
        <div class="center py-20">
            {#if !loading}
                <h1 class="text-3xl">
                    {json.results[0]}
                </h1>
            {/if}
        </div>
    </section>
</div>
