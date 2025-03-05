import {defineStore} from 'pinia';
import axios from 'axios'

export const useAuthStore = defineStore('aut',{
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
    }),
    
    actions: {

        // login method
        async login(credentials) {
            try {
                const response = await axios.post('/api/auth/login', credentials);
                this.token = response.data.token;
                localStorage.setItem('token',this.token);
            } catch (error) {
                throw error.response.data.message;
            }
        },

        // logout
        async logout() {
            try{
                localStorage.removeItem('token');
            }catch(error){
                throw error.response.data.message;
            }
        }
    }
})