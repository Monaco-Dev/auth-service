<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Connection extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'connection_user_id',
    ];

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
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function connection()
    {
        return $this->belongsTo(User::class, 'connection_user_id', 'id');
    }

    /**
     * Return BrokerLicense relationship.
     * 
     * @return App\Models\BrokerLicense
     */
    public function brokerLicense()
    {
        return $this->hasOne(BrokerLicense::class, 'user_id', 'connection_user_id');
    }
}
