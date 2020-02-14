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
