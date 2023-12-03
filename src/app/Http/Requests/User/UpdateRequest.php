<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->user);
    }

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
                'sometimes',
                'string',
                'max:50',
                'regex:/^[a-zA-Z ]*$/'
            ],
            'last_name' => [
                'required',
                'sometimes',
                'string',
                'max:50',
                'regex:/^[a-zA-Z ]*$/'
            ],
            'email' => [
                'required',
                'sometimes',
                'email',
                'unique:users,email,' . $this->user->id
            ],
            'phone_number' => [
                'required',
                'sometimes',
                'max:11',
                'min:11',
                'string',
                'unique:users,phone_number,' . $this->user->id
            ],
            'password' => [
                'required_with:email',
                'string',
                'current_password:api'
            ]
        ];
    }
}
