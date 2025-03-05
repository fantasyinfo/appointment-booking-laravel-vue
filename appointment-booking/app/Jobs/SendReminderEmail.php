<?php

namespace App\Jobs;



use App\Mail\AppointmentReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendReminderEmail implements ShouldQueue
{
    use Queueable;

    public $email;
    public $appointment;


    /**
     * Create a new job instance.
     */
    public function __construct($appointment)
    {

        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // Send email to appointment creator
        Mail::to($this->appointment->user->email)->send(new AppointmentReminder($this->appointment));

        // Send email to all guests
        foreach ($this->appointment->guests as $guest) {
            Mail::to($guest->email)->send(new AppointmentReminder($this->appointment));
        }
    }
}
