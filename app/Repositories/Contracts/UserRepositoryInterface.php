<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    FindInterface as Find,
    UpdateInterface as Update
};

interface UserRepositoryInterface extends Find, Update
{
    /**
     * Authenticate User
     * 
     * @param String $login
     * @param String $password
     * @return mixed
     */
    public function authenticate($login, $password);

    /**
     * Logout the user
     *
     * @param int $id
     * @return mixed
     */
    public function logout($id);

    /**
     * Refresh user token
     *
     * @param String $token
     * @return mixed
     */
    public function refreshToken($token);

    /**
     * Attempt to authorize user
     * 
     * @param String $login
     * @param String $password
     * @return bool
     */
    public function isValidCredential($login, $password);

    /**
     * Check if email is verified
     * 
     * @param int|string|null $id
     * @param string|null $login
     * @return bool
     */
    public function isEmailVerified($id = null, $login = null);

    /**
     * Reset user's password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(array $request);
}
