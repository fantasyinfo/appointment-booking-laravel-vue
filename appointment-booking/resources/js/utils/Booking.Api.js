import axios from "axios";
import { useAuthStore } from "../stores/auth";

// create new booking
export const newBooking = async (bookingData) => {
    try {
        const authStore = useAuthStore();
        const token = authStore.token;

        const response = await axios.post(
            "/api/appointment-booking",
            bookingData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            }
        );

        return response?.data;
    } catch (error) {
        console.error("Booking error:", error?.response?.data);
        throw error?.response?.data;
    }
};

// get all bookings
export const getAllBookings = async (sortParams = {}) => {
    try {
        const authStore = useAuthStore();
        const token = authStore.token;

        const queryParams = new URLSearchParams(sortParams).toString();
        const url = queryParams
            ? `/api/appointment-booking?${queryParams}`
            : "/api/appointment-booking";

        const response = await axios.get(url, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
            },
        });

        return response?.data;
    } catch (error) {
        console.error("Fetching Booking error:", error?.response?.data);
        throw error?.response?.data;
    }
};
