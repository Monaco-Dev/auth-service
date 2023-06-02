<?php

namespace App\Http\Requests\Connection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\Connection;

class DisconnectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->id == Auth::user()->id) return false;

        $model = Connection::where('connection_user_id', $this->id)
            ->where('user_id', Auth::user()->id)
            ->exists();

        if (!$model) return false;

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
