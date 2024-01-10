<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\{
    UpdateInterface as Update,
    ShowInterface as Show
};

interface UserServiceInterface extends Update, Show
{
    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request);

    /**
     * Search for specific resources in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMutuals();
}
