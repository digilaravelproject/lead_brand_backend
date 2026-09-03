<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CalendarApiController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Middleware\EnsureUserSubscriptionAccess;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('complete-setup', [AuthController::class, 'completeSetup']);
    Route::post('google-login', [AuthController::class, 'googleLogin']);
});

// The profile endpoint must remain available to every authenticated user so the
// client can display the user and their dealer/admin, regardless of subscription.
Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'me']);

Route::middleware(['auth:sanctum', EnsureUserSubscriptionAccess::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('user/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('user/language', [AuthController::class, 'updateLanguage']);
    Route::get('calendar', [CalendarApiController::class, 'getCalendar']);

    // Leads APIs
    Route::prefix('leads')->group(function () {
        Route::get('/', [LeadController::class, 'index']);
        Route::post('/', [LeadController::class, 'store']);
        Route::get('stats', [LeadController::class, 'getStats']);
        Route::get('{id}', [LeadController::class, 'show']);
        Route::post('{id}/change-status', [LeadController::class, 'changeStatus']);
        Route::put('{id}', [LeadController::class, 'update']);
        Route::delete('{id}', [LeadController::class, 'destroy']);
    });

    // Subscription content and functionality
    Route::get('banners', [BannerController::class, 'index']);
    Route::get('banners/{id}', [BannerController::class, 'show']);
    Route::get('faqs', [FaqController::class, 'index']);
    Route::get('messages', [MessageController::class, 'index']);
    Route::get('pages/{page_name}', [PageController::class, 'show']);
    Route::get('training-categories', [TrainingController::class, 'getCategories']);
    Route::get('trainings', [TrainingController::class, 'getTrainings']);
    Route::get('trainings/search', [TrainingController::class, 'search']);
    Route::get('trainings/{id}', [TrainingController::class, 'show']);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
    Route::get('notifications/{id}', [NotificationController::class, 'show']);
    Route::get('tools/media/{id}', [ToolController::class, 'showMedia']);
    Route::get('tools', [ToolController::class, 'index']);
    Route::get('tools/{id}', [ToolController::class, 'show']);
});
