<?php

namespace App\Services\Contracts;

interface ServiceInterface
{
    /**
     * Repository instance of the service.
     * 
     * @return \App\Repositories\Contracts\RepositoryInterface
     */
    public function repository();
}
