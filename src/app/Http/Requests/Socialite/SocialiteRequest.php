<?php

namespace App\Http\Requests\Socialite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialiteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'driver' => [
                'string',
                'required',
                Rule::in(['google', 'facebook', 'linkedin-openid'])
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'driver' => $this->driver
        ]);
    }
}
