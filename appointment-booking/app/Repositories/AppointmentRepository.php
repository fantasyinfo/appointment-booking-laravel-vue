<?php

namespace App\Repositories;

use App\Models\Appointment;


class AppointmentRepository
{

    public function create($data)
    {
        return Appointment::create($data);
    }

    // duplicate ck
    public function existsForUser($userId, $dateTimeUTC)
    {
        return Appointment::where('user_id', $userId)
            ->where('date_time', $dateTimeUTC)
            ->exists();
    }

    // create guests
    public function createGuests($appointment, $guests)
    {
        $guestData = [];
        foreach ($guests as $email) {
            $guestData[] = ['email' => $email];
        }

        return $appointment->guests()->createMany($guestData);
    }

    // get all bookings of a user
    public function getAll($userId, $request)
    {

        // \DB::connection()->enableQueryLog();

        $query = Appointment::query();

        $query->where('user_id', $userId)->with('guests');

        // created Date filter
        if ($request->has('createdDate') && in_array(strtolower($request->createdDate), ['asc', 'desc'])) {
            $query->orderBy('created_at', $request->createdDate);
        }

        // upcoming date filter

        if ($request->has('upcoming') && in_array(strtolower($request->upcoming), ['asc', 'desc'])) {
            $query->orderBy('date_time', $request->upcoming);
        }

        // default
        if (!$request->has('createdDate') && !$request->has('upcoming')) {
            $query->orderBy('date_time', 'asc');
        }

        $data = $query->get();


        // \Log::info('Query ', \DB::getQueryLog());
        return $data;
    }

    public function delete($userId, $id)
    {
        return Appointment::where('id', $id)->where('user_id', $userId)->delete();
    }

    public function findAppointmentById($id)
    {
        return Appointment::find($id);
    }

}