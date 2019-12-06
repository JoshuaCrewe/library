<script>
    export let item;

    console.log(item);
</script>
<a href="#/item/{item.id}" class="item">
    <div class="item-image">
        <img src="{item.image}" alt="{item.title}">
    </div>
    <div class="item-details">
        <h2 class="item-title">
            {item.title}
        </h2>
        <h3 class="item-author delta body-font">
            {item.author}
        </h3>
        <p class="item-format">
            {item.format}
        </p>
        <p>
            {item.summary}
        </p>
    </div>
</a>

<style>
    .item {
        display: flex;
        align-items: center;
        width : 100%;
        flex: 1 1 100%;
        padding: 1rem 0;
        margin-bottom: 2rem;
    }

    .item-title {
        margin-bottom: .25rem;
    }

    .item-author {
        margin-bottom: .25rem;
    }

    .item-image {
        position: relative;
        flex-basis: 25%;
        margin-bottom: 0;
        padding-bottom: 40%;
    }

    .item-image img {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        object-fit: cover;
    }

    .item-details {
        flex-basis: 75%;
        flex: 2;
        padding-left: 8rem;
    }

    .item-format {
        color: #ccc;
        display: inline-block;
    }

    @media (max-width: 900px) {
        .item-image {
            flex-basis: 33%;
            padding-bottom: 50%;
        }
        .item-details {
            padding-left: 4rem;
        }

    }

    @media (max-width: 600px) {
        .item {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            padding-bottom: 0;
            margin-bottom: 2rem;
        }

        .item-image img {
            position: relative;
        }

        .item-details {
            padding-left: 0rem;
        }

    }
</style>
