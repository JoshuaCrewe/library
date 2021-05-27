<script>
    import {onMount} from 'svelte';
    import Loan from '../components/Loan.svelte';
    import Reservation from '../components/Reservation.svelte';

    import {push} from 'svelte-spa-router'
    let loading = true;
    let json = {};
    async function getDashboard() {
        loading = true;

        let url = window.location.origin + '/api/dashboard';
        console.log(url);
        const response = await fetch(url);
        json = await response.json();

        if (json.welcome == '') {
            /* push(`/login`) */
        }

        console.log(json.loans);

        loading = false;
    }
    
    onMount(() => {
        getDashboard()
    })

</script>
<div class="min-h-screen">
    {#if !loading}
        <section class="layout">
            <div class="center pt-20 pb-10">
                <h1 class="text-3xl">
                    {json.welcome}
                </h1>
            </div>
        </section>
        <div class="layout w-full md:w-3/4 m-auto max-w-3xl" id="list">
            <h2 class="text-4xl mb-8 text-center">Loans</h2>
            <ul class="">
                {#each json.loans as loan}
                    <li>
                        <Loan {loan} />
                    </li>
                {/each}
            </ul>
            <h2 class="text-4xl mb-8 mt-8 text-center">Reservations</h2>
            <ul class="">
                {#each json.reservations as item}
                    <li>
                        <Reservation {item} />
                    </li>
                {/each}
            </ul>
        </div>
    {:else}
        <seciton class="layout">
            <div class="w-full md:w-3/4 m-auto max-w-3xl pt-20" id="list">
                <div class="center">
                    <h2 class="text-4xl">
                        L O A D I N G . . .
                    </h2>
                </div>
            </div>
        </seciton>
    {/if}
</div>
