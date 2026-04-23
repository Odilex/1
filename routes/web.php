<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::resource('rooms', \App\Http\Controllers\RoomController::class);
Route::resource('guests', \App\Http\Controllers\GuestController::class);
Route::resource('reservations', \App\Http\Controllers\ReservationController::class);
Route::resource('payments', \App\Http\Controllers\PaymentController::class);
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

// API routes for React frontend
Route::prefix('api')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'api']);
    Route::get('/rooms', [\App\Http\Controllers\RoomController::class, 'apiIndex']);
    Route::get('/guests', [\App\Http\Controllers\GuestController::class, 'apiIndex']);
    Route::get('/reservations', [\App\Http\Controllers\ReservationController::class, 'apiIndex']);
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'apiIndex']);
});
