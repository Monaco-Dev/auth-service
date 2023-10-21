<?php

namespace App\Policies;

use App\Models\User;

class ConnectionPolicy
{
    /**
     * Determine whether the user can connect the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\User $model
     * @return bool
     */
    public function connect(User $user, User $model)
    {
        return !$user->connections()->where('connection_user_id', $model->id)->exists() &&
            $user->incomingInvites()->where('connection_invitation_user_id', $model->id)->exists() &&
            !$user->outgoingInvites()->where('connection_invitation_user_id', $model->id)->exists();
    }

    /**
     * Determine whether the user can disconnect the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\User $model
     * @return bool
     */
    public function disconnect(User $user, User $model)
    {
        return $user->connections()->where('connection_user_id', $model->id)->exists() &&
            !$user->incomingInvites()->where('connection_invitation_user_id', $model->id)->exists() &&
            !$user->outgoingInvites()->where('connection_invitation_user_id', $model->id)->exists();
    }
}
