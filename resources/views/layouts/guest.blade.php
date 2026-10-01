<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Welcome') - {{ config('app.name', 'Nexus SaaS') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-dark-950 dark:text-slate-100 font-sans antialiased min-h-screen flex">

  {{-- Left: form column --}}
  <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-12 lg:p-16">
    <div class="flex items-center justify-between">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/images/logo-light.svg') }}" alt="{{ config('app.name') }}" class="h-8 dark:hidden">
        <img src="{{ asset('assets/images/logo-dark.svg') }}" alt="{{ config('app.name') }}" class="h-8 hidden dark:block">
      </a>
      <button data-toggle="theme" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800" title="Switch Theme">
        <svg class="w-5 h-5 theme-moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        <svg class="w-5 h-5 theme-sun-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
      </button>
    </div>

    <div class="max-w-md w-full mx-auto my-auto py-8">
      @yield('form')
    </div>

    <p class="text-xs text-slate-400 text-center">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
  </div>

  {{-- Right: branded showcase --}}
  <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-brand-900 via-indigo-950 to-dark-950 p-16 flex-col justify-between text-white relative overflow-hidden">
    <div class="relative z-10">
      <span class="px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase backdrop-blur-sm border border-white/10">@yield('showcase-badge', 'Enterprise SaaS Platform')</span>
    </div>
    <div class="relative z-10 max-w-lg space-y-4">
      <h2 class="text-4xl font-extrabold tracking-tight leading-tight">@yield('showcase-title', 'Scale your multi-tenant SaaS business with confidence.')</h2>
      <p class="text-sm text-slate-300">@yield('showcase-text', 'Nexus provides complete real-time recurring revenue analytics, automated seat licensing, and developer webhook infrastructure.')</p>
    </div>
    <div class="relative z-10 text-xs text-slate-400 flex items-center gap-6">
      <span>SOC2 Type II Certified</span><span>&bull;</span><span>99.99% SLA Uptime</span>
    </div>
  </div>
</body>
</html>
