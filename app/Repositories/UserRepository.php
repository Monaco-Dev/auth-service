<?php

namespace App\Repositories;

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
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function authenticate($email, $password)
    {
        if (request()->remember_me) {
            Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.remember_me_token_timeout')));
        }

        return collect(
            (new AuthRequest)->getToken($email, $password)
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

                $user->tokens->each(fn ($token) => $this->logout($token->id));

                event(new PasswordReset($user));
            }
        );
    }

    /**
     * Deactivate user to database.
     * 
     * @param int|string $id
     * @return bool
     */
    public function deactivate($id)
    {
        $user = $this->model->find($id);

        $user->tokens->each(fn ($token) => $this->logout($token->id));

        $user->deactivated_at = now();

        return $user->save();
    }

    /**
     * Get user's profile
     * 
     * @param int|string $id
     * @param bool $verified
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function profile($id, $verified = false)
    {
        $model = $this->model
            ->find($id)
            ->withRelations();

        if ($verified) $model = $model->verified();

        return $model->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(mixed $model, array $request)
    {
        $model->fill($request);

        if ($model->isDirty()) {
            auth()->user()->tokens->each(fn ($token) => $this->logout($token->id));

            $model->email_verified_at = null;

            $model->sendEmailVerificationNotification();
        }

        return $model->save();
    }
}
