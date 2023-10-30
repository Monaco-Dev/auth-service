<?php

namespace App\Repositories\Support\BaseContracts;

interface FirstOrCreateInterface
{
    /**
     * Display the specified resource or store a newly created resource in storage.
     *
     * @param array $where
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $where = [], array $data = []);
}
