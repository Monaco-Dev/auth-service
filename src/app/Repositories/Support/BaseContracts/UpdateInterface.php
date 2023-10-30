<?php

namespace App\Repositories\Support\BaseContracts;

interface UpdateInterface
{
    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(mixed $model, array $request);
}
