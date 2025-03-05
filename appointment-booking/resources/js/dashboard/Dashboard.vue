<template>
    <div class="flex min-h-screen bg-gray-100 my-3 mx-3 rounded-2xl">
        <div class="w-1/3 p-8 bg-white shadow-md">
            <BookingForm />
        </div>
        <div class="w-2/3 p-8">
            <BookingsTable />
        </div>
    </div>
</template>


<script setup>
import { ref } from 'vue'
import BookingForm from './BookingForm.vue'
import BookingsTable from './BookingsTable.vue'


</script>
