<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TrainingCategoryController;
use App\Http\Controllers\Admin\TrainingHubController;
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

        // Manage Training Categories
        Route::prefix('training-categories')->group(function () {
            Route::get('/', [TrainingCategoryController::class, 'index'])->name('admin.training-categories.index');
            Route::post('/', [TrainingCategoryController::class, 'store'])->name('admin.training-categories.store');
            Route::get('{id}', [TrainingCategoryController::class, 'show'])->name('admin.training-categories.show');
            Route::post('{id}/update', [TrainingCategoryController::class, 'update'])->name('admin.training-categories.update');
            Route::post('{id}/toggle-status', [TrainingCategoryController::class, 'toggleStatus'])->name('admin.training-categories.toggle-status');
            Route::delete('{id}', [TrainingCategoryController::class, 'destroy'])->name('admin.training-categories.destroy');
        });

        // Manage Training Hubs
        Route::prefix('training-hubs')->group(function () {
            Route::get('/', [TrainingHubController::class, 'index'])->name('admin.training-hubs.index');
            Route::post('/', [TrainingHubController::class, 'store'])->name('admin.training-hubs.store');
            Route::get('{id}', [TrainingHubController::class, 'show'])->name('admin.training-hubs.show');
            Route::post('{id}/update', [TrainingHubController::class, 'update'])->name('admin.training-hubs.update');
            Route::post('{id}/toggle-status', [TrainingHubController::class, 'toggleStatus'])->name('admin.training-hubs.toggle-status');
            Route::delete('{id}', [TrainingHubController::class, 'destroy'])->name('admin.training-hubs.destroy');
        });

        // Manage Business Tools
        Route::prefix('tools')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ToolController::class, 'index'])->name('admin.tools.index');
            Route::post('/', [\App\Http\Controllers\Admin\ToolController::class, 'store'])->name('admin.tools.store');
            Route::get('{id}', [\App\Http\Controllers\Admin\ToolController::class, 'show'])->name('admin.tools.show');
            Route::post('{id}/update', [\App\Http\Controllers\Admin\ToolController::class, 'update'])->name('admin.tools.update');
            Route::post('{id}/toggle-status', [\App\Http\Controllers\Admin\ToolController::class, 'toggleStatus'])->name('admin.tools.toggle-status');
            Route::delete('{id}', [\App\Http\Controllers\Admin\ToolController::class, 'destroy'])->name('admin.tools.destroy');
            
            // Subtools & Media management specific to a Tool
            Route::get('{id}/manage', [\App\Http\Controllers\Admin\ToolController::class, 'manage'])->name('admin.tools.manage');
            Route::post('{id}/subtools', [\App\Http\Controllers\Admin\ToolController::class, 'storeSubtool'])->name('admin.tools.subtools.store');
            Route::delete('subtools/{subtoolId}', [\App\Http\Controllers\Admin\ToolController::class, 'destroySubtool'])->name('admin.tools.subtools.destroy');
            Route::post('{id}/media', [\App\Http\Controllers\Admin\ToolController::class, 'storeMedia'])->name('admin.tools.media.store');
            Route::delete('media/{mediaId}', [\App\Http\Controllers\Admin\ToolController::class, 'destroyMedia'])->name('admin.tools.media.destroy');
        });
    });
});

