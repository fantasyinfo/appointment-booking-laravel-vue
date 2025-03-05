<?php

namespace App\Jobs;

use App\Http\Controllers\AppointmentBookingController;
use App\Mail\AppointmentBook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendAppintmentBookingEmail implements ShouldQueue
{
    use Queueable;

    public $email;
    public $appointment;


    /**
     * Create a new job instance.
     */
    public function __construct($email, $appointment)
    {
        $this->email = $email;
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Mail::to($this->email)->send(new AppointmentBook($this->appointment));
    }
}
