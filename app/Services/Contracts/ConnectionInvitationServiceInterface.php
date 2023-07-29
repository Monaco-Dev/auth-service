<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\{
    IndexInterface as Index,
    StoreInterface as Store
};

interface ConnectionInvitationServiceInterface extends Index, Store
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requests();

    /**
     * Remove the specified resource from storage.
     *
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id);
}
