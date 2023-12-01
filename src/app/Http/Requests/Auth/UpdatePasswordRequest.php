<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Support\PasswordRules;

class UpdatePasswordRequest extends FormRequest
{
    use PasswordRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email'
            ],
            'current_password' => [
                'required',
                'string',
                'current_password:api'
            ],
            'password' => $this->password(),
            'password_confirmation' => [
                'string'
            ]
        ];
    }
}
