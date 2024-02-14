<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Support\License\Attributes;
use App\Models\Support\License\Relationships;
use App\Models\Support\License\Scopes;

class License extends Model
{
    use HasFactory,
        SoftDeletes,
        Attributes,
        Scopes,
        Relationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'license_number',
        'type',
        'file',
        'verified_at',
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
        'is_license_expired',
        'url',
        'license_type'
    ];
}
