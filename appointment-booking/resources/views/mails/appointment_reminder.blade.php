<p>Dear <strong>{{ $appointment->user->name }}</strong>,</p>
<p>This is a reminder for your upcoming appointment titled <strong>{{ $appointment->title }}</strong>  scheduled for
    <strong>  {{ \Carbon\Carbon::parse($appointment->date_time)
        ->setTimezone($appointment->user->timezone ?? config('app.timezone'))
        ->format('Y-m-d H:i A') }}</strong> </p>

Thank you!