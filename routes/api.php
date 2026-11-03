<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{ActivityLogController,AppointmentController,AuthController,DashboardController,DoctorController,IssueReportController,PatientController,UserController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', DashboardController::class);

    Route::apiResource('patients', PatientController::class)->middleware('role:admin,receptionist');
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::post('/doctors', [DoctorController::class, 'store']);
        Route::match(['put','patch'], '/doctors/{doctor}', [DoctorController::class, 'update']);
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);
        Route::apiResource('users', UserController::class)->only(['index','store','update']);
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    });
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('issue-reports', IssueReportController::class)->only(['index','store','show','update']);
});
