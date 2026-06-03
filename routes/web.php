<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\RedirectIfAdminAuthenticated;

Route::get('/', function () {
    return view('welcome');
});

// Admin Panel Routes
Route::prefix('admin')->group(function () {
    
    // Guest Routes
    Route::middleware([RedirectIfAdminAuthenticated::class])->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    });

    // Authenticated Routes
    Route::middleware([AdminAuthenticate::class])->group(function () {
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
        
        // Profile
        Route::post('profile/update', [AuthController::class, 'updateProfile'])->name('admin.profile.update');

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Manage Users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('{id}', [UserController::class, 'show'])->name('admin.users.show');
            Route::post('{id}/update', [UserController::class, 'update'])->name('admin.users.update');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });

        // Manage FAQs
        Route::prefix('faqs')->group(function () {
            Route::get('/', [FaqController::class, 'index'])->name('admin.faqs.index');
            Route::post('/', [FaqController::class, 'store'])->name('admin.faqs.store');
            Route::get('{id}', [FaqController::class, 'show'])->name('admin.faqs.show');
            Route::post('{id}/update', [FaqController::class, 'update'])->name('admin.faqs.update');
            Route::post('{id}/toggle-status', [FaqController::class, 'toggleStatus'])->name('admin.faqs.toggle-status');
            Route::delete('{id}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');
        });

        // Manage Static Pages
        Route::prefix('pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('admin.pages.index');
            Route::post('/', [PageController::class, 'store'])->name('admin.pages.store');
            Route::get('{id}', [PageController::class, 'show'])->name('admin.pages.show');
            Route::post('{id}/update', [PageController::class, 'update'])->name('admin.pages.update');
            Route::post('{id}/toggle-status', [PageController::class, 'toggleStatus'])->name('admin.pages.toggle-status');
            Route::delete('{id}', [PageController::class, 'destroy'])->name('admin.pages.destroy');
        });
    });
});
