import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";
import router from "./router/Router.js";

const _app = createApp(App);
_app.use(router);
_app.use(createPinia());
_app.mount("#app");
