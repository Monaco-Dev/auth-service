<?php

namespace App\Repositories;

use App\Models\Social;
use App\Repositories\Contracts\SocialRepositoryInterface;

class SocialRepository extends Repository implements SocialRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Social
     */
    public function __construct(Social $model)
    {
        $this->model = $model;
    }
}
