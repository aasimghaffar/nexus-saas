<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LockScreenController extends Controller
{
    /**
     * Show the lock screen.
     */
    public function show(Request $request)
    {
        if (! $request->session()->get('nexus.locked')) {
            return redirect()->route('dashboard');
        }

        return view('auth.lock-screen');
    }

    /**
     * Lock the current session (POST from header dropdown).
     */
    public function engage(Request $request)
    {
        $request->session()->put('nexus.locked', true);

        return redirect()->route('lock');
    }

    /**
     * Unlock with the account password.
     */
    public function release(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('The provided password is incorrect.'),
            ]);
        }

        $request->session()->forget('nexus.locked');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
