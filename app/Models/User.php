<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        CascadeSoftDeletes,
        PasswordsCanResetPassword;

    /**
     * The relationships that are soft deletable.
     * 
     * @var array<string>
     */
    protected $cascadeDeletes = [
        'brokerLicense',
        'socials',
        'connections',
        'connectionInvitations'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'password',
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
        'phone_number_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
     * User email or username for authentication.
     * 
     * @return Model
     */
    public function findForPassport($login)
    {
        return $this->where('email', $login)->orWhere('username', $login)->first();
    }

    /**
     * Return Social relationship.
     * 
     * @return App\Models\Social
     */
    public function socials()
    {
        return $this->hasMany(Social::class);
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function networkUsers()
    {
        return $this->belongsToMany(User::class, 'connections', 'user_id', 'connection_user_id')
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function connectionUsers()
    {
        return $this->belongsToMany(User::class, 'connections', 'connection_user_id', 'user_id')
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return Connection relationship.
     * 
     * @return App\Models\Connection
     */
    public function connections()
    {
        return $this->hasMany(Connection::class)
            ->whereHas('connection.brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return ConnectionInvitation relationship.
     * 
     * @return App\Models\ConnectionInvitation
     */
    public function connectionInvitations()
    {
        return $this->hasMany(ConnectionInvitation::class)
            ->whereHas('invitation.brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
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
     * Return ConnectionInvitation relationship.
     * 
     * @return App\Models\User
     */
    public function pendingInvitations()
    {
        return $this->belongsToMany(User::class, 'connection_invitations', 'invitation_user_id', 'user_id')
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return ConnectionInvitation relationship.
     * 
     * @return App\Models\ConnectionInvitation
     */
    public function requestInvitations()
    {
        return $this->belongsToMany(User::class, 'connection_invitations', 'user_id', 'invitation_user_id')
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Get user's relationships.
     */
    public static function withProfile()
    {
        return self::with([
            'socials',
            'brokerLicense',
            'networkUsers.brokerLicense',
            'requestInvitations.brokerLicense',
            'pendingInvitations.brokerLicense',
            'mutuals.brokerLicense'
        ]);
    }

    /**
     * Get connection mutuals.
     * 
     * @return App\Models\User
     */
    public function mutuals()
    {
        return $this->belongsToMany(User::class, 'connections', 'connection_user_id', 'user_id')
            ->whereHas('connections', function ($query) {
                $query->where('connection_user_id', Auth::user()->id);
            })
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }
}
