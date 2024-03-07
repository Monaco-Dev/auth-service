<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Exception;

use App\Repositories\Contracts\{
    SocialiteRepositoryInterface,
    UserRepositoryInterface
};
use App\Services\Contracts\SocialiteServiceInterface;

class SocialiteService extends Service implements SocialiteServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\SocialiteRepositoryInterface $repository
     * @param App\Repositories\Contracts\UserRepositoryInterface $userRepository
     */
    public function __construct(
        SocialiteRepositoryInterface $repository,
        UserRepositoryInterface $userRepository
    ) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function callback(array $request)
    {
        $web = config('services.web_url');

        DB::beginTransaction();

        try {
            $driver = Arr::get($request, 'driver');

            $social = Socialite::driver($driver)->stateless()->user();

            $name = explode(' ', $social->getName());
            $lastName = array_splice($name, -1);
            $email = $social->getEmail();
            $avatar = $social->getAvatar();
            $socialId = $social->getId();
            $token = $social->token;
            $refreshToken = $social->refreshToken;
            $expiresIn = $social->expiresIn;

            $params = [
                'password' => $token,
                'deactivated_at' => null
            ];

            $user = $this->userRepository->model()->whereEmail($email)->first();

            $origPsw = null;

            if ($user) {
                $origPsw = $user->password;

                if (!$user->avatar) $params['avatar'] = $avatar;

                if (!$user->is_email_verified) $params['email_verified_at'] = now();

                $params['uuid'] = $user->uuid;
            } else {
                $params['first_name'] = implode(' ', $name);
                $params['last_name'] = implode('', $lastName);
                $params['avatar'] = $avatar;
                $params['email_verified_at'] = now();
                $params['uuid'] = Str::uuid();
            }

            $user = $this->userRepository->updateOrCreate(
                ['email' => $email],
                $params
            );

            $this->repository->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'driver_id' => $socialId,
                    'driver' => $driver,
                    'token' => $token,
                    'refresh_token' => $refreshToken,
                    'expires_in' => $expiresIn
                ]
            );

            $response = $this->userRepository->authenticate(
                $user->email,
                $token
            );

            $cookie = cookie(
                'token',
                base64_encode($response),
                config('auth.remember_me_token_timeout'),
                '/',
                config('app.domain'),
                config('app.env') != 'local',
                false,
                false,
                'strict'
            );

            $this->userRepository->update($user, ['password' => $origPsw]);

            DB::commit();

            return redirect($web)->withCookie($cookie);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param array $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function redirect(array $request)
    {
        $driver = Arr::get($request, 'driver');

        return Socialite::driver($driver)->stateless()->redirect();
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function login(array $request)
    {
        DB::beginTransaction();

        try {
            $id = Arr::get($request, 'id');

            $social = $this->repository->model()->whereDriverId($id)->first();

            $response = $this->userRepository->authenticate(
                $social->user->email,
                $social->token
            );

            $response->password_reset = true;

            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
