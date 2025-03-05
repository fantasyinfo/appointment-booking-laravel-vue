import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";
import router from "./router/Router.js";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const _app = createApp(App);
_app.use(router);
_app.use(createPinia());
_app.use(Toast, {
    position: "top-right", 
    timeout: 3000, 
    closeOnClick: true, 
});
_app.mount("#app");
