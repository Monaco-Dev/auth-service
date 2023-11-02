<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        PasswordsCanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'deactivated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deactivated_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_email_verified',
        'is_deactivated',
        'full_name',
        'url',
        'is_incoming_invite',
        'is_outgoing_invite',
        'is_following',
        'is_follower',
        'is_connection',
        'is_verified'
    ];

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
    public function getUrlAttribute()
    {
        $slug = optional(optional($this->slugs())->primary())->slug;

        return $slug ? "/profile/$slug" : null;
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
     * Return Slug relationship.
     * 
     * @return App\Models\Slug
     */
    public function slugs()
    {
        return $this->hasMany(Slug::class);
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
     * User must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at')
            ->whereNull('deactivated_at')
            ->whereNull('deleted_at');
        // ->whereHas('brokerLicense', function ($query) {
        //     $query->verified();
        // });
    }

    /**
     * Append with relationships.
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWithRelations(Builder $query)
    {
        $with = [
            'brokerLicense',
            'slugs',
        ];

        $withCount = [
            'connections',
            'incomingInvites',
            'outgoingInvites',
            'following',
            'followers'
        ];

        return $query->with($with)->withCount($withCount);
    }

    /**
     * Wildcard search query
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $search = null): Builder
    {
        $id = $id ?? optional(Auth::user())->id;

        return $query->withRelations()
            ->withCount([
                'mutuals' => function ($query) use ($id) {
                    $query->whereHas('connections', function ($query) use ($id) {
                        $query->where('connection_user_id', $id);
                    });
                }
            ])
            ->leftJoin('broker_licenses', 'broker_licenses.user_id', '=', 'users.id')
            ->where(function ($query) use ($search) {
                $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('license_number', 'like', '%' . $search . '%');
            })
            ->verified()
            ->orderBy('mutuals_count', 'desc')
            ->orderByRaw('LOCATE("' . $search . '", CONCAT(first_name, " ", last_name)) desc')
            ->orderByRaw('LOCATE("' . $search . '", email) desc')
            ->orderByRaw('LOCATE("' . $search . '", phone_number) desc')
            ->orderByRaw('LOCATE("' . $search . '", license_number) desc');
    }
}
