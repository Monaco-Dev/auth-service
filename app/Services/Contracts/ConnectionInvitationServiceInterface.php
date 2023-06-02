<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\{
    StoreInterface as Store,
    DestroyInterface as Destroy
};

interface ConnectionInvitationServiceInterface extends Store, Destroy
{
    /**
     * Here you insert custom functions.
     */
}
