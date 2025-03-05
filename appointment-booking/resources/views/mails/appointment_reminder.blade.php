<p>Dear <strong>{{ $appointment->user->name }}</strong>,</p>
<p>This is a reminder for your upcoming appointment titled <strong>{{ $appointment->title }}</strong>  scheduled for
    <strong>{{ \Carbon\Carbon::parse($appointment->date_time)->toDateTimeString() }}</strong> </p>

Thank you!