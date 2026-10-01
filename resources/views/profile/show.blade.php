@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
  <div>
    <h1 class="text-2xl font-extrabold tracking-tight">Account Settings</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Manage your personal profile, security, sessions, and notification preferences.</p>
  </div>

  {{-- Tab nav (handled by nexus-app.js data-tabs) --}}
  <div class="flex items-center gap-2 border-b border-slate-200 dark:border-dark-800 pb-2 overflow-x-auto" data-tabs-group>
    <button data-tab-target="#tab-profile" class="px-4 py-2 rounded-xl text-xs font-bold text-brand-600 bg-brand-50 dark:bg-brand-950/40 dark:text-brand-400 transition-colors whitespace-nowrap">Personal Profile</button>
    <button data-tab-target="#tab-security" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-dark-400 dark:hover:text-white transition-colors whitespace-nowrap">Password &amp; 2FA</button>
    <button data-tab-target="#tab-sessions" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-dark-400 dark:hover:text-white transition-colors whitespace-nowrap">Active Sessions</button>
    <button data-tab-target="#tab-notifications" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-dark-400 dark:hover:text-white transition-colors whitespace-nowrap">Notifications</button>
  </div>

  {{-- Tab 1: Personal Profile --}}
  <div id="tab-profile" class="tab-panel space-y-6">
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <h2 class="text-base font-bold text-slate-900 dark:text-white">Profile Details</h2>
      <p class="text-xs text-slate-400 mt-1 mb-5">This information is visible to teammates in your workspace.</p>

      {{-- Avatar --}}
      <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="flex items-center gap-4 pb-6 border-b border-slate-100 dark:border-dark-800">
        @csrf
        <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-full border-2 border-brand-500" alt="">
        <div class="text-xs">
          <input type="file" name="avatar" accept="image/*" required class="block text-[11px] text-slate-500 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/50 dark:file:text-brand-400 file:font-semibold file:text-[11px]">
          @error('avatar') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
          <button type="submit" class="mt-2 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Upload Photo</button>
        </div>
      </form>

      {{-- Profile info (Fortify: PUT /user/profile-information) --}}
      <form method="POST" action="{{ url('/user/profile-information') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mt-6">
        @csrf
        @method('PUT')
        <div>
          <label class="block mb-1.5 font-bold">Full Name</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('name', 'updateProfileInformation') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block mb-1.5 font-bold">Work Email</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('email', 'updateProfileInformation') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block mb-1.5 font-bold">Company</label>
          <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div>
          <label class="block mb-1.5 font-bold">Phone Number</label>
          <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+44 20 7946 0000"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div class="sm:col-span-2">
          <label class="block mb-1.5 font-bold">Designation</label>
          <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" placeholder="VP of Product &amp; Engineering"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div class="sm:col-span-2 flex justify-end">
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Tab 2: Password & 2FA --}}
  <div id="tab-security" class="tab-panel hidden space-y-6">
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <h2 class="text-base font-bold text-slate-900 dark:text-white">Change Password</h2>
      <p class="text-xs text-slate-400 mt-1 mb-5">Use at least 8 characters with mixed case and numbers.</p>

      <form method="POST" action="{{ url('/user/password') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
        @csrf
        @method('PUT')
        <div>
          <label class="block mb-1 font-bold">Current Password</label>
          <input type="password" name="current_password" required autocomplete="current-password"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('current_password', 'updatePassword') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block mb-1 font-bold">New Password</label>
          <input type="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('password', 'updatePassword') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block mb-1 font-bold">Confirm New Password</label>
          <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-type new password"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div class="sm:col-span-3 flex justify-end">
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">Update Password</button>
        </div>
      </form>
    </div>

    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold">Two-Factor Authentication (2FA)</h3>
          <p class="text-xs text-slate-400 mt-1">Add a second layer of security using a TOTP authenticator app.</p>
        </div>
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $user->hasTwoFactorEnabled() ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-dark-800 dark:text-dark-300' }}">
          {{ $user->hasTwoFactorEnabled() ? 'Enabled' : 'Disabled' }}
        </span>
      </div>

      <div class="mt-5 text-xs">
        @if (! $user->two_factor_secret)
          {{-- State 1: disabled --}}
          <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold shadow-md shadow-brand-500/20">Enable 2FA</button>
          </form>

        @elseif (! $user->hasTwoFactorEnabled())
          {{-- State 2: pending confirmation — show QR + confirm code --}}
          <div class="space-y-4">
            <p class="font-semibold text-slate-700 dark:text-dark-200">Scan this QR code with Google Authenticator, 1Password, or Authy, then enter the 6-digit code to confirm.</p>
            <div class="p-4 bg-white rounded-2xl border border-slate-200 dark:border-dark-700 inline-block">{!! $user->twoFactorQrCodeSvg() !!}</div>
            <p class="text-slate-400">Or enter this key manually: <code class="font-mono font-bold text-slate-600 dark:text-dark-300">{{ decrypt($user->two_factor_secret) }}</code></p>
            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}" class="flex items-center gap-2">
              @csrf
              <input type="text" name="code" inputmode="numeric" placeholder="123456" required
                     class="w-32 px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 text-center font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
              <button type="submit" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold">Confirm</button>
            </form>
            @error('code', 'confirmTwoFactorAuthentication') <span class="text-rose-500 font-semibold block">{{ $message }}</span> @enderror
            <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
              @csrf @method('DELETE')
              <button type="submit" class="text-slate-400 hover:text-rose-500 font-semibold">Cancel setup</button>
            </form>
          </div>

        @else
          {{-- State 3: enabled — recovery codes + disable --}}
          <div class="space-y-4">
            <div>
              <p class="font-semibold text-slate-700 dark:text-dark-200 mb-2">Emergency recovery codes (store them somewhere safe):</p>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 font-mono text-[11px]">
                @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                  <span class="px-2 py-1.5 rounded-lg bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 text-center">{{ $code }}</span>
                @endforeach
              </div>
            </div>
            <div class="flex items-center gap-2">
              <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold">Regenerate Codes</button>
              </form>
              <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold">Disable 2FA</button>
              </form>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Tab 3: Active Sessions --}}
  <div id="tab-sessions" class="tab-panel hidden space-y-6">
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <h3 class="text-base font-bold">Active Device Sessions</h3>
      <p class="text-xs text-slate-400 mt-1 mb-5">Devices currently signed in to your account.</p>

      @if (config('session.driver') !== 'database')
        <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Set <code>SESSION_DRIVER=database</code> in .env (and run the sessions migration) to list sessions here.</p>
      @else
        <div class="space-y-3 text-xs">
          @foreach ($sessions as $session)
            <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 dark:border-dark-800">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-dark-800 flex items-center justify-center">
                  <svg class="w-4.5 h-4.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                  <p class="font-bold text-slate-800 dark:text-white">{{ $session->agent }} @if($session->current)<span class="text-emerald-500 font-semibold">(this device)</span>@endif</p>
                  <p class="text-[11px] text-slate-400">{{ $session->ip }} &bull; Last active {{ $session->lastActive }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('profile.sessions.logout-others') }}" class="mt-6 flex items-center gap-2 text-xs">
        @csrf
        <input type="password" name="password" placeholder="Confirm password" required
               class="w-56 px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold">Sign out other sessions</button>
      </form>
      @error('password', 'logoutOtherSessions') <span class="text-rose-500 font-semibold text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
  </div>

  {{-- Tab 4: Notifications --}}
  <div id="tab-notifications" class="tab-panel hidden space-y-6">
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <h3 class="text-base font-bold">Email &amp; In-App Alerts</h3>
      <p class="text-xs text-slate-400 mt-1 mb-5">Choose what you want to hear about.</p>

      <form method="POST" action="{{ route('profile.notifications') }}" class="space-y-3 text-xs">
        @csrf
        @php($prefs = $user->notification_prefs ?? ['security_alerts', 'billing_emails', 'ticket_replies'])
        @foreach ([
          'product_updates' => ['Product updates', 'New features and improvements to the platform.'],
          'security_alerts' => ['Security alerts', 'Sign-ins from new devices and password changes.'],
          'billing_emails'  => ['Billing emails', 'Invoices, receipts, and payment issues.'],
          'ticket_replies'  => ['Support ticket replies', 'When an agent responds to your tickets.'],
          'weekly_digest'   => ['Weekly digest', 'A summary of workspace activity every Monday.'],
        ] as $key => [$label, $help])
          <label class="flex items-start justify-between gap-4 p-3.5 rounded-xl border border-slate-100 dark:border-dark-800 cursor-pointer">
            <span>
              <span class="font-bold text-slate-800 dark:text-white block">{{ $label }}</span>
              <span class="text-[11px] text-slate-400">{{ $help }}</span>
            </span>
            <input type="checkbox" name="prefs[]" value="{{ $key }}" @checked(in_array($key, $prefs))
                   class="rounded text-brand-600 focus:ring-brand-500 mt-0.5">
          </label>
        @endforeach
        <div class="flex justify-end pt-2">
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">Save Preferences</button>
        </div>
      </form>
    </div>
  </div>
@endsection
