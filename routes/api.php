<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('refresh-token', [AuthController::class, 'refreshToken']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('verify-token', [AuthController::class, 'verifyToken']);

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendEmailVerification'])->name('verification.send');

    Route::middleware('verified')->group(function () {
        //
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
