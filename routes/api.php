<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\BannerController;

Route::prefix('auth')->group(function () {
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('complete-setup', [AuthController::class, 'completeSetup']);
    Route::post('google-login', [AuthController::class, 'googleLogin']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('user/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('user/language', [AuthController::class, 'updateLanguage']);
    Route::get('calendar', [\App\Http\Controllers\Api\CalendarApiController::class, 'getCalendar']);
    
    // Leads APIs
    Route::prefix('leads')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\LeadController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\LeadController::class, 'store']);
        Route::get('stats', [\App\Http\Controllers\Api\LeadController::class, 'getStats']);
        Route::get('{id}', [\App\Http\Controllers\Api\LeadController::class, 'show']);
        Route::post('{id}/change-status', [\App\Http\Controllers\Api\LeadController::class, 'changeStatus']);
        Route::put('{id}', [\App\Http\Controllers\Api\LeadController::class, 'update']);
        Route::delete('{id}', [\App\Http\Controllers\Api\LeadController::class, 'destroy']);
    });
});

// Banners APIs
Route::get('banners', [BannerController::class, 'index']);
Route::get('banners/{id}', [BannerController::class, 'show']);

// Public content routes
Route::get('faqs', [FaqController::class, 'index']);
Route::get('pages/{page_name}', [PageController::class, 'show']);

// Public training routes
use App\Http\Controllers\Api\TrainingController;
Route::get('training-categories', [TrainingController::class, 'getCategories']);
Route::get('trainings', [TrainingController::class, 'getTrainings']);
Route::get('trainings/search', [TrainingController::class, 'search']);
Route::get('trainings/{id}', [TrainingController::class, 'show']);

// Notifications routes
use App\Http\Controllers\Api\NotificationController;
Route::get('notifications', [NotificationController::class, 'index']);
Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead']);

// Tools routes
use App\Http\Controllers\Api\ToolController;
Route::get('tools', [ToolController::class, 'index']);
Route::get('tools/{id}', [ToolController::class, 'show']);
Route::get('tools/media/{id}', [ToolController::class, 'showMedia']);

Route::get('notifications/{id}', [NotificationController::class, 'show']);

