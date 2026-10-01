<?php

namespace App\Http\Controllers;

/**
 * Lets signed-in users PREVIEW the auth screens from the sidebar.
 * The real guest routes redirect authenticated users to the dashboard,
 * so without this, a buyer exploring the product could never see them.
 */
class AuthPreviewController extends Controller
{
    private const PAGES = [
        'login'            => 'auth.login',
        'register'         => 'auth.register',
        'forgot-password'  => 'auth.forgot-password',
        'reset-password'   => 'auth.reset-password',
        'verify-email'     => 'auth.verify-email',
        'confirm-password' => 'auth.confirm-password',
        'two-factor'       => 'auth.two-factor-challenge',
    ];

    public function show(string $page)
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        return view(self::PAGES[$page], ['request' => request()]);
    }
}
