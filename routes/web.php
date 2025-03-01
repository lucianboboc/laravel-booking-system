<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentDestroyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingEmployeeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\SlotsController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

//Carbon::setTestNow(now()->setTimeFromTimeString('09:00:00'));

Route::get('/', BookingController::class)
    ->name('bookings');

Route::get('/bookings/{employee:slug}', BookingEmployeeController::class)
    ->name('bookings.employee');

Route::get('/checkout/{employee:slug}/{service:slug}', CheckoutController::class)
    ->name('checkout');

Route::get('/slots/{employee:slug}/{service:slug}', SlotsController::class)
    ->name('slots');

Route::post('/appointments', AppointmentController::class)
    ->name('appointments');
Route::delete('/appointments/{appointment}', AppointmentDestroyController::class)
    ->name('appointments.destroy');

Route::get('/confirmation/{appointment:uuid}', ConfirmationController::class)
    ->name('confirmation');
