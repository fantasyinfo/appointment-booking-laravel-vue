import { createRouter, createWebHistory } from "vue-router";

// auth files
import Register from "../auth/Register.vue";
import Login from "../auth/Login.vue";

// dasboard files

import Dashboard from "../dashboard/Dashboard.vue";

const routes = [
    {
        path: "/",
        redirect: "/login",
    },
    {
        path: "/register",
        component: Register,
    },
    {
        path: "/login",
        component: Login,
    },
    {
        path: "/dashboard",
        component: Dashboard,
        meta: {
            requiresAuth: true,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuth = localStorage.getItem("token");

    if (to.meta.requiresAuth && !isAuth) {
        next("/login");
    } else {
        next();
    }
});

export default router;