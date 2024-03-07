<?php

namespace App\Http\Requests\License;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateOrCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'license_number' => [
                'required',
                'string',
                'unique:licenses,license_number,' . optional(auth()->user()->license)->id,
            ],
            'expiration_date' => [
                'required',
                'date'
            ],
            'file' => [
                'required',
                File::image()->max(10000)
            ]
        ];
    }
}
