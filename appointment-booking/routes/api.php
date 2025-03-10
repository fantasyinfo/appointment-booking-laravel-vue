<?php

use App\Http\Controllers\AppointmentBookingController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/tt', function () {
    return 'Hello';
});


// Auth Routes
Route::post('/auth/register', [AuthController::class, 'registerUser'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'loginUser'])->name('auth.login');

// Protected routes 
Route::middleware('api.auth')->group(function () {
    // lgout route
    Route::post('/auth/logout', [AuthController::class, 'logoutUser'])->name('auth.logout');

    // appointment routes
    Route::post('/appointment-booking', [AppointmentBookingController::class, 'newAppointmentBooking'])->name('appointment.booking');

    Route::get('/appointment-booking', [AppointmentBookingController::class, 'getAllAppointmentBookings'])->name('appointment.all.booking');

    Route::delete('/appointment-booking/{id}', [AppointmentBookingController::class, 'deleteAppointment'])->name('appointment.delete');
});
