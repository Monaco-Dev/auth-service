<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = UserRepository::find($this->id, false);

        if (!$user) abort(404, 'Not found.');

        return $user->id == Auth::user()->id;
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
                'string'
            ],
            'last_name' => [
                'required',
                'sometimes',
                'string'
            ],
            'username' => [
                'required',
                'sometimes',
                'string',
                'unique:users'
            ],
            'email' => [
                'required',
                'sometimes',
                'email',
                'unique:users'
            ],
            'phone_number' => [
                'required',
                'sometimes',
                'max:11',
                'min:11',
                'string'
            ]
        ];
    }
}
