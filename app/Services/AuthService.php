<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;

use App\Services\Contracts\AuthServiceInterface;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\BrokerLicenseRepositoryInterface;

class AuthService extends Service implements AuthServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\BrokerLicenseRepositoryInterface
     */
    protected $brokerLicenseRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\UserRepositoryInterface
     * @param App\Repositories\Contracts\BrokerLicenseRepositoryInterface
     */
    public function __construct(
        UserRepositoryInterface $repository,
        BrokerLicenseRepositoryInterface $brokerLicenseRepository
    ) {
        $this->repository = $repository;
        $this->brokerLicenseRepository = $brokerLicenseRepository;
    }

    /**
     * Login Action
     *
     * @param array $request
     * @return mixed
     */
    public function login($request)
    {
        return $this->repository->authenticate(
            Arr::get($request, 'login'),
            Arr::get($request, 'password')
        );
    }

    /**
     * Logout Action
     *
     * @return mixed
     */
    public function logout()
    {
        return $this->repository->logout(request()->user()->token()->id);
    }

    /**
     * Refresh token action
     *
     * @param array $request
     * @return mixed
     */
    public function refreshToken($request)
    {
        return $this->repository->refreshToken(Arr::get($request, 'refresh_token'));
    }

    /**
     * Verify access token and return user data.
     * 
     * @return \Illuminate\Http\Response
     */
    public function verifyToken()
    {
        $data = Auth::user()
            ->with([
                'brokerLicense',
                'connections.connection',
                'socials'
            ])
            ->first();

        return response(new UserResource($data));
    }

    /**
     * Register new user data.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function register(array $request)
    {
        $userData = Arr::except($request, ['socials', 'broker_license_number']);

        $user = $this->repository->create($userData);

        $this->brokerLicenseRepository->firstOrCreate(
            ['user_id' => $user->id],
            ['license_number' => Arr::get($request, 'broker_license_number')]
        );

        $data = $user->with([
            'brokerLicense',
            'connections.connection',
            'socials'
        ])->first();

        $token = $this->repository->authenticate(
            $user->email,
            Arr::get($request, 'password')
        );

        $data->token = $token;

        event(new Registered($user));

        return response(new UserResource($data));
    }

    /**
     * Verify email token.
     * 
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail($request)
    {
        $request->fulfill();

        return response()->json(true);
    }

    /**
     * Request resend email verification.
     * 
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function resendEmailVerification($request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(true);
    }

    /**
     * Send resend password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(array $request)
    {
        $status = Password::sendResetLink($request);

        $response = [];

        if ($status === Password::RESET_LINK_SENT) {
            Arr::set($response, 'status', __($status));
        } else {
            Arr::set($response, 'email', __($status));
        }

        return response()->json($response);
    }

    /**
     * Reset user's password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(array $request)
    {
        $status = $this->repository->resetPassword($request);

        $response = [];

        if ($status === Password::PASSWORD_RESET) {
            Arr::set($response, 'status', __($status));
        } else {
            Arr::set($response, 'email', __($status));
        }

        return response()->json($response);
    }
}
