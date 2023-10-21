<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ConnectionInvitationServiceInterface;
use App\Http\Requests\ConnectionInvitation\{
    CancelRequest,
    SearchRequest,
    SendRequest
};
use App\Models\User;

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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ConnectionInvitation\SendRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function send(SendRequest $request, User $user)
    {
        return $this->service->send($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\ConnectionInvitation\CancelRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function cancel(CancelRequest $request, User $user)
    {
        return $this->service->cancel($user);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\ConnectionInvitation\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchIncoming(SearchRequest $request)
    {
        return $this->service->searchIncoming($request->validated());
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\ConnectionInvitation\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchOutgoing(SearchRequest $request)
    {
        return $this->service->searchOutgoing($request->validated());
    }
}
