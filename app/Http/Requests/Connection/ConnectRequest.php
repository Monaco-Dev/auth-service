<?php

namespace App\Http\Requests\Connection;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ValidInvite;

class ConnectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:App\Models\ConnectionInvitation,user_id',
                'exists:users,id',
                new ValidInvite
            ]
        ];
    }
}
