<?php
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

// register user
it('Register a user', function () {

    $response = $this->postJson('/api/auth/register', [
        'name' => 'Gaurav Sharma',
        'email' => 'abc@gmail.com',
        'password' => Hash::make('Secret'),
        'timezone' => 'Asia/Kolkata',
    ]);

    $response->assertStatus(201)->assertJson(['error' => false]);

});

// book appointment
it('try to book an appointment', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->postJson('/api/appointment-booking', [
        'title' => 'Meeting with Client',
        'description' => 'Discuss project details',
        'date_time' => Carbon::now()->addDays(1)->timestamp * 1000,
        'reminder_time' => Carbon::now()->addDays(1)->subMinutes(30)->timestamp * 1000,
        'guests' => ['guest1@example.com', 'guest2@example.com'],

    ]);

    \Log::info('REsponse From Book Testing', [$response]);
    $response->assertStatus(201)
        ->assertJson(['error' => false]);
});

// cancel appointment
it('cancel an appointment', function () {

    Sanctum::actingAs(User::factory()->create());

    $response = $this->postJson('/api/appointment-booking', [
        'title' => 'Meeting with Client',
        'description' => 'Discuss project details',
        'date_time' => Carbon::now()->addDays(1)->timestamp * 1000,
        'reminder_time' => Carbon::now()->addDays(1)->subMinutes(30)->timestamp * 1000,
        'guests' => ['guest1@example.com', 'guest2@example.com'],

    ]);

    \Log::info('REsponse From Book Testing', [$response]);

    $id = $response->json('data.appointment.id');
    // check delete
    $deleteResponse = $this->deleteJson("/api/appointment-booking/$id");

    \Log::info('REsponse From Delete Book Testing', [$deleteResponse]);
    $deleteResponse->assertStatus(200)->assertJson(['error' => false]);

});
