<?php

use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GpsPointController;
use App\Http\Controllers\Api\V1\MeController;
use App\Http\Controllers\Api\V1\TodaysTourController;
use App\Http\Controllers\Api\V1\VisitController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', MeController::class);

        Route::get('attendance/today', [AttendanceController::class, 'today']);
        Route::post('attendance/punch-in', [AttendanceController::class, 'punchIn']);
        Route::post('attendance/punch-out', [AttendanceController::class, 'punchOut']);

        Route::get('todays-tour', TodaysTourController::class);

        Route::get('visits/{visit}', [VisitController::class, 'show']);
        Route::post('visits/{visit}/check-in', [VisitController::class, 'checkIn']);
        Route::post('visits/{visit}/check-out', [VisitController::class, 'checkOut']);
        Route::post('visits/{visit}/photos', [VisitController::class, 'storePhoto']);

        Route::post('gps-points', [GpsPointController::class, 'store']);
    });
});
