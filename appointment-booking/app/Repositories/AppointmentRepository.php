<?php

namespace App\Repositories;

use App\Models\Appointment;


class AppointmentRepository
{

    public function create($data)
    {
        return Appointment::create($data);
    }

    public function existsForUser($userId, $dateTimeUTC)
    {
        return Appointment::where('user_id', $userId)
            ->where('date_time', $dateTimeUTC)
            ->exists();
    }

    public function createGuests($appointment, $guests)
    {
        $guestData = [];
        foreach ($guests as $email) {
            $guestData[] = ['email' => $email];
        }

        return $appointment->guests()->createMany($guestData);
    }

}