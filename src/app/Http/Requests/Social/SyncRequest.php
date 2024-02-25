<?php

namespace App\Http\Requests\Social;

use Illuminate\Foundation\Http\FormRequest;

class SyncRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'socials' => [
                'nullable',
                'array'
            ],
            'socials.*.provider' => [
                'required',
                'string'
            ],
            'socials.*.url' => [
                'required',
                'string',
                'active_url'
            ]
        ];
    }
}
