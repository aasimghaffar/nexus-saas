<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'designation'  => ['nullable', 'string', 'max:100'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $user->forceFill([
                'name'              => $input['name'],
                'company_name'      => $input['company_name'] ?? $user->company_name,
                'phone'             => $input['phone'] ?? $user->phone,
                'designation'       => $input['designation'] ?? $user->designation,
                'email'             => $input['email'],
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();
        } else {
            $user->forceFill([
                'name'         => $input['name'],
                'company_name' => $input['company_name'] ?? $user->company_name,
                'phone'        => $input['phone'] ?? $user->phone,
                'designation'  => $input['designation'] ?? $user->designation,
                'email'        => $input['email'],
            ])->save();
        }
    }
}
