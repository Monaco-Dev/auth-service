<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = UserRepository::find($this->id, false);

        if (!$user) abort(404, 'Not found.');

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
            //
        ];
    }
}
