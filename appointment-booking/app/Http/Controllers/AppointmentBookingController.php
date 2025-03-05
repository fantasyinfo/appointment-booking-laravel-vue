<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentBookingController extends Controller
{
    //
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    // new booking
    public function newAppointmentBooking(AppointmentRequest $appointmentRequest)
    {
        $response = $this->appointmentService->appointmentBooking($appointmentRequest->validated());


        if (!empty($response['error'])) {
            return response()->json([
                "error" => true,
                "message" => $response['message'],
            ], 400);
        }
        return response()->json([
            "error" => false,
            "message" => "New Appointment Book Successfully.",
            "data" => $response

        ], 201);
    }

    public function getAllAppointmentBookings(Request $request)
    {
        $response = $this->appointmentService->getAllBookings($request);


        if (!empty($response['error'])) {
            return response()->json([
                "error" => true,
                "message" => $response['message']
            ], 400);
        }
        return response()->json([
            "error" => false,
            "message" => "All Appointment Bookings Lists.",
            "data" => $response

        ], 200);
    }

    public function deleteAppointment($id)
    {

        // check valid id
        $isFound = $this->appointmentService->getAppointmentById($id);

        if (!empty($isFound['error'])) {
            return response()->json([
                "error" => true,
                "message" => $isFound['message'],
            ], 400);
        }

        // delete now
        $response = $this->appointmentService->deleteBooking($isFound['appointment']);

        if (!empty($response['error'])) {
            return response()->json([
                "error" => true,
                "message" => $response['message'],
            ], 400);
        }
        return response()->json([
            "error" => false,
            "message" => "Appointment Deleted Successfully.",
            "data" => $response

        ], 200);
    }
}
