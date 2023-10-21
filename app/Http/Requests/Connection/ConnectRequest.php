<?php

namespace App\Http\Requests\Connection;

use Illuminate\Foundation\Http\FormRequest;

class ConnectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('connect', $this->user);
    }
}
