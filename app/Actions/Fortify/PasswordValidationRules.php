<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * The validation rules used to validate passwords.
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'];
    }
}
