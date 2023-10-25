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
     * Primary resource of the service.
     */
    protected $resourceClass;

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

    /**
     * Format the response for API resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $data
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Database\Eloquent\Model
     */
    public function setResponseResource($data)
    {
        return isset($this->resourceClass) ? new $this->resourceClass($data) : $data;
    }

    /**
     * Format the response for API collection.
     *
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Support\LazyCollection|\Illuminate\Contracts\Pagination\LengthAwarePaginator $data
     * @return \Illuminate\Http\Resources\Json\ResourceCollection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\LazyCollection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function setResponseCollection($data)
    {
        return isset($this->resourceClass) ? $this->resourceClass::collection($data) : $data;
    }
}
