<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Services\Contracts\AuthServiceInterface;
use App\Http\Resources\UserResource;
use App\Notifications\DeactivateNotification;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\BrokerLicenseRepositoryInterface;
use App\Repositories\Contracts\SlugRepositoryInterface;

class AuthService extends Service implements AuthServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\BrokerLicenseRepositoryInterface
     */
    protected $brokerLicenseRepository;

    /**
     * @var \App\Repositories\Contracts\SlugRepositoryInterface
     */
    protected $slugRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\UserRepositoryInterface
     * @param App\Repositories\Contracts\BrokerLicenseRepositoryInterface
     * @param App\Repositories\Contracts\SlugRepositoryInterface
     */
    public function __construct(
        UserRepositoryInterface $repository,
        BrokerLicenseRepositoryInterface $brokerLicenseRepository,
        SlugRepositoryInterface $slugRepository
    ) {
        $this->repository = $repository;
        $this->brokerLicenseRepository = $brokerLicenseRepository;
        $this->slugRepository = $slugRepository;
    }

    /**
     * Login Action
     *
     * @param array $request
     * @return mixed
     */
    public function login($request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if ($user->is_deactivated) {
                $this->repository->reactivate($user->id);
            }

            $response = $this->repository->authenticate(
                Arr::get($request, 'email'),
                Arr::get($request, 'password')
            );

            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
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
        return new UserResource(
            $this->repository->profile(Auth::user()->id)
        );
    }

    /**
     * Register new user data.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function register(array $request)
    {
        DB::beginTransaction();

        try {
            // prepare data
            $userData = Arr::except($request, ['broker']);
            $brokerData = Arr::get(Arr::only($request, ['broker']), 'broker');

            // create user
            $user = $this->repository->create($userData);
            event(new Registered($user));

            // login user session
            Auth::login($user);

            // create broker license
            Arr::set($brokerData, 'user_id', $user->id);
            $this->brokerLicenseRepository->create($brokerData);

            // create slug
            $this->slugRepository->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'slug' => Str::slug(
                        $user->first_name . ' ' . $user->last_name . ' ' . (string) Str::uuid(),
                        '-'
                    )
                ]
            );

            // get profile
            $response = new UserResource(
                $this->repository->profile($user->id)
            );

            // authenticate
            $token = $this->repository->authenticate(
                $user->email,
                Arr::get($request, 'password')
            );
            Arr::set($response, 'token', $token);

            // logout user session
            Auth::logout($user);

            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
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

    /**
     * Request to deactivate a specific user.
     * 
     * @return \Illuminate\Http\Response
     */
    public function deactivate()
    {
        $user = Auth::user();

        $this->repository->deactivate($user->id);

        $user->notify(new DeactivateNotification);

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return int
     */
    public function delete()
    {
        return $this->repository->delete(Auth::user()->id);
    }
}
