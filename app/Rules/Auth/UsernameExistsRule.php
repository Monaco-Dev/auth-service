<?php

namespace App\Rules\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class UsernameExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            !filter_var($value, FILTER_VALIDATE_EMAIL) &&
            !UserRepository::model()->where('username', $value)->exists()
        ) {
            $fail('Invalid Credentials.');
        }
    }
}
