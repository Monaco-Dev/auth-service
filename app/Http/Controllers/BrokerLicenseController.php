<?php

namespace App\Http\Controllers;

use App\Services\Contracts\BrokerLicenseServiceInterface;
use App\Http\Requests\BrokerLicense\StoreRequest;

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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BrokerLicense\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->service->store($request->validated());
    }
}
