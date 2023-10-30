<?php

namespace App\Services\Support\BaseContracts;

interface StoreInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request);
}
