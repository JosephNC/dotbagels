<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\RewardsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SquareController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth
Route::middleware('guest')->group(function () {

    Route::prefix('login')->group(function () {
        Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/', [LoginController::class, 'login']);
    });

    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/', [RegisterController::class, 'register'])->middleware('throttle:5,1'); // Available 5 times every minute;
    });

    Route::prefix('password')->group(function () {

        // Route::prefix('reset-password')->group(function () {
        //     Route::get('/', [ResetPasswordController::class, 'showResetPasswordForm'])->name('reset-password');
        //     Route::post('/', [ResetPasswordController::class, 'reset-password'])->name('reset-password');
        // });

        Route::get('/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    });
});

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Email Verification
    Route::prefix('email')->group(function () {
        Route::get('/verify', [VerifyController::class, 'index'])->name('verification.notice');

        Route::get('/verify/{id}/{hash}', [VerifyController::class, 'verify'])->middleware('signed')->name('verification.verify');

        Route::post('/verification-notification', [VerifyController::class, 'send'])
            ->middleware('throttle:3,1') // Send link is only available 3 times every minute
            ->name('verification.send');
    });

    Route::middleware('verified')->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Activities
        Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities')->middleware('remember');

        // Rewards
        Route::prefix('rewards')->group(function () {

            Route::get('/', [RewardsController::class, 'index'])->name('rewards');

            Route::middleware(['admin', 'agent'])->group(function () {

                Route::get('/input', [RewardsController::class, 'input'])->name('rewards.input');
                Route::post('/check', [RewardsController::class, 'check'])->name('rewards.check');
                Route::post('/redeem', [RewardsController::class, 'redeem'])->name('rewards.redeem');
            });
        });

        // Reports
        Route::get('reports', [ReportsController::class, 'index'])->name('reports');

        // Account
        Route::get('account', [UsersController::class, 'account'])->name('account');
        Route::put('account', [UsersController::class, 'accountUpdate'])->name('account.update');


        /**
         * Administrator Routes
         */
        Route::middleware('admin')->group(function () {

            Route::get('items', [SquareController::class, 'items'])->name('items');
            Route::get('orders', [SquareController::class, 'orders'])->name('orders');
            Route::put('square-sync', [SquareController::class, 'sync'])->name('square.sync');

            // Settings
            Route::prefix('settings')->group(function () {
                Route::get('/', [SettingsController::class, 'index'])->name('settings');
                Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
            });

            // Loyalty
            Route::prefix('loyalty')->group(function () {

                Route::get('/', [LoyaltyController::class, 'index'])->name('loyalty');
                Route::get('/create', [LoyaltyController::class, 'create'])->name('loyalty.create');
                Route::post('/', [LoyaltyController::class, 'store'])->name('loyalty.store');
                Route::get('/{program_id}/edit', [LoyaltyController::class, 'edit'])->name('loyalty.edit');
                Route::put('/{program_id}', [LoyaltyController::class, 'update'])->name('loyalty.update');
            });

            // Users
            Route::prefix('users')->group(function () {

                Route::get('/', [UsersController::class, 'index'])->name('users')->middleware('remember');
                Route::get('/create', [UsersController::class, 'create'])->name('users.create');
                Route::post('/', [UsersController::class, 'store'])->name('users.store');
                Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
                Route::put('/{user}', [UsersController::class, 'update'])->name('users.update');
                Route::delete('/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
                Route::put('/{user}/restore', [UsersController::class, 'restore'])->name('users.restore');
            });
        });
    });
});

// Images
Route::get('/img/{path}', [ImagesController::class, 'show'])->where('path', '.*');

// 500 error
Route::get('500', function () {
    // Force debug mode for this endpoint in the demo environment
    if (App::environment('demo')) {
        Config::set('app.debug', true);
    }

    echo $fail;
});
