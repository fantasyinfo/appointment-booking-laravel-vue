<?php

namespace App\Services;

use App\Jobs\SendAppintmentBookingEmail;
use App\Jobs\SendCancelAppintmentBookingEmail;
use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Exception;


class AppointmentService
{

    protected $appointmentRepo;

    // create auth repo object
    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
    }


    // book appointment
    public function appointmentBooking($data)
    {
        try {

            $userId = auth('sanctum')->id();

            // convert the timezone to UTC and save to db
            $dateTimeUTC = Carbon::parse($data['date_time'])->utc();

            // check for duplicate booking on the same date & time 

            if ($this->appointmentRepo->existsForUser($userId, $dateTimeUTC)) {
                return ['error' => true, 'message' => 'Booking already found, Duplicate entry.'];
            }



            // weekday validation 

            if (!$dateTimeUTC->isWeekday()) {
                return ['error' => true, 'message' => 'Appointment can only be on weekdays Monday to friday.'];
            }

            // check reminder time if provided then override the 30 minuts before

            $reminderTimeUTC = isset($data['reminder_time']) ? Carbon::parse($data['reminder_time'])->utc() : $dateTimeUTC->copy()->subMinutes(30);

            // create 
            $appointmentData = [
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
                'date_time' => $dateTimeUTC,
                'reminder_time' => $reminderTimeUTC,
            ];
            $appointment = $this->appointmentRepo->create($appointmentData);

            // create guests
            if (!empty($data['guests'])) {

                $this->appointmentRepo->createGuests($appointment, $data['guests']);
            }

            // send email notification of new booking to user and all guests if provided
            $this->sendAppointmentNotification($appointment);

            return [
                'error' => false,
                'message' => 'Booking successful',
                'appointment' => $appointment
            ];

        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }


    // send appointment booked notification
    public function sendAppointmentNotification($appointment)
    {
        SendAppintmentBookingEmail::dispatch(auth('sanctum')->user()->email, $appointment);

        // send email to guests also
        if (!empty($appointment->guests)) {
            foreach ($appointment->guests as $guestEmail) {
                SendAppintmentBookingEmail::dispatch($guestEmail, $appointment);
            }
        }
    }

    // get all bookings
    public function getAllBookings($request)
    {
        try {


            $bookingsLists = $this->appointmentRepo->getAll(auth('sanctum')->user()->id, $request);

            return [
                'error' => false,
                'message' => 'Booking successful',
                'appointments' => $bookingsLists
            ];
        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }



    }


    // delete booking
    public function deleteBooking($appointment)
    {
        try {

            // check the appointment date_time 

            $currentDatetime = Carbon::now()->utc();
            $appointmentTime = Carbon::parse($appointment->date_time)->utc();

            // check if current time is more then 30 minutes then delete, else return message, appointment do not delete if its in next 30 minutes

            if ($currentDatetime->diffInMinutes($appointmentTime, false) <= 30) {
                return [
                    'error' => true,
                    'message' => 'Appointment cannot be deleted within 30 minutes of its start time.',
                ];
            }


            if ($this->appointmentRepo->delete(auth('sanctum')->user()->id, $appointment->id)) {
                // send delete notifictions 
                $this->sendDeleteAppointmentNotification($appointment);
            }

            return [
                'error' => false,
                'message' => 'Booking Deleted',
            ];
        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }



    }

    // send delete appointment notification
    public function sendDeleteAppointmentNotification($appointment)
    {
         SendCancelAppintmentBookingEmail::dispatch(auth('sanctum')->user()->email, $appointment);

        // // send email to guests also
        if (!empty($appointment->guests)) {
            foreach ($appointment->guests as $guestEmail) {
                SendCancelAppintmentBookingEmail::dispatch($guestEmail, $appointment);
            }
        }
    }

    // get apointment by id
    public function getAppointmentById($id)
    {
        try {

            $appointment = $this->appointmentRepo->findAppointmentById($id);



            if (!$appointment) {
                return [
                    "error" => true,
                    "message" => "Appointment not found."
                ];
            }

            return [
                'error' => false,
                'message' => 'Appointment Details',
                'appointment' => $appointment
            ];

        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

}