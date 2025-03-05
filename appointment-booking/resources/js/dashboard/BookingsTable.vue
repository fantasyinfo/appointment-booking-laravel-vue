<template>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="p-4 bg-gray-50 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <h2 class="text-xl font-semibold text-gray-800">Bookings</h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <button @click="sortByDate" class="px-4 py-2 bg-blue-500 text-white rounded">Sort by Date</button>
                <button @click="sortByUpcoming" class="px-4 py-2 bg-green-500 text-white rounded ml-2">Sort by
                    Upcoming</button>
            </div>
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 text-left hidden sm:table-cell">Title</th>
                        <th class="py-3 px-4 text-left">Appointment Date</th>
                        <th class="py-3 px-4 text-left hidden md:table-cell">Guests</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <tr v-for="booking in bookingsLists" :key="booking?.id"
                        class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4 text-left hidden sm:table-cell">
                            <span class="font-medium">{{ booking?.title }}</span>
                        </td>
                        <td class="py-3 px-4 text-left">
                            <div class="flex flex-col">
                                <span class="sm:hidden font-semibold">{{ booking?.title }}</span>
                                {{ new Date(booking?.date_time).toLocaleString() }}
                            </div>
                        </td>
                        <td class="py-3 px-4 text-left hidden md:table-cell">
                            <div class="flex flex-wrap gap-1">
                                <span v-for="guest in booking?.guests" :key="guest?.id"
                                    class="bg-blue-100 text-blue-600 text-xs font-medium px-2 py-0.5 rounded">
                                    {{ guest?.email }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button @click="deleteBooking(booking?.id)"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- No Bookings State -->
        <div v-if="!bookingsLists || bookingsLists.length === 0" class="text-center py-10 text-gray-500">
            No bookings available
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { getAllBookings } from '../utils/Booking.Api.js'
import { useToast } from "vue-toastification"
const sortOptions = ref({ createdDate: "asc", upcoming: "asc" });

const toast = useToast();
const bookingsLists = ref([]);


const fetchBookings = async (sortParams = {}) => {
    try {

        const allBookings = await getAllBookings(sortParams);

        if (allBookings?.data?.error) {
            return toast.error(allBookings?.data?.message)
        }

        console.log(allBookings?.data?.appointments)
        bookingsLists.value = allBookings?.data?.appointments


    } catch (error) {

        if (error?.error) {
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

const deleteBooking = async (id) => {
    console.log(id)
}

const sortByDate = () => {
    sortOptions.createdDate = sortOptions.createdDate == 'desc' ? 'asc' : 'desc'
    fetchBookings(sortOptions)
}

const sortByUpcoming = () => {
    sortOptions.upcoming = sortOptions.upcoming == 'desc' ? 'asc' : 'desc'
    fetchBookings(sortOptions)
}

onMounted(() => fetchBookings(sortOptions));
</script>