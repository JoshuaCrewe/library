<script>
    export let loan;
</script>
<div class="flex hover:bg-gray-100 py-4 px-2 relative">
    <div class="w-1/3 pr-4">
        <img src="{loan.image}" alt="{loan.title}" class="mb-0">
    </div>
    <div class="w-2/3">
        <a class="card-link" href="#/item/{loan.id}">
            <h2 class="text-base mb-2 leading-6 md:text-xl lg:leading-8 lg:text-2xl md:mb-4">
                {loan.title}
            </h2>
        </a>
        <h3 class="text-base mb-2 leading-4 body-font md:text-lg md:mb-4">
            {loan.author}
        </h3>
        <p class="text-xs sm:text-sm bg-green-200 rounded px-1 inline-block mb-2">
            Renewed : {loan.renewCount}
        </p>
        <p class="text-sm leading-5 sm:text-base">
            Due back : {loan.due}
        </p>

        <button class="button bg-green-100 border-green-500 mt-8 relative z-10">
            Renew
        </button>
    </div>
</div>
