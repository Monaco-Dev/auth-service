<?php

namespace App\Policies;

use App\Models\User;

class ConnectionInvitationPolicy
{
    /**
     * Determine whether the user can invite the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\User $model
     * @return bool
     */
    public function invite(User $user, User $model)
    {
        return !$user->connections()->where('connection_user_id', $model->id)->exists() &&
            !$model->connections()->where('connection_user_id', $user->id)->exists() &&
            !$user->incomingInvites()->where('user_id', $model->id)->exists() &&
            !$user->outgoingInvites()->where('connection_invitation_user_id', $model->id)->exists();
    }
}
