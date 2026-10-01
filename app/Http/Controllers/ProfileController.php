<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $sessions = collect();

        if (config('session.driver') === 'database') {
            $sessions = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->id)
                ->orderByDesc('last_activity')
                ->get()
                ->map(fn ($s) => (object) [
                    'agent'      => $this->readableAgent($s->user_agent ?? ''),
                    'ip'         => $s->ip_address,
                    'current'    => $s->id === $request->session()->getId(),
                    'lastActive' => \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans(),
                ]);
        }

        return view('profile.show', ['user' => $request->user(), 'sessions' => $sessions]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);

        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->forceFill([
            'avatar_path' => $request->file('avatar')->store('avatars', 'public'),
        ])->save();

        return back()->with('status', 'Profile photo updated.');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'prefs'   => ['nullable', 'array'],
            'prefs.*' => ['string', 'in:product_updates,security_alerts,billing_emails,ticket_replies,weekly_digest'],
        ]);

        $request->user()->forceFill([
            'notification_prefs' => array_values($validated['prefs'] ?? []),
        ])->save();

        return back()->with('status', 'Notification preferences saved.');
    }

    public function logoutOtherSessions(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages(['password' => __('The provided password is incorrect.')])
                ->errorBag('logoutOtherSessions');
        }

        Auth::logoutOtherDevices($request->password);

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        return back()->with('status', 'All other sessions have been signed out.');
    }

    private function readableAgent(string $agent): string
    {
        $browser = str_contains($agent, 'Firefox') ? 'Firefox'
            : (str_contains($agent, 'Edg') ? 'Edge'
            : (str_contains($agent, 'Chrome') ? 'Chrome'
            : (str_contains($agent, 'Safari') ? 'Safari' : 'Browser')));

        $os = str_contains($agent, 'Windows') ? 'Windows'
            : (str_contains($agent, 'Mac') ? 'macOS'
            : (str_contains($agent, 'Android') ? 'Android'
            : (str_contains($agent, 'iPhone') || str_contains($agent, 'iPad') ? 'iOS' : 'Linux')));

        return "{$browser} on {$os}";
    }
}
