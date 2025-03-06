<template>
    <div class="container mx-auto px-4 py-6 max-w-md">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Create Booking</h2>
        <p class="text-sm md:text-base text-gray-600 mb-6">Schedule your event and invite guests</p>

        <form @submit.prevent="submitBooking" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input v-model="title" type="text" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea v-model="description" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Date & Time</label>
                <input v-model="date_time" type="datetime-local" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reminder Date & Time</label>
                <input v-model="reminder_time" type="datetime-local"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Guest Emails (comma-separated)</label>
                <input v-model="guests" type="text" placeholder="john@example.com, jane@example.com"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-300">
                    Create Booking
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue'
import { newBooking } from '../utils/Booking.Api.js'
import { useToast } from "vue-toastification"



const title = ref('')
const description = ref('')
const date_time = ref('')
const reminder_time = ref('')
const guests = ref('')


const toast = useToast();

const emit = defineEmits(['bookingCreated'])

const submitBooking = async () => {
    try {
        if (!title.value || !description.value || !date_time.value) {
            return toast.error("All fields are required");
        }

        let guestList = guests.value
            .split(",") // Split by comma
            .map(email => email.trim()) // Trim spaces
            .filter(email => email.length > 0); // Remove empty values


        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!guestList.every(email => emailRegex.test(email))) {
            return toast.error("One or more guest emails are invalid.");
        }




        const bookingResponse = await newBooking({
            title: title.value,
            description: description.value,
            date_time: new Date(date_time.value).getTime(),
            reminder_time: reminder_time.value ? new Date(reminder_time.value).getTime() : null,
            guests: guestList,
        });

        if (bookingResponse?.data?.error) {
            return toast.error(bookingResponse?.data?.message)
        }
        toast.success("Booking successful, and email has been send with booking details.");

        // emit 
        emit('bookingCreated');

        // empty the form now
        title.value = '';
        description.value = '';
        date_time.value = '';
        reminder_time.value = '';
        guests.value = '';
    } catch (error) {

        // validation errors
        if (error?.errors) {
            const validationErrors = error?.errors;

            if (validationErrors.title) toast.error(validationErrors?.title[0]);
            if (validationErrors.description) toast.error(validationErrors?.description[0]);
            if (validationErrors.date_time) toast.error(validationErrors?.date_time[0]);
            if (validationErrors.reminder_time) toast.error(validationErrors?.reminder_time[0]);
            if (validationErrors.guests) toast.error(validationErrors?.guests[0]);
            return;
        }

        if (error.error) {
            toast.error(error?.message)
            return;
        }
        toast.error(error?.response?.data?.message || "Something went wrong.");

        if (!error?.response) {
            toast.error("Network error. Server not responding.");
            return;
        }


    }
}
</script>
