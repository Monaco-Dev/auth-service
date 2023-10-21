<?php

namespace App\Http\Requests\Support;

use Illuminate\Validation\Rules\Password;

trait PasswordRules
{
    /**
     * Default password hardening rules.
     * 
     * @return array
     */
    private function password()
    {
        return [
            'required',
            'confirmed',
            'string',
            'max:16',
            Password::min(8)
                ->letters()
                ->numbers()
                ->mixedCase()
                ->symbols()
                ->uncompromised()
        ];
    }
}
