<?php

namespace App\Repositories;

use App\Models\BrokerLicense;
use App\Repositories\Contracts\BrokerLicenseRepositoryInterface;

class BrokerLicenseRepository extends Repository implements BrokerLicenseRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\BrokerLicense
     */
    public function __construct(BrokerLicense $model)
    {
        $this->model = $model;
    }
}
