<p>Dear <strong>{{ $appointment->user->name }}</strong>,</p>
<p>Your appointment titled <strong>{{ $appointment->title }}</strong> schedule for
    <strong>{{ \Carbon\Carbon::parse($appointment->date_time)->toDateTimeString() }}</strong> has been cancelled.</p>
<p>We regret any inconvenience caused.</p>

Thank you!
