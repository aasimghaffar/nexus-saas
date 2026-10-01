<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', $title ?? 'Dashboard') - {{ config('app.name', 'Nexus SaaS') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-dark-950 dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col">

  @include('partials.sidebar')

  <div id="main-content" class="flex-1 flex flex-col lg:pl-64 transition-all duration-300">

    @include('partials.header')

    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
      @if (session('status'))
        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 text-xs font-semibold px-4 py-3">
          {{ session('status') }}
        </div>
      @endif

      @yield('content')
      {{ $slot ?? '' }}
    </main>

    @include('partials.footer')
  @include('partials.search-modal')
  </div>

  @livewireScripts
  @stack('scripts')
</body>
</html>
