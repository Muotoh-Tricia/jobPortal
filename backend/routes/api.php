<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobSeekerController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    });

    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/profile', [UserController::class, 'profile'])->middleware('auth:sanctum');
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    // Employer Routes
    Route::prefix('employers')->group(function () {
        Route::get('/', [EmployerController::class, 'index']);
        Route::post('/', [EmployerController::class, 'store'])->middleware('auth:sanctum');
        Route::get('/{employer}', [EmployerController::class, 'show']);
        Route::put('/{employer}', [EmployerController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{employer}', [EmployerController::class, 'destroy'])->middleware('auth:sanctum');
        Route::get('/{employer}/jobs', [EmployerController::class, 'jobs']);
        Route::get('/{employer}/applications', [EmployerController::class, 'applications']);
    });

    // Job Seeker Routes
    Route::prefix('job-seekers')->group(function () {
        Route::get('/', [JobSeekerController::class, 'index']);
        Route::post('/', [JobSeekerController::class, 'store'])->middleware('auth:sanctum');
        Route::get('/{jobSeeker}', [JobSeekerController::class, 'show']);
        Route::put('/{jobSeeker}', [JobSeekerController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{jobSeeker}', [JobSeekerController::class, 'destroy'])->middleware('auth:sanctum');
        Route::get('/{jobSeeker}/applications', [JobSeekerController::class, 'applications']);
        Route::get('/{jobSeeker}/resume/download', [JobSeekerController::class, 'downloadResume']);
    });

    // Job Routes
    Route::prefix('jobs')->group(function () {
        Route::get('/', [JobController::class, 'index']);
        Route::post('/', [JobController::class, 'store'])->middleware('auth:sanctum');
        Route::get('/search', [JobController::class, 'search']);
        Route::get('/{job}', [JobController::class, 'show']);
        Route::put('/{job}', [JobController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{job}', [JobController::class, 'destroy'])->middleware('auth:sanctum');
        Route::get('/{job}/applications', [JobController::class, 'applications']);
        Route::get('/{job}/check-eligibility', [JobController::class, 'checkApplicationEligibility'])
            ->middleware('auth:sanctum');
    });

    // Application Routes
    Route::prefix('applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->middleware('auth:sanctum');
        Route::post('/', [ApplicationController::class, 'store'])->middleware('auth:sanctum');
        Route::get('/user', [ApplicationController::class, 'userApplications'])->middleware('auth:sanctum');
        Route::get('/{application}', [ApplicationController::class, 'show'])->middleware('auth:sanctum');
        Route::put('/{application}', [ApplicationController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{application}', [ApplicationController::class, 'destroy'])->middleware('auth:sanctum');
        Route::patch('/{application}/status', [ApplicationController::class, 'updateStatus'])->middleware('auth:sanctum');
    });
});
