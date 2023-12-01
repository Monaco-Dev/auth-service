<?php

namespace App\Policies;

use App\Models\User;

class FollowPolicy
{
    /**
     * Determine whether the user can follow the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\User $model
     * @return bool
     */
    public function follow(User $user, User $model)
    {
        return $user->id != $model->id &&
            !$user->following()->where('follow_user_id', $model->id)->exists();
    }

    /**
     * Determine whether the user can unfollow the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\User $model
     * @return bool
     */
    public function unfollow(User $user, User $model)
    {
        return $user->id != $model->id &&
            $user->following()->where('follow_user_id', $model->id)->exists();
    }
}
