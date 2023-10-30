<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Services\Contracts\AuthServiceInterface;
use App\Http\Requests\Auth\{
    ForgotPasswordRequest,
    LoginRequest,
    RefreshTokenRequest,
    RegisterRequest,
    ResetPasswordRequest,
};

class AuthController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\AuthServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\AuthServiceInterface $service
     */
    public function __construct(AuthServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Login Action
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        return $this->service->login($request->validated());
    }

    /**
     * Logout Action
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return $this->service->logout();
    }

    /**
     * Refresh token action
     *
     * @param \App\Http\Requests\Auth\RefreshTokenRequest $request
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(RefreshTokenRequest $request)
    {
        return $this->service->refreshToken($request->validated());
    }

    /**
     * Verify access token and return user data.
     * 
     * @return \Illuminate\Http\Response
     */
    public function verifyToken()
    {
        return $this->service->verifyToken();
    }

    /**
     * Register new user data.
     * 
     * @param \App\Http\Requests\Auth\RefreshTokenRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        return $this->service->register($request->validated());
    }

    /**
     * Verify email token.
     * 
     * @param \Illuminate\Foundation\Auth\EmailVerificationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        return $this->service->verifyEmail($request);
    }

    /**
     * Request resend email verification.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resendEmailVerification(Request $request)
    {
        return $this->service->resendEmailVerification($request);
    }

    /**
     * Send resend password.
     * 
     * @param \App\Http\Requests\Auth\ForgotPasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        return $this->service->forgotPassword($request->validated());
    }

    /**
     * Reset user's password.
     * 
     * @param \App\Http\Requests\Auth\ResetPasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->service->resetPassword($request->validated());
    }

    /**
     * Request to deactivate a specific user.
     * 
     * @return \Illuminate\Http\Response
     */
    public function deactivate()
    {
        return $this->service->deactivate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return $this->service->delete();
    }
}
