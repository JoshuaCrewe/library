<script>
    export let item;
    import Actions from './Actions';
    import {link} from 'svelte-spa-router'
</script>
<div class="flex hover:bg-gray-100 py-4 px-2 relative">
    <div class="w-1/3 pr-4">
        <img src="{item.image}" alt="{item.title}" class="mb-0">
    </div>
    <div class="w-2/3">
        <a class="card-link" href="#/item/{item.id}">
            <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-8 lg:text-2xl md:mb-4">
                {item.title}
            </h2>
        </a>
        <h3 class="text-base mb-2 leading-4 body-font md:text-lg md:mb-4">
            <a class="z-10 relative" use:link href="/search/author:({item.author})">
                {item.author}
            </a>
        </h3>
        <p class="text-xs sm:text-sm bg-green-200 rounded px-1 inline-block mb-2">
            {item.format}
        </p>
        <p class="text-sm leading-5 sm:text-base">
            {item.summary}
        </p>
    </div>
    <Actions {item} />
</div>
