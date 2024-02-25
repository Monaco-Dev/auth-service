<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SocialServiceInterface;
use App\Http\Requests\Social\SyncRequest;

class SocialController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\SocialServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\SocialServiceInterface $service
     */
    public function __construct(SocialServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Update or store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Social\SyncRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function sync(SyncRequest $request)
    {
        return $this->service->sync($request->validated());
    }
}
