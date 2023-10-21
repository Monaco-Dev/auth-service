<?php

namespace App\Http\Requests\Slug;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'slug' => [
                'required',
                'string',
                Rule::unique('slugs', 'slug')->ignore(Auth::user()->id, 'user_id')
            ]
        ];
    }
}
