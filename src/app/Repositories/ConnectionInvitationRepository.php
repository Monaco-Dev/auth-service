<?php

namespace App\Repositories;

use App\Models\ConnectionInvitation;
use App\Repositories\Contracts\ConnectionInvitationRepositoryInterface;

class ConnectionInvitationRepository extends Repository implements ConnectionInvitationRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\ConnectionInvitation
     */
    public function __construct(ConnectionInvitation $model)
    {
        $this->model = $model;
    }
}
