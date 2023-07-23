<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    FindInterface as Find,
    CreateInterface as Create,
    ForceDeleteInterface as ForceDelete
};

interface BrokerLicenseRepositoryInterface extends Find, Create, ForceDelete
{
    /**
     * Here you insert custom functions.
     */
}
