<?php

namespace App\Models\Support\User;

use Illuminate\Support\Facades\Auth;

use App\Models\License;
use App\Models\Social;
use App\Models\Socialite;
use App\Models\User;

trait Relationships
{
    /**
     * Return License relationship.
     * 
     * @return App\Models\License
     */
    public function license()
    {
        return $this->hasOne(License::class);
    }

    /**
     * Return Connections relationship.
     * 
     * @return App\Models\Connection
     */
    public function connections()
    {
        return $this->belongsToMany(
            User::class,
            'connections',
            'user_id',
            'connection_user_id'
        )
            ->withTimestamps();
    }

    /**
     * Return Connection Invitations relationship.
     * 
     * @return App\Models\ConnectionInvitation
     */
    public function outgoingInvites()
    {
        return $this->belongsToMany(
            User::class,
            'connection_invitations',
            'user_id',
            'connection_invitation_user_id'
        )
            ->withTimestamps();
    }

    /**
     * Return Connection Invitations relationship.
     * 
     * @return App\Models\ConnectionInvitation
     */
    public function incomingInvites()
    {
        return $this->belongsToMany(
            User::class,
            'connection_invitations',
            'connection_invitation_user_id',
            'user_id'
        )
            ->withTimestamps();
    }

    /**
     * Return Follow relationship.
     * 
     * @return App\Models\Follow
     */
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'user_id',
            'follow_user_id',
        )
            ->withTimestamps();
    }

    /**
     * Return Follow relationship.
     * 
     * @return App\Models\Follow
     */
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follow_user_id',
            'user_id',
        )
            ->withTimestamps();
    }

    /**
     * Get mutual connections.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function mutuals()
    {
        return $this->belongsToMany(
            User::class,
            'connections',
            'connection_user_id',
            'user_id',
        )
            ->wherePivot('connection_user_id', '!=', optional(Auth::user())->id);
    }

    /**
     * Get Socialite relationship.
     * 
     * @return App\Models\Socialite
     */
    public function socialite()
    {
        return $this->hasOne(Socialite::class);
    }

    /**
     * Get Social relationship.
     * 
     * @return App\Models\Social
     */
    public function socials()
    {
        return $this->hasMany(Social::class);
    }
}
