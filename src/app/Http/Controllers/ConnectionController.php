<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ConnectionServiceInterface;
use App\Http\Requests\Connection\{
    ConnectRequest,
    DisconnectRequest
};
use App\Http\Requests\SearchRequest;
use App\Models\User;

class ConnectionController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\ConnectionServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\ConnectionServiceInterface $service
     */
    public function __construct(ConnectionServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Connection\ConnectRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function connect(ConnectRequest $request, User $user)
    {
        return $this->service->connect($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Connection\DisconnectRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function disconnect(DisconnectRequest $request, User $user)
    {
        return $this->service->disconnect($user);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request)
    {
        return $this->service->search($request->validated());
    }
}
