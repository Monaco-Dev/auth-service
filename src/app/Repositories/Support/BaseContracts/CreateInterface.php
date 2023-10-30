<?php

namespace App\Repositories\Support\BaseContracts;

interface CreateInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $request);
}
