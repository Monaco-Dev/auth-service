<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Auth\Events\PasswordReset;

use App\Services\Contracts\AuthServiceInterface;
use App\Http\Resources\UserResource;
use App\Notifications\DeactivateNotification;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthService extends Service implements AuthServiceInterface
{
    /**
     * Resource class of the service.
     * 
     * @var \App\Http\Resources\UserResource
     */
    protected $resourceClass = UserResource::class;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\UserRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
                $user->deactivated_at = null;
                $user->save();
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
        return $this->setResponseResource(
            $this->repository->model()
                ->withRelations()
                ->whereId(Auth::user()->id)
                ->first()
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
            Arr::set($request, 'uuid', Str::uuid());

            // create user
            $user = $this->repository->create($request);
            event(new Registered($user));

            // get profile
            $profile = $this->repository->model()
                ->withRelations()
                ->whereId($user->id)
                ->first();

            // authenticate
            $token = $this->repository->authenticate(
                $user->email,
                Arr::get($request, 'password')
            );

            $profile->token = $token;

            Auth::setUser($user);

            DB::commit();

            return $this->setResponseResource($profile);
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
     * Update user's password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(array $request)
    {
        $user = Auth::user();

        $user->forceFill([
            'password' => Arr::get($request, 'password')
        ]);

        $user->save();

        $user->tokens->each(fn ($token) => $this->logout($token->id));

        event(new PasswordReset($user));
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
        $user = Auth::user();

        $license = $user->license;
        $license->license_number = $license->license_number . '+deleted-' . Str::uuid();
        $license->save();
        $license->delete();

        $user->tokens->each(fn ($token) => $this->repository->logout($token->id));

        $this->repository->model()->whereId($user->id)->update([
            'email' => $user->email . '+deleted-' . Str::uuid(),
            'phone_number' => $user->phone_number . '+deleted-' . Str::uuid()
        ]);

        return $this->repository->delete($user->id);
    }
}
