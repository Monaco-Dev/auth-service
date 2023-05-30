<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * The repository model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function model();
}
