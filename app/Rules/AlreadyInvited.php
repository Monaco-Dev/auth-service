<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

use App\Models\ConnectionInvitation;

class AlreadyInvited implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = ConnectionInvitation::where('user_id', Auth::user()->id)
            ->where('invitation_user_id', $value)
            ->exists();

        if ($model) $fail('The :attribute already have an invitation.');
    }
}
