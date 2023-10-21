<?php

namespace App\Http\Requests\ConnectionInvitation;

use Illuminate\Foundation\Http\FormRequest;

class CancelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('cancel-invite', $this->user);
    }
}
