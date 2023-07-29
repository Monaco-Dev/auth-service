<?php

namespace App\Repositories\Contracts;

interface ConnectionInvitationRepositoryInterface
{
    /**
     * Remove the specified resource from storage.
     *
     * @param array $request
     * @return int
     */
    public function cancel(array $request);
}
