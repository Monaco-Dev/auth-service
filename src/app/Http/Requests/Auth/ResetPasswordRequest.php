<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Support\PasswordRules;

class ResetPasswordRequest extends FormRequest
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
            'token' => [
                'required'
            ],
            'email' => [
                'required',
                'email'
            ],
            'password' => $this->password(),
            'password_confirmation' => [
                'string'
            ]
        ];
    }
}
