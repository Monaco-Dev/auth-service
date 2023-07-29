<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\CreateInterface as Create;

interface ConnectionRepositoryInterface extends Create
{
    /**
     * Remove the specified resource from storage.
     *
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function disconnect(array $request);
}
