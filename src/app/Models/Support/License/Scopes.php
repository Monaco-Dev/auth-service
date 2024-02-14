<?php

namespace App\Models\Support\License;

use Illuminate\Database\Eloquent\Builder;

trait Scopes
{
    /**
     * License must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('verified_at')
            ->where('expiration_date', '>', now());
    }
}
