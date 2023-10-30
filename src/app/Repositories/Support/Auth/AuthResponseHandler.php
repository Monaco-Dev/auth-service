<?php

namespace App\Repositories\Support\Auth;

use Illuminate\Http\Response;

class AuthResponseHandler
{
    /**
     * Handle authentication response
     *
     * @return mixed
     */
    public function handle(Response $response)
    {
        switch ($response->status()) {
            case 200:
                return json_decode($response->content());
            default:
                abort($response->status(), 'Invalid Credentials.');
        }
    }
}
