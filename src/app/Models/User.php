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
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use App\Models\Support\User\Attributes;
use App\Models\Support\User\Relationships;
use App\Models\Support\User\Scopes;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        PasswordsCanResetPassword,
        Attributes,
        Scopes,
        Relationships,
        HasSlug;

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
        'deactivated_at',
        'slug',
        'avatar',
        'email_verified_at'
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
        'is_incoming_invite',
        'is_outgoing_invite',
        'is_following',
        'is_follower',
        'is_connection',
        'is_verified',
        'avatar_url'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                $check = User::where('first_name', $model->first_name)
                    ->where('last_name', $model->last_name)
                    ->exists();

                $uuid = Str::uuid();

                $name = "{$model->first_name} {$model->last_name}";

                return ($check) ? "{$name} {$uuid}" : $name;
            })
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }
}
