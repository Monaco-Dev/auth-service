<?php

namespace App\Services\Contracts;

use App\Models\User;

interface ConnectionInvitationServiceInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function send(User $user);

    /**
     * Search for specific resources in the database.
     * 
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchIncoming(array $request);

    /**
     * Search for specific resources in the database.
     * 
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchOutgoing(array $request);
}
