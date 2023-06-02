<?php

namespace App\Services\Support\BaseContracts;

interface ShowInterface
{
    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id, bool $findOrFail = true);
}
