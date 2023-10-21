<?php

namespace App\Services\Contracts;

interface BrokerLicenseServiceInterface
{
    /**
     * Update or create the specified resource in storage.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(array $request);
}
