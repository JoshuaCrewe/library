<script>
    export let item;
</script>
<a href="#/item/{item.id}" class="flex hover:bg-gray-100 py-4 px-2 flex-col text-center">
    <div class="mb-2">
        <img src="{item.image}" alt="{item.title}" class="mb-0 w-full">
    </div>
    <div class="px-2">
        <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-6 lg:text-2xl md:mb-4">
            {item.title}
        </h2>
        <h3 class="text-base mb-2 leading-4 body-font md:text-lg md:mb-4">
            {item.author}
        </h3>
    </div>
    <div class="flex items-center hidden">
        <button class="button bg-green-100 border-green-500 mx-auto">
            Reserve
        </button>
    </div>
</a>
