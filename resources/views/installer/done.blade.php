<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Installed - Nexus SaaS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 p-10 text-center space-y-5">
    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    <div>
      <h1 class="text-2xl font-extrabold">Installation Complete</h1>
      <p class="text-xs text-slate-500 mt-2">Nexus SaaS is ready. Sign in as <strong>{{ $email }}</strong> to open your dashboard.</p>
    </div>
    <a href="{{ url('/login') }}" class="block w-full py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-lg shadow-brand-500/25">Go to Sign In</a>
    <p class="text-[11px] text-slate-400">If you're running <code>php artisan serve</code>, it restarts itself once now (environment changed) — that's normal. The installer is locked; to re-run it, delete storage/installed.json.</p>
  </div>
</body>
</html>
