<?php

namespace App\Models\Support\License;

use App\Models\User;

trait Relationships
{
    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
