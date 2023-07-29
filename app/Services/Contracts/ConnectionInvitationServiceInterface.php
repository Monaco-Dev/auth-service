<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\StoreInterface as Store;

interface ConnectionInvitationServiceInterface extends Store
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id);
}
