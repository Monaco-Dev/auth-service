<?php

namespace App\Repositories;

use App\Models\License;
use App\Repositories\Contracts\LicenseRepositoryInterface;

class LicenseRepository extends Repository implements LicenseRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\License
     */
    public function __construct(License $model)
    {
        $this->model = $model;
    }
}
