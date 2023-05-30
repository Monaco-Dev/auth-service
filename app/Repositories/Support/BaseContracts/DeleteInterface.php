<?php

namespace App\Repositories\Support\BaseContracts;

interface DeleteInterface
{
    /**
     * Remove the specified resource from storage.
     *
     * @param int|string $id
     * @return int
     */
    public function delete($id);
}
