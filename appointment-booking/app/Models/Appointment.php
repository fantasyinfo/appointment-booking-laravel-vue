<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date_time',
        'timezone',
        'reminder_time',
        'reminder_sent',
    ];

    protected function casts()
    {
        return ['date_time' => 'datetime', 'timezone' => 'string'];

    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Guests
    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
