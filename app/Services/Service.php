<?php

namespace App\Services;

use App\Repositories\Contracts\RepositoryInterface;
use App\Services\Contracts\ServiceInterface;
use App\Services\Support\Traits\RepositoryResource;

abstract class Service implements ServiceInterface
{
    use RepositoryResource;

    /**
     * Primary repository of the service.
     * 
     * @var \App\Repositories\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * Create the instance of the service.
     *
     * @param \App\Repositories\Contracts\RepositoryInterface
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Repository instance of the service.
     * 
     * @return \App\Repositories\Contracts\RepositoryInterface
     */
    public function repository()
    {
        return $this->repository;
    }
}
