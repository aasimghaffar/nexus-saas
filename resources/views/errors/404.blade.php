<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found - {{ config('app.name', 'Nexus SaaS') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-dark-950 dark:text-slate-100 font-sans antialiased min-h-screen flex items-center justify-center p-6 text-center">

  <div class="max-w-md w-full space-y-6">
    <img src="{{ asset('assets/images/illustrations/404.svg') }}" alt="404 Illustration" class="w-72 sm:w-80 mx-auto">
    <div class="space-y-2">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Page Not Found</h1>
      <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400">The destination page you are trying to visit might have been removed, had its name changed, or is temporarily unavailable.</p>
    </div>
    <div class="flex items-center justify-center gap-3">
      <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/25 transition-all">
        &larr; Back to Dashboard
      </a>
      <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 text-xs font-bold hover:bg-slate-50 dark:hover:bg-dark-800 shadow-sm transition-colors">
        Contact Support
      </a>
    </div>
  </div>
</body>
</html>
