<?php

namespace App\Http\Requests\BrokerLicense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
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
                'unique:broker_licenses,license_number,' . optional(Auth::user()->brokerLicense)->id,
                'digits:7'
            ],
            'expiration_date' => [
                'required',
                'date'
            ],
            'user_id' => [
                'required',
                'numeric',
                'exists:users,id'
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::user()->id
        ]);
    }
}
