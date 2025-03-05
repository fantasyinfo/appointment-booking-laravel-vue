import axios from "axios";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const router = useRouter();
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

// delete existing booking with id
export const deleteExistingBooking = async (id) => {
    try {
        const authStore = useAuthStore();
        const token = authStore.token;

        const response = await axios.delete(`/api/appointment-booking/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
            },
        });

        return response?.data;
    } catch (error) {
        console.error("Delting Booking error:", error?.response?.data);
        throw error?.response?.data;
    }
};
// logout
export const logoutUser = async () => {
    try {
        const authStore = useAuthStore();
        const token = authStore.token;

        if (!token) {
            throw new Error("No authentication token found");
        }

        const response = await axios.post(
            `/api/auth/logout/`,
            {}, 
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            }
        );

        authStore.token = null; 
        authStore.user = null;

        localStorage.removeItem("token");
        localStorage.removeItem("user");

        return response;
    } catch (error) {
        console.error("Logout error:", error?.response?.data);
        throw error?.response?.data;
    }
};
