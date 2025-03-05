<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;


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
                "message" => $response['message']
            ], 400);
        }
        return response()->json([
            "error" => false,
            "message" => "New Appointment Book Successfully.",
            "data" => $response

        ], 201);
    }
}
