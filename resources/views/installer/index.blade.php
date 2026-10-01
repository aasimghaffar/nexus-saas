<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Install - Nexus SaaS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen py-10 px-4">
  <div class="max-w-2xl mx-auto space-y-6">
    <div class="text-center">
      <img src="{{ asset('assets/images/logo-light.svg') }}" class="h-9 mx-auto" alt="Nexus">
      <h1 class="text-2xl font-extrabold tracking-tight mt-4">Welcome to the Nexus Installer</h1>
      <p class="text-xs text-slate-500 mt-1">One page, three sections — you'll be signed in within two minutes.</p>
    </div>

    @if (count($errors))
    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 space-y-1">
      @foreach ($errors as $error)
        <p class="text-xs font-semibold text-rose-700">{{ $error }}</p>
      @endforeach
    </div>
    @endif

    {{-- 1. Requirements --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-6">
      <h2 class="text-sm font-extrabold">1 · Server Requirements</h2>
      <div class="mt-4 space-y-1.5">
        @php($allOk = true)
        @foreach ($requirements as [$label, $ok, $detail])
          @php($allOk = $allOk && $ok)
          <div class="flex items-center justify-between text-xs py-1.5 px-3 rounded-xl {{ $ok ? 'bg-emerald-50/60' : 'bg-rose-50' }}">
            <span class="font-semibold">{{ $label }}</span>
            <span class="font-bold {{ $ok ? 'text-emerald-600' : 'text-rose-600' }}">{{ $detail }}</span>
          </div>
        @endforeach
      </div>
      @unless ($allOk)
        <p class="text-xs font-bold text-rose-600 mt-3">Fix the items above, then reload this page before installing.</p>
      @endunless
    </div>

    <form method="POST" action="{{ url('/install') }}" class="space-y-6">
      {{-- 2. Application & database --}}
      <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-6 space-y-4 text-xs">
        <h2 class="text-sm font-extrabold">2 · Application &amp; Database</h2>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-bold mb-1">Application Name</label>
            <input name="app_name" value="{{ $old['app_name'] ?? 'Nexus SaaS' }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div>
            <label class="block font-bold mb-1">Application URL</label>
            <input name="app_url" value="{{ $old['app_url'] ?? url('/') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
          </div>
        </div>
        <div>
          <label class="block font-bold mb-1">Database Driver</label>
          <select name="db_connection" id="db_connection" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none">
            <option value="mysql" @selected(($old['db_connection'] ?? 'mysql') === 'mysql')>MySQL / MariaDB (recommended)</option>
            <option value="sqlite" @selected(($old['db_connection'] ?? '') === 'sqlite')>SQLite (small installs / testing)</option>
          </select>
        </div>
        <div id="mysql-fields" class="grid grid-cols-2 gap-3">
          <div><label class="block font-bold mb-1">DB Host</label><input name="db_host" value="{{ $old['db_host'] ?? '127.0.0.1' }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
          <div><label class="block font-bold mb-1">DB Port</label><input name="db_port" value="{{ $old['db_port'] ?? '3306' }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
          <div><label class="block font-bold mb-1">Database Name</label><input name="db_database" value="{{ $old['db_database'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
          <div><label class="block font-bold mb-1">DB Username</label><input name="db_username" value="{{ $old['db_username'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
          <div class="col-span-2"><label class="block font-bold mb-1">DB Password</label><input type="password" name="db_password" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
        </div>
      </div>

      {{-- 3. Admin account --}}
      <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-6 space-y-4 text-xs">
        <h2 class="text-sm font-extrabold">3 · Super Admin Account</h2>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="block font-bold mb-1">Full Name</label><input name="admin_name" value="{{ $old['admin_name'] ?? '' }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
          <div><label class="block font-bold mb-1">Email</label><input type="email" name="admin_email" value="{{ $old['admin_email'] ?? '' }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
          <div class="col-span-2"><label class="block font-bold mb-1">Password (min 8 chars)</label><input type="password" name="admin_password" required minlength="8" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none"></div>
        </div>
        <label class="flex items-center gap-2 cursor-pointer font-semibold">
          <input type="checkbox" name="demo_data" value="1" @checked($old['demo_data'] ?? false) class="rounded text-brand-600"> Install demo content (sample team, projects, board tasks, tickets)
        </label>
      </div>

      <button type="submit" @disabled(! $allOk) class="w-full py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-lg shadow-brand-500/25 disabled:opacity-40">
        Install Nexus SaaS
      </button>
      <p class="text-center text-[11px] text-slate-400">Installation runs migrations and writes your .env — it may take up to a minute on shared hosting.</p>
    </form>
  </div>

  <script>
    const sel = document.getElementById('db_connection');
    const mysql = document.getElementById('mysql-fields');
    const sync = () => mysql.style.display = sel.value === 'mysql' ? '' : 'none';
    sel.addEventListener('change', sync); sync();
  </script>
</body>
</html>
