<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    FindInterface as Find,
    UpdateInterface as Update,
    DeleteInterface as Delete,
    UpdateOrCreateInterface as UpdateOrCreate
};

interface UserRepositoryInterface extends Find, Update, Delete, UpdateOrCreate
{
    /**
     * Authenticate User
     * 
     * @param String $email
     * @param String $password
     * @return mixed
     */
    public function authenticate($email, $password);

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
     * Reset user's password.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(array $request);

    /**
     * Get user's profile
     * 
     * @param int|string $id
     * @param bool $verified
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function profile($id, $verified = false);

    /**
     * Deactivate user to database.
     * 
     * @param int|string $id
     * @return bool
     */
    public function deactivate($id);
}
