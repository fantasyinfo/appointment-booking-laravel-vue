<template>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 md:my-3 md:mx-3 md:rounded-2xl">
        <div class="w-full md:w-1/3 p-4 md:p-8 bg-white md:shadow-md">
            <BookingForm @bookingCreated="refreshBookings"  />
        </div>
        <div class="w-full md:w-2/3 p-4 md:p-8">
            <BookingsTable  ref="bookingsTableRef" />
        </div>
    </div>
</template>


<script setup>
import { ref } from 'vue'
import BookingForm from './BookingForm.vue'
import BookingsTable from './BookingsTable.vue'


const bookingsTableRef = ref(null);

const refreshBookings = () => {
    if (bookingsTableRef.value) {
        bookingsTableRef.value.fetchBookings(); // Refresh the bookings list
    }
}

</script>
