<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string'
            ],
            'last_name' => [
                'required',
                'string'
            ],
            'username' => [
                'required',
                'string',
                'unique:users'
            ],
            'email' => [
                'required',
                'email',
                'unique:users'
            ],
            'phone_number' => [
                'nullable',
                'max:11',
                'min:11',
                'string'
            ],
            'password' => [
                'required',
                'confirmed',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'broker_license_number' => [
                'required',
                'sometimes',
                'unique:broker_licenses,license_number',
                'digits:7'
            ],
            'expiration_date' => [
                'required',
                'sometimes',
                'date'
            ]
        ];
    }
}
