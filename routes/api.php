<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\ReservationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('class-schedules', ClassScheduleController::class)->only(['index', 'show']);
    Route::apiResource('class-sessions', ClassSessionController::class)->only(['index', 'show']);
    Route::apiResource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
    Route::post('/reservations/{reservation}/check-in', [ReservationController::class, 'checkIn']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('class-types', ClassTypeController::class);
    Route::apiResource('class-schedules', ClassScheduleController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('class-sessions', ClassSessionController::class)->only(['store', 'update', 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:instructor'])->group(function () {
    Route::apiResource('class-schedules', ClassScheduleController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('class-sessions', ClassSessionController::class)->only(['store', 'update', 'destroy']);
});