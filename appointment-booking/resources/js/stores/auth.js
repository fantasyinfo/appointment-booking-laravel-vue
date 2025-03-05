import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("aut", {
    state: () => ({
        user: null,
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
                localStorage.setItem("token", this.token);
                return response;
            } catch (error) {
                throw error?.response?.data;
            }
        },

        // logout
        async logout() {
            try {
                localStorage.removeItem("token");
            } catch (error) {
                throw error?.response?.data;
            }
        },
    },
});
