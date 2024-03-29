import { writable } from "svelte/store";

export const items = writable({
    currentSearch: "",
    results: [],
    page: 1
});

export const page = writable(1);

export default items;
