<p>Dear <strong>{{ $appointment->user->name }}</strong>,</p>
<p>Your appointment titled <strong>{{ $appointment->title }}</strong> has been successfully booked for
    <strong>{{ \Carbon\Carbon::parse($appointment->date_time)->toDateTimeString() }}</strong> </p>
<p>Description: {{ $appointment->description }}</p>
<h3>Guests:</h3>
<ul>
    @foreach ($appointment->guests as $guest)
        <li> {{ $guest->email }}</li>
    @endforeach
</ul>
Thank you!