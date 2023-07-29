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
use Illuminate\Support\Arr;

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

        return optional($user)->is_email_verified;
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
     * Get user's profile
     * 
     * @param int|string|null
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function profile($id = null)
    {
        return $this->model->withProfile()->find($id ?? Auth::user()->id);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function searchNetworks(array $request)
    {
        $search = Arr::get($request, 'search');

        $data = $this->model
            ->with(['brokerLicense'])
            ->withCount([
                'connectionUsers as is_network' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
                'pendingInvitations as is_following' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
                'requestInvitations as is_requested' => function ($query) {
                    $query->where('invitation_user_id', Auth::user()->id);
                }
            ])
            ->whereHas('networks', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });

        if ($search) {
            $data = $data->where(function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            })
                ->whereHas('brokerLicense', function ($query) use ($search) {
                    $query->where('license_number', 'like', '%' . $search . '%');
                });
        }

        return $data->paginate();
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $request)
    {
        $search = Arr::get($request, 'search');

        $data = $this->model->withProfile();

        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            })
                ->whereHas('brokerLicense', function ($query) use ($search) {
                    $query->where('license_number', 'like', '%' . $search . '%');
                });
        }

        return $data->paginate();
    }

    /**
     * Search for specific resources in the database.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pendingInvitations()
    {
        return $this->model
            ->withCount([
                'networkUsers',
                'requestInvitations',
                'pendingInvitations',
                'mutuals',
                'networks as is_network' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
                'pendingInvitations as is_following' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
                'requestInvitations as is_requested' => function ($query) {
                    $query->where('invitation_user_id', Auth::user()->id);
                }
            ])
            ->whereHas('pendingInvitations', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate();
    }

    /**
     * Search for specific resources in the database.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function requestInvitations()
    {
        return $this->model
            ->withCount([
                'networkUsers',
                'requestInvitations',
                'pendingInvitations',
                'mutuals',
                'networks as is_network' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
                'pendingInvitations as is_following' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
                'requestInvitations as is_requested' => function ($query) {
                    $query->where('invitation_user_id', Auth::user()->id);
                }
            ])
            ->whereHas('requestInvitations', function ($query) {
                $query->where('invitation_user_id', Auth::user()->id);
            })
            ->paginate();
    }
}
