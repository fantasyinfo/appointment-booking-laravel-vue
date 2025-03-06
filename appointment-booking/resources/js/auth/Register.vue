<template>

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto"
                src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Register your account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit.prevent="register" method="POST">
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Full Name</label>
                    <div class="mt-2">
                        <input type="text" v-model="name" name="full_name" id="full_name" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>
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
                    <label for="timezone" class="block text-sm font-medium text-gray-900">Timezone</label>
                    <select v-model="timezone" id="timezone" required
                        class="mt-2 block w-full rounded-md border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option v-for="tz in timezones" :key="tz" :value="tz">
                            {{ tz }}
                        </option>
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                        in</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm/6 text-gray-500">
                already a member? <router-link to="/login" class="text-blue-600 hover:underline">
                    Login
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth.js'
import { useToast } from "vue-toastification"
import moment from 'moment-timezone'

const name = ref('');
const email = ref('');
const password = ref('');
const timezone = ref('');
const timezones = ref([]);
const authStore = useAuthStore();
const toast = useToast();

const register = async () => {
    try {
        if (!name.value || !email.value || !password.value || !timezone.value) {
            return toast.error("All fields are required");
        }

        const registerResponse = await authStore.register({
            name: name.value,
            email: email.value,
            password: password.value,
            timezone: timezone.value
        });

        if (registerResponse?.data?.error) {
            return toast.error(registerResponse?.data?.message)
        }
        toast.success("Registration successful, please login now.");
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


onMounted(() => {
    // Load timezone list
    timezones.value = moment.tz.names();

    // Get user's timezone
    let userTimezone = moment.tz.guess();

    // Ensure 'Asia/Kolkata' is used instead of 'Asia/Calcutta'
    timezone.value = userTimezone === "Asia/Calcutta" ? "Asia/Kolkata" : userTimezone;
   //timezone.value = userTimezone
});



</script>