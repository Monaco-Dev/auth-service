<?php

namespace App\Repositories\Support\BaseContracts;

interface DeleteInterface
{
    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $model
     * @return int
     */
    public function delete(mixed $model);
}
