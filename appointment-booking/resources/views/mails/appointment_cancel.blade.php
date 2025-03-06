<p>Dear <strong>{{ $appointment->user->name }}</strong>,</p>
<p>Your appointment titled <strong>{{ $appointment->title }}</strong> schedule for
    <strong>  {{ \Carbon\Carbon::parse($appointment->date_time)
        ->setTimezone($appointment->user->timezone ?? config('app.timezone'))
        ->format('Y-m-d H:i A') }}</strong> has been cancelled.</p>
<p>We regret any inconvenience caused.</p>

Thank you!
