<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerLicense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'license_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_license_verified'
    ];

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsLicenseVerifiedAttribute()
    {
        return !!$this->verified_at;
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return Connection relationship.
     * 
     * @return App\Models\Connection
     */
    public function connection()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get all verified records only.
     */
    public function verified()
    {
        return $this->whereNotNull('verified_at');
    }

    /**
     * Identify if license is verified.
     * 
     * @return bool
     */
    public function isVerified()
    {
        return !!$this->verified_at;
    }
}
