<?php

namespace App\Rules\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class LoginRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!UserRepository::isValidCredential(
            request()->login,
            request()->password
        )) {
            $fail('Invalid Credentials.');
        }
    }
}
