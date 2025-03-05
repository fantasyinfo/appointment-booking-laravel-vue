import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: JSON.parse(localStorage.getItem("user")) || null,
        token: localStorage.getItem("token") || null,
    }),

    actions: {
        // register
        async register(details) {
            try {
                const response = await axios.post(
                    "/api/auth/register",
                    details
                );
                return response?.data;
            } catch (error) {
                console.error("Registration error:", error?.response?.data);
                throw error?.response?.data;
            }
        },
        // login method
        async login(credentials) {
            try {
                const response = await axios.post(
                    "/api/auth/login",
                    credentials
                );

                this.token = response.data.token;
                this.user = response.data.user;

                // set details in localstorage to keep login
                localStorage.setItem("token", this.token);
                localStorage.setItem("user", JSON.stringify(this.user));
                return response;
            } catch (error) {
                throw error?.response?.data;
            }
        },

       
    },
});
