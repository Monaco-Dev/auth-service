<?php

namespace App\Repositories\Support\BaseContracts;

interface ForceDeleteInterface
{
    /**
     * Force remove the specified resource from storage.
     *
     * @param int|string $id
     * @return mixed
     */
    public function forceDelete($id);
}
