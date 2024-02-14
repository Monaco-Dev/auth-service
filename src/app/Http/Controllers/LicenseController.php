<?php

namespace App\Http\Controllers;

use App\Services\Contracts\LicenseServiceInterface;
use App\Http\Requests\License\UpdateOrCreateRequest;

class LicenseController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\LicenseServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\LicenseServiceInterface $service
     */
    public function __construct(LicenseServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Update or create the specified resource in storage.
     *
     * @param  \App\Http\Requests\License\UpdateOrCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(UpdateOrCreateRequest $request)
    {
        return $this->service->updateOrCreate($request->validated());
    }
}
