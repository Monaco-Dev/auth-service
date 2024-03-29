<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Support\PasswordRules;

class RegisterRequest extends FormRequest
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
            'first_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z ]*$/'
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z ]*$/'
            ],
            'email' => [
                'required',
                'email',
                'unique:users'
            ],
            'phone_number' => [
                'required',
                'max:11',
                'min:11',
                'string',
                'unique:users'
            ],
            'password' => $this->password()
        ];
    }
}
