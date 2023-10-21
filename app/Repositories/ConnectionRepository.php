<?php

namespace App\Repositories;

use App\Models\Connection;
use App\Repositories\Contracts\ConnectionRepositoryInterface;

class ConnectionRepository extends Repository implements ConnectionRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Connection
     */
    public function __construct(Connection $model)
    {
        $this->model = $model;
    }
}
