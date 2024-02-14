<?php

namespace App\Models\Support\License;

use Illuminate\Support\Facades\Storage;

trait Attributes
{
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
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsLicenseExpiredAttribute()
    {
        return $this->expiration_date <= now();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getUrlAttribute()
    {
        return Storage::disk('gcs')->url($this->file);
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getLicenseTypeAttribute()
    {
        return ($this->type == 'broker') ? 'Real Estate Broker' : 'Real Estate Salesperson';
    }
}
