<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\TrainingCategoryController;
use App\Http\Controllers\Admin\TrainingHubController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\DealerAuthenticate;
use App\Http\Middleware\RedirectIfAdminAuthenticated;
use Illuminate\Support\Facades\Route;

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
            Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('{id}', [UserController::class, 'show'])->name('admin.users.show');
            Route::post('{id}/update', [UserController::class, 'update'])->name('admin.users.update');
            Route::post('{id}/approval', [UserController::class, 'approval'])->name('admin.users.approval');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });

        // Manage Dealers
        Route::prefix('dealers')->group(function () {
            Route::get('/', [DealerController::class, 'index'])->name('admin.dealers.index');
            Route::post('/', [DealerController::class, 'store'])->name('admin.dealers.store');
            Route::get('{id}/users', [DealerController::class, 'users'])->name('admin.dealers.users');
            Route::get('{id}', [DealerController::class, 'show'])->name('admin.dealers.show');
            Route::post('{id}/update', [DealerController::class, 'update'])->name('admin.dealers.update');
            Route::delete('{id}', [DealerController::class, 'destroy'])->name('admin.dealers.destroy');
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
            Route::get('/', [ToolController::class, 'index'])->name('admin.tools.index');
            Route::post('/', [ToolController::class, 'store'])->name('admin.tools.store');
            Route::get('{id}', [ToolController::class, 'show'])->name('admin.tools.show');
            Route::post('{id}/update', [ToolController::class, 'update'])->name('admin.tools.update');
            Route::post('{id}/toggle-status', [ToolController::class, 'toggleStatus'])->name('admin.tools.toggle-status');
            Route::delete('{id}', [ToolController::class, 'destroy'])->name('admin.tools.destroy');

            // Subtools & Media management specific to a Tool
            Route::get('{id}/manage', [ToolController::class, 'manage'])->name('admin.tools.manage');
            Route::post('{id}/subtools', [ToolController::class, 'storeSubtool'])->name('admin.tools.subtools.store');
            Route::post('subtools/{subtoolId}/update', [ToolController::class, 'updateSubtool'])->name('admin.tools.subtools.update');
            Route::delete('subtools/{subtoolId}', [ToolController::class, 'destroySubtool'])->name('admin.tools.subtools.destroy');
            Route::post('{id}/media', [ToolController::class, 'storeMedia'])->name('admin.tools.media.store');
            Route::post('media/{mediaId}/update', [ToolController::class, 'updateMedia'])->name('admin.tools.media.update');
            Route::delete('media/{mediaId}', [ToolController::class, 'destroyMedia'])->name('admin.tools.media.destroy');
        });

        // Manage Leads
        Route::prefix('leads')->group(function () {
            Route::get('/', [LeadController::class, 'index'])->name('admin.leads.index');
            Route::get('{id}', [LeadController::class, 'show'])->name('admin.leads.show');
            Route::post('{id}/update', [LeadController::class, 'update'])->name('admin.leads.update');
            Route::post('{id}/toggle-status', [LeadController::class, 'toggleStatus'])->name('admin.leads.toggle-status');
            Route::post('{id}/change-status', [LeadController::class, 'changeStatus'])->name('admin.leads.change-status');
            Route::delete('{id}', [LeadController::class, 'destroy'])->name('admin.leads.destroy');
        });

        // Manage Banners
        Route::prefix('banners')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('admin.banners.index');
            Route::post('/', [BannerController::class, 'store'])->name('admin.banners.store');
            Route::get('{id}', [BannerController::class, 'show'])->name('admin.banners.show');
            Route::post('{id}/update', [BannerController::class, 'update'])->name('admin.banners.update');
            Route::post('{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('admin.banners.toggle-status');
            Route::delete('{id}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
        });

        // Manage Calendar Contents
        Route::prefix('calendar-contents')->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('admin.calendar-contents.index');
            Route::post('/', [CalendarController::class, 'store'])->name('admin.calendar-contents.store');
            Route::get('{id}', [CalendarController::class, 'show'])->name('admin.calendar-contents.show');
            Route::post('{id}/update', [CalendarController::class, 'update'])->name('admin.calendar-contents.update');
            Route::post('{id}/toggle-status', [CalendarController::class, 'toggleStatus'])->name('admin.calendar-contents.toggle-status');
            Route::delete('{id}', [CalendarController::class, 'destroy'])->name('admin.calendar-contents.destroy');
        });
    });
});

// Dealer Panel Routes
Route::prefix('dealer')->group(function () {
    Route::get('login', [App\Http\Controllers\Dealer\AuthController::class, 'showLogin'])->name('dealer.login');
    Route::post('login', [App\Http\Controllers\Dealer\AuthController::class, 'login'])->name('dealer.login.submit');

    Route::middleware([DealerAuthenticate::class])->group(function () {
        Route::post('logout', [App\Http\Controllers\Dealer\AuthController::class, 'logout'])->name('dealer.logout');
        Route::get('dashboard', [App\Http\Controllers\Dealer\DashboardController::class, 'index'])->name('dealer.dashboard');
        Route::post('profile/update', [App\Http\Controllers\Dealer\AuthController::class, 'updateProfile'])->name('dealer.profile.update');
        Route::prefix('users')->group(function () {
            Route::get('/', [App\Http\Controllers\Dealer\UserController::class, 'index'])->name('dealer.users.index');
            Route::post('/', [App\Http\Controllers\Dealer\UserController::class, 'store'])->name('dealer.users.store');
            Route::get('{id}', [App\Http\Controllers\Dealer\UserController::class, 'show'])->name('dealer.users.show');
            Route::post('{id}/update', [App\Http\Controllers\Dealer\UserController::class, 'update'])->name('dealer.users.update');
            Route::post('{id}/approval', [App\Http\Controllers\Dealer\UserController::class, 'approval'])->name('dealer.users.approval');
            Route::delete('{id}', [App\Http\Controllers\Dealer\UserController::class, 'destroy'])->name('dealer.users.destroy');
        });
    });
});
