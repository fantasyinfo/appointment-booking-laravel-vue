<?php

namespace App\Services;

use App\Jobs\SendAppintmentBookingEmail;
use App\Mail\AppointmentBook;
use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

    public function getAllBookings($request)
    {
        try {
          
            
            $bookingsLists = $this->appointmentRepo->getAll(auth('sanctum')->user()->id,$request);

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


}