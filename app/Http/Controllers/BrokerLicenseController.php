<?php

namespace App\Http\Controllers;

use App\Services\Contracts\BrokerLicenseServiceInterface;
use App\Http\Requests\BrokerLicense\UpdateRequest;

class BrokerLicenseController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\BrokerLicenseServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\BrokerLicenseServiceInterface $service
     */
    public function __construct(BrokerLicenseServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Update or create the specified resource in storage.
     *
     * @param  \App\Http\Requests\BrokerLicense\UpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        return $this->service->updateOrCreate($request->validated());
    }
}
