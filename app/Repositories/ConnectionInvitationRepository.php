<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

use App\Models\ConnectionInvitation;
use App\Repositories\Contracts\ConnectionInvitationRepositoryInterface;

class ConnectionInvitationRepository extends Repository implements ConnectionInvitationRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\ConnectionInvitation
     */
    public function __construct(ConnectionInvitation $model)
    {
        $this->model = $model;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param array $request
     * @return int
     */
    public function cancel(array $request)
    {
        return $this->model
            ->where('invitation_user_id', Arr::get($request, 'invitation_user_id'))
            ->where('user_id', Arr::get($request, 'user_id'))
            ->forceDelete();
    }
}
