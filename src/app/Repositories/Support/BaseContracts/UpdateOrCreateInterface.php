<?php

namespace App\Repositories\Support\BaseContracts;

interface UpdateOrCreateInterface
{
    /**
     * Update or Create the specified resource in storage.
     *
     * @param array $query
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $query = [], array $data = []);
}
