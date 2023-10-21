<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    BrokerLicenseController,
    ConnectionController,
    ConnectionInvitationController,
    SlugController,
    UserController
};

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

Route::prefix('auth')->group(function () {
    Route::name('auth.')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
    });

    Route::name('password.')->group(function () {
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('email');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('update');
    });
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::name('auth.')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::delete('delete', [AuthController::class, 'destroy'])->name('destroy');
            Route::get('verify-token', [AuthController::class, 'verifyToken'])->name('token.verify');
        });

        Route::prefix('email')->name('verification.')->group(function () {
            Route::get('verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verify');
            Route::post('verification-notification', [AuthController::class, 'resendEmailVerification'])->name('send');
        });
    });

    Route::middleware('verified-email')->group(function () {
        Route::post('broker-licenses/update', [BrokerLicenseController::class, 'update'])->name('brokerLicenses.update');

        Route::middleware('verified-broker')->group(function () {
            Route::post('auth/deactivate', [AuthController::class, 'deactivate'])->name('auth.deactivate');

            Route::prefix('users')->name('users.')->group(function () {
                Route::put('{user}', [UserController::class, 'update'])->name('update');
                Route::get('{url}', [UserController::class, 'show'])->name('show')->middleware('profile');
                Route::post('search', [UserController::class, 'search'])->name('search');
            });

            Route::prefix('connections')->name('connections.')->group(function () {
                Route::prefix('{user}')->group(function () {
                    Route::post('connect', [ConnectionController::class, 'connect'])->name('connect');
                    Route::delete('disconnect', [ConnectionController::class, 'disconnect'])->name('disconnect');
                });

                Route::post('search', [ConnectionController::class, 'search'])->name('search');
            });

            Route::prefix('connection-invitations')->name('connection-invitations.')->group(function () {
                Route::prefix('{user}')->group(function () {
                    Route::post('send', [ConnectionInvitationController::class, 'send'])->name('send');
                    Route::delete('cancel', [ConnectionInvitationController::class, 'cancel'])->name('cancel');
                });

                Route::prefix('search')->name('search.')->group(function () {
                    Route::post('incoming', [ConnectionInvitationController::class, 'searchIncoming'])->name('incoming');
                    Route::post('outgoing', [ConnectionInvitationController::class, 'searchOutgoing'])->name('outgoing');
                });
            });

            Route::prefix('slugs')->name('slugs.')->group(function () {
                Route::post('/', [SlugController::class, 'store'])->name('store');
                Route::delete('{slug}', [SlugController::class, 'destroy'])->name('destroy');
            });
        });
    });
});

Route::prefix('service')->middleware('client')->group(function () {
    //
});
