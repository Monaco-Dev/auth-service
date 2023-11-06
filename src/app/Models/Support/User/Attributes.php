<?php

namespace App\Models\Support\User;

use Illuminate\Support\Facades\Auth;

trait Attributes
{
    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsEmailVerifiedAttribute()
    {
        return !!$this->email_verified_at;
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsDeactivatedAttribute()
    {
        return !!$this->deactivated_at;
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsOutgoingInviteAttribute()
    {
        if (!optional(Auth::user())->id) return false;

        return $this->incomingInvites()
            ->wherePivot(
                'user_id',
                Auth::user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsIncomingInviteAttribute()
    {
        if (!optional(Auth::user())->id) return false;

        return $this->outgoingInvites()
            ->wherePivot(
                'connection_invitation_user_id',
                Auth::user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsFollowingAttribute()
    {
        if (!optional(Auth::user())->id) return false;

        return $this->followers()
            ->wherePivot(
                'user_id',
                Auth::user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsFollowerAttribute()
    {
        if (!optional(Auth::user())->id) return false;

        return $this->following()
            ->wherePivot(
                'follow_user_id',
                Auth::user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsConnectionAttribute()
    {
        if (!optional(Auth::user())->id) return false;

        return $this->connections()
            ->wherePivot(
                'connection_user_id',
                Auth::user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsVerifiedAttribute()
    {
        return !!$this->verified()->find($this->id);
    }

    /**
     * Hash password attribute.
     * 
     * @param string $value
     * @return mixed
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_ARGON2I);
    }
}
