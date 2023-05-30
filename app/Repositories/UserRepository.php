<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\{
    Passport,
    RefreshTokenRepository,
    TokenRepository
};
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Support\Auth\AuthRequest;

class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\User
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Authenticate User
     *
     * @param string $login
     * @param string $password
     * @return Collection
     */
    public function authenticate($login, $password)
    {
        if (request()->remember_me) {
            Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.remember_me_token_timeout')));
        }

        return collect(
            (new AuthRequest)->getToken($login, $password)
        );
    }

    /**
     * Logout the user
     *
     * @param int $id
     * @return mixed
     */
    public function logout($id)
    {
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $tokenRepository->revokeAccessToken($id);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($id);

        return response()->json(true);
    }

    /**
     * Refresh the user token
     *
     * @param String $token
     * @return mixed
     */
    public function refreshToken($token)
    {
        return collect(
            (new AuthRequest)->getTokenViaRefreshToken($token)
        );
    }

    /**
     * Attempt to authorize user
     * 
     * @param String $login
     * @param String $password
     * @return bool
     */
    public function isValidCredential($login, $password)
    {
        $params = [];

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $params['email'] = $login;
        } else {
            $params['username'] = $login;
        }

        $params['password'] = $password;

        $attempt = Auth::attempt($params);

        return $attempt;
    }

    /**
     * Check if email is verified
     * 
     * @param int|string|null $id
     * @param string|null $login
     * @return bool
     */
    public function isEmailVerified($id = null, $login = null)
    {
        if (!$id && !$login) return false;

        $user = $this->model;

        if ($id) $user = $user->where('id', $id);

        if ($login) $user = $user->where('email', $login)->orWhere('username', $login);

        $user = $user->first();

        return !!optional($user)->email_verified_at;
    }

    /**
     * Reset user's password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(array $request)
    {
        return Password::reset(
            $request,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
}
