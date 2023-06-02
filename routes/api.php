<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    ConnectionController,
    ConnectionInvitationController,
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
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('auth.token.refresh');

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

        Route::middleware('verified-broker')->group(function () {
            Route::prefix('users')->group(function () {
                Route::put('{id}', [UserController::class, 'update'])->name('user.update');
                Route::get('{id}', [UserController::class, 'show'])->name('user.show');
                Route::post('search', [UserController::class, 'search'])->name('user.search');
            });

            Route::prefix('connections')->group(function () {
                Route::post('/', [ConnectionController::class, 'connect'])->name('connection.connect');
                Route::delete('{id}', [ConnectionController::class, 'disconnect'])->name('connection.disconnect');
            });

            Route::prefix('connection-invitations')->group(function () {
                Route::post('/', [ConnectionInvitationController::class, 'invite'])->name('connection-invitation.invite');
                Route::delete('{id}', [ConnectionInvitationController::class, 'cancel'])->name('connection-invitation.cancel');
            });
        });
    });
});

/**
 * feed-service
 *      posts
 *          id
 *          user_id
 *          content
 *      shares
 *          id
 *          user_id
 *          post_id
 *      pins
 *          id
 *          user_id
 *          post_id
 *      alerts
 *          id
 *          user_id
 *          keyword
 *      files
 */
