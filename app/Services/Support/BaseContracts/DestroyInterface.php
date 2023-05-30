<?php

namespace App\Services\Support\BaseContracts;

interface DestroyInterface
{
    /**
     * Remove the specified resource from storage.
     *
     * @param int|string $id
     * @return int
     */
    public function destroy($id);
}
