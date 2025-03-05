import axios from "axios";
import { useAuthStore } from "../stores/auth";

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
