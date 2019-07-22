<style>
    h1 {
        text-align: center;
    }

    .books {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }

    .book {
        flex: 1 1 25%;
        width: 25%;
    }
</style>

<h1>Library</h1>

<Form  on:books={handleResults}/>

{#if data}
    <div class="books">
        {#each data as book}
            <div class="book">
                <img src="{book.image}" alt="{book.title}">
                <h2>
                    {book.title}
                </h2>
            </div>
        {/each}
    </div>
{/if}

<script>
    import Form from './components/Form.svelte'

    let data;

    const handleResults = (response) => {
        data = response.detail.books.results;
    }
</script>
