<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * Login Action
     *
     * @param array $request
     * @return mixed
     */
    public function login($request);

    /**
     * Logout Action
     *
     * @return mixed
     */
    public function logout();

    /**
     * Refresh token action
     *
     * @param array $request
     * @return mixed
     */
    public function refreshToken($request);

    /**
     * Verify access token and return user data.
     * 
     * @return \Illuminate\Http\Response
     */
    public function verifyToken();

    /**
     * Register new user data.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function register(array $request);

    /**
     * Verify email token.
     * 
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail($request);

    /**
     * Request resend email verification.
     * 
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function resendEmailVerification($request);

    /**
     * Send resend password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(array $request);

    /**
     * Reset user's password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(array $request);

    /**
     * Request to deactivate a specific user.
     * 
     * @return \Illuminate\Http\Response
     */
    public function deactivate();
}
