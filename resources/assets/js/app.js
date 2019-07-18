import App from "./component.svelte";

const app = new App({
    target: document.body,
    props: {
        rounded: true
    }
});

export default app;
