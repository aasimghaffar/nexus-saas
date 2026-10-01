<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Events\Dispatcher;

class LogAuthenticationEvents
{
    public function handleLogin(Login $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties(['ip' => request()->ip(), 'agent' => request()->userAgent(), 'status' => 'success'])
            ->log('Signed in');
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            activity('auth')
                ->causedBy($event->user)
                ->withProperties(['ip' => request()->ip(), 'status' => 'success'])
                ->log('Signed out');
        }
    }

    public function handleFailed(Failed $event): void
    {
        activity('auth')
            ->withProperties([
                'ip'     => request()->ip(),
                'email'  => (string) request()->input('email'),
                'status' => 'failed',
            ])
            ->log('Failed sign-in attempt');
    }

    public function handlePasswordReset(PasswordReset $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties(['ip' => request()->ip(), 'status' => 'success'])
            ->log('Password reset');
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class         => 'handleLogin',
            Logout::class        => 'handleLogout',
            Failed::class        => 'handleFailed',
            PasswordReset::class => 'handlePasswordReset',
        ];
    }
}
