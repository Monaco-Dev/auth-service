<?php

namespace App\Repositories;

use App\Models\Socialite;
use App\Repositories\Contracts\SocialiteRepositoryInterface;

class SocialiteRepository extends Repository implements SocialiteRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Socialite
     */
    public function __construct(Socialite $model)
    {
        $this->model = $model;
    }
}
