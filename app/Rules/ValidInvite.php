<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

use App\Models\ConnectionInvitation;

class ValidInvite implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = ConnectionInvitation::where('user_id', $value)
            ->where('invitation_user_id', Auth::user()->id)
            ->exists();

        if (!$model) $fail('The :attribute is invalid.');
    }
}
