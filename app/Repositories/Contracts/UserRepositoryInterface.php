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

    /**
     * Get user's profile
     * 
     * @param int|string|null
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function profile($id = null);

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function searchNetworks(array $request);

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $request);

    /**
     * Search for specific resources in the database.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pendingInvitations();

    /**
     * Search for specific resources in the database.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function requestInvitations();
}
