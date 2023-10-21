<?php

namespace App\Policies;

use App\Models\Slug;
use App\Models\User;

class SlugPolicy
{
    /**
     * Determine whether the user can delete the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Slug $slug
     * @return bool
     */
    public function deleteSlug(User $user, Slug $slug): bool
    {
        return $slug->user_id == $user->id;
    }
}
