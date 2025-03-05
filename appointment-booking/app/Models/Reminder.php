<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'email',
        'sent_at',
    ];

    // Relationship with Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
