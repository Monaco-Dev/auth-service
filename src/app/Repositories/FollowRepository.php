<?php

namespace App\Repositories;

use App\Models\Follow;
use App\Repositories\Contracts\FollowRepositoryInterface;

class FollowRepository extends Repository implements FollowRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Follow
     */
    public function __construct(Follow $model)
    {
        $this->model = $model;
    }
}
