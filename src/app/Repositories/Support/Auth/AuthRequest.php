<?php

namespace App\Repositories\Support\Auth;

class AuthRequest
{
    /**
     * oauth token endpoint
     *
     * @var String
     */
    protected $endpoint = 'oauth/token';

    /**
     * Request a token for the given credentials on /oauth/token passport
     * (Internal request)
     *
     * @param String $email,
     * @param String $password
     * @return object
     */
    public function getToken(String $email, String $password)
    {
        $url = sprintf('%s', $this->endpoint);

        $params = [
            'grant_type' => 'password',
            'client_id' => config('services.client_id'),
            'client_secret' => config('services.client_secret'),
            'username' => $email,
            'password' => $password,
            'scope' => '*'
        ];

        $request = request()->create($url, 'POST', $params);
        $response = app()->handle($request);

        return (new AuthResponseHandler())->handle($response);
    }

    /**
     * Request access token via refresh token
     *
     * @param String $token
     *
     * @return object
     */
    public function getTokenViaRefreshToken(String $token)
    {
        $url = sprintf('%s', $this->endpoint);

        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token,
            'client_id' => config('services.client_id'),
            'client_secret' => config('services.client_secret'),
            'scope' => '*'
        ];

        $request = request()->create($url, 'POST', $params);
        $response = app()->handle($request);

        return (new AuthResponseHandler())->handle($response);
    }
}
