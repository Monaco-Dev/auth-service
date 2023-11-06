<?php

namespace App\Models\Support\BrokerLicense;

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
}
