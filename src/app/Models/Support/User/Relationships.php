<?php

namespace App\Models\Support\User;

use Illuminate\Support\Facades\Auth;

use App\Models\BrokerLicense;
use App\Models\User;

trait Relationships
{
    /**
     * Return BrokerLicense relationship.
     * 
     * @return App\Models\BrokerLicense
     */
    public function brokerLicense()
    {
        return $this->hasOne(BrokerLicense::class);
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
}
