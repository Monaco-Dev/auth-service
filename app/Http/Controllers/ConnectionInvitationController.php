<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ConnectionInvitationServiceInterface;
use App\Http\Requests\ConnectionInvitation\{
    CancelRequest,
    InviteRequest,
};

class ConnectionInvitationController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\ConnectionInvitationServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\ConnectionInvitationServiceInterface $service
     */
    public function __construct(ConnectionInvitationServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->index();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requests()
    {
        return $this->service->requests();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ConnectionInvitation\InviteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function invite(InviteRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Connection\CancelRequest  $request
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(CancelRequest $request, $id)
    {
        return $this->service->cancel($id);
    }
}
