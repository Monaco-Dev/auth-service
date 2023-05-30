<?php

namespace App\Repositories\Support\BaseContracts;

interface FindInterface
{
    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, bool $findOrFail = true);
}
