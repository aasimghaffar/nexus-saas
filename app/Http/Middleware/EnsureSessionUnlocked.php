<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionUnlocked
{
    /**
     * Redirect locked sessions to the lock screen.
     * The lock screen, unlock, and logout routes are excluded via route definition.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->session()->get('nexus.locked')) {
            return redirect()->route('lock');
        }

        return $next($request);
    }
}
