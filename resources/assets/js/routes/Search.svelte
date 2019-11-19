<style>
    h1 {
        text-align: center;
    }

    h2 {
        margin-bottom: 0;
    }

    .books {
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        max-width: 600px;
    }

    .book {
        display: flex;
        width : 100%;
        flex: 1 1 100%;
        padding: 1rem 0;
    }

    .book-image {
        position: relative;
        padding-bottom: 75%;
        flex: 1;
    }

    .book-details {
        flex: 1;
padding-left: 1rem;
    }

    .book-image img {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        object-fit: cover;
    }
    
    .book-format {
        border: 1px solid green;
        border-radius: 5px;
        color: green;
        display: inline-block;
        padding: .25rem .5rem;
    }


</style>

<h1>Library</h1>

<Form on:books={handleResults}/>

{#if data}
    <div class="books" transition:fade>
        {#each data as book}
            <a href="/item/{book.id}" class="book" use:link>
                <div class="book-image">
                    <img src="{book.image}" alt="{book.title}">
                </div>
                <div class="book-details">
                    <h2>
                        {book.title}
                    </h2>
                    <p class="book-format">
                        {book.format}
                    </p>
                </div>
            </a>
        {/each}
    </div>
{/if}

<script>
    import Form from './../components/Form.svelte'
    import { fade } from 'svelte/transition';
    import {link} from 'svelte-spa-router'

    let data;

    const handleResults = (response) => {
        data = response.detail.books.results;
    }
</script>
