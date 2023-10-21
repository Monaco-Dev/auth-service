<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    FindInterface as Find,
    UpdateOrCreateInterface as UpdateOrCreate,
    DeleteInterface as Delete
};

interface SlugRepositoryInterface extends Find, UpdateOrCreate, Delete
{
    /**
     * Retrieve user profile via slug.
     * 
     * @param string $slug
     * @return App\Models\User
     */
    public function profile($slug);
}
