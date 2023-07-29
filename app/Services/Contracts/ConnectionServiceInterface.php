<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\StoreInterface as Store;

interface ConnectionServiceInterface extends Store
{
    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request);

    /**
     * Remove the specified resource from storage.
     *
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function disconnect($id);
}
