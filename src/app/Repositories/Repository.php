<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Support\Traits\ModelResource;

abstract class Repository implements RepositoryInterface
{
    use ModelResource;

    /**
     * Eloquent model instance of the repository.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create the instance of the repository.
     *
     * @param \Illuminate\Database\Eloquent\Model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * The repository model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function model()
    {
        return $this->model;
    }
}
