<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    BrokerLicenseController,
    ConnectionController,
    ConnectionInvitationController,
    UserController
};
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('migrate', function () {
    Artisan::call('migrate');
});
Route::get('seed', function () {
    Artisan::call('db:class --seed');
});
Route::get('passport', function () {
    Artisan::call('passport:install');
});
Route::get('info', function () {
    phpinfo();
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('auth.token.refresh');
    Route::post('validate-password', [AuthController::class, 'validatePassword']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('verify-token', [AuthController::class, 'verifyToken'])->name('auth.token.verify');

        Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
        Route::post('email/verification-notification', [AuthController::class, 'resendEmailVerification'])->name('verification.send');
    });

    Route::middleware('verified-email')->group(function () {
        Route::delete('auth/deactivate', [AuthController::class, 'deactivate'])->name('auth.deactivate');

        Route::post('broker-licenses', [BrokerLicenseController::class, 'store'])->name('brokerLicenses.store');

        Route::middleware('verified-broker')->group(function () {
            Route::prefix('users')->group(function () {
                Route::put('{id}', [UserController::class, 'update'])->name('users.update');
                Route::get('{id}', [UserController::class, 'show'])->name('users.show');
                Route::post('search', [UserController::class, 'search'])->name('users.search');
            });

            Route::prefix('connections')->group(function () {
                Route::post('search', [ConnectionController::class, 'search'])->name('connections.search');
                Route::post('/', [ConnectionController::class, 'connect'])->name('connections.connect');
                Route::delete('{id}', [ConnectionController::class, 'disconnect'])->name('connections.disconnect');
            });

            Route::prefix('connection-invitations')->group(function () {
                Route::get('requests', [ConnectionInvitationController::class, 'requests'])->name('connection-invitations.requests');
                Route::get('/', [ConnectionInvitationController::class, 'index'])->name('connection-invitations.index');
                Route::post('/', [ConnectionInvitationController::class, 'invite'])->name('connection-invitations.invite');
                Route::delete('{id}', [ConnectionInvitationController::class, 'cancel'])->name('connection-invitations.cancel');
            });
        });
    });
});

Route::prefix('service')->middleware('client')->group(function () {
    //
});
