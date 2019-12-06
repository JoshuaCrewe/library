import { writable } from "svelte/store";

export const items = writable({
    currentSearch: "",
    results: []
});

export default items;
