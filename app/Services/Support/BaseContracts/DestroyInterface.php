<?php

namespace App\Services\Support\BaseContracts;

interface DestroyInterface
{
    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $model
     * @return int
     */
    public function destroy(mixed $model);
}
