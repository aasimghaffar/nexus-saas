<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name'   => ['required', 'string', 'max:100'],
            'last_name'    => ['required', 'string', 'max:100'],
            'company_name' => ['required', 'string', 'max:150'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => $this->passwordRules(),
            'terms'        => ['accepted'],
        ], [
            'terms.accepted' => 'You must agree to the Terms of Service.',
        ])->validate();

        return User::create([
            'name'         => trim($input['first_name'].' '.$input['last_name']),
            'company_name' => $input['company_name'],
            'email'        => $input['email'],
            'password'     => Hash::make($input['password']),
        ]);
    }
}
