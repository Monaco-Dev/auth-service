<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Support\BrokerLicense\Attributes;
use App\Models\Support\BrokerLicense\Relationships;
use App\Models\Support\BrokerLicense\Scopes;

class BrokerLicense extends Model
{
    use HasFactory, Attributes, Scopes, Relationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'verified_at',
        'license_number',
        'expiration_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'expiration_date' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_license_verified',
        'is_license_expired'
    ];
}
