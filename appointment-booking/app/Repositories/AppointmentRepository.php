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
        $query = Appointment::query();
        $query->where('user_id', $userId)->with('guests');
    
        // Extract sorting filters from the request
        $sortFilters = [];
    
        if ($request->has('createdDate') && in_array(strtolower($request->createdDate), ['asc', 'desc'])) {
            $sortFilters['createdDate'] = $request->createdDate;
        }
    
        if ($request->has('upcoming') && in_array(strtolower($request->upcoming), ['asc', 'desc'])) {
            $sortFilters['upcoming'] = $request->upcoming;
        }
    
        // Apply sorting dynamically
        if (!empty($sortFilters)) {
            foreach ($sortFilters as $column => $direction) {
                $sortColumn = $column === 'upcoming' ? 'date_time' : 'created_at';
                $query->orderBy($sortColumn, $direction);
            }
        } else {
            // Default sorting by date_time if no filters are provided
            $query->orderBy('date_time', 'asc');
        }
    
        return $query->get();
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