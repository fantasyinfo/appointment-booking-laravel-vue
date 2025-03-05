<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Jobs\SendReminderEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nowUtc = Carbon::now('UTC')->format('Y-m-d H:i:00'); // Ensure precise minute matching

        Log::info("Current UTC Time ", [$nowUtc]);

        $appointments = Appointment::where('reminder_time', $nowUtc)
            ->where('reminder_sent', false)
            ->with(['user', 'guests']) // Fetch related user and guests
            ->get();

        Log::info("Appointments Found: ", [$appointments ]);
        foreach ($appointments as $appointment) {

            // Dispatch job to send reminder emails
            dispatch(new SendReminderEmail($appointment));

            // Mark the reminder as sent to prevent duplicate emails
            $appointment->update(['reminder_sent' => true]);

            Log::info("Reminder sent for Appointment ID: {$appointment->id}");
        }
    }
}
