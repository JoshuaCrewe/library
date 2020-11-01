<script>
    export let item;
    let removed = false;
    let canRemove = true;
    import Actions from './Actions';
</script>
<div class="relative flex hover:bg-gray-100 py-4 px-2 flex-col text-center" class:hidden={removed}>
    <div class="mb-2">
        <img src="{item.image}" alt="{item.title}" class="mb-0 w-full">
    </div>
    <div class="px-2">
        <a class="card-link" href="#/item/{item.id}">
            <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-6 lg:text-2xl md:mb-4">
                {item.title}
            </h2>
        </a>
        <h3 class="text-base mb-2 leading-6 body-font md:text-lg md:mb-4">
            {item.author}
        </h3>
    </div>

    <Actions {item} {canRemove} on:remove={() => removed = true} />
</div>
