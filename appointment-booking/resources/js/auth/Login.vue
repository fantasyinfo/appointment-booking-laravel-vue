<template>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto"
                src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Login to your account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit.prevent="login" method="POST">

                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input type="email" v-model="email" name="email" id="email" autocomplete="email" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>

                    <div class="mt-2">
                        <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                        <input type="password" v-model="password" name="password" id="password"
                            autocomplete="current-password" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                        in</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm/6 text-gray-500">
                Register as a member? <router-link to="/register" class="text-blue-600 hover:underline">
                    Register
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth.js'
import { useToast } from "vue-toastification"
import { useRouter } from 'vue-router'


const email = ref('');
const password = ref('');
const authStore = useAuthStore();
const toast = useToast();
const router = useRouter();

// redirect to dashboad
if(authStore.user) {
    router.push('/dashboard')
}

const login = async () => {
    try {
        if (!email.value || !password.value) {
            return toast.error("All fields are required");
        }

        const loginResponse = await authStore.login({
            email: email.value,
            password: password.value,
        });

        if (loginResponse?.data?.error) {
            return toast.error(loginResponse?.data?.message)
        }

        toast.success("Login successful.");
        router.push('/dashboard')
    } catch (error) {

        // validation errors
        if (error.errors) {
            const validationErrors = error.errors;

            if (validationErrors.email) toast.error(validationErrors.email[0]);
            if (validationErrors.password) toast.error(validationErrors.password[0]);
            return;
        }

        if (!error.response) {
            toast.error("Network error. Server not responding.");
            return;
        }

        toast.error(error.response.data.message || "Something went wrong.");
    }
};

</script>