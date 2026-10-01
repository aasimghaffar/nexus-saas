<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scheduled Maintenance - {{ config('app.name', 'Nexus SaaS') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-dark-950 dark:text-slate-100 font-sans antialiased min-h-screen flex items-center justify-center p-6 text-center">

  <div class="max-w-lg w-full space-y-8">
    <img src="{{ asset('assets/images/illustrations/maintenance.svg') }}" alt="Maintenance" class="w-64 sm:w-72 mx-auto">

    <div class="space-y-2">
      <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">Scheduled Maintenance</span>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Upgrading Database Clusters</h1>
      <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400">We are performing scheduled cloud infrastructure upgrades to boost query performance. We will be back online shortly.</p>
    </div>

    <!-- Countdown Timers -->
    <div class="grid grid-cols-4 gap-3 max-w-sm mx-auto">
      <div class="p-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-800 shadow-sm">
        <span class="text-xl sm:text-2xl font-extrabold text-brand-600 dark:text-brand-400">00</span>
        <p class="text-[10px] uppercase font-bold text-slate-400 mt-1">Days</p>
      </div>
      <div class="p-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-800 shadow-sm">
        <span class="text-xl sm:text-2xl font-extrabold text-brand-600 dark:text-brand-400">02</span>
        <p class="text-[10px] uppercase font-bold text-slate-400 mt-1">Hours</p>
      </div>
      <div class="p-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-800 shadow-sm">
        <span class="text-xl sm:text-2xl font-extrabold text-brand-600 dark:text-brand-400">45</span>
        <p class="text-[10px] uppercase font-bold text-slate-400 mt-1">Mins</p>
      </div>
      <div class="p-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-800 shadow-sm">
        <span class="text-xl sm:text-2xl font-extrabold text-brand-600 dark:text-brand-400">18</span>
        <p class="text-[10px] uppercase font-bold text-slate-400 mt-1">Secs</p>
      </div>
    </div>

    <!-- Notify When Back Form -->
    <form onsubmit="event.preventDefault(); NexusApp.showToast({title:'Subscribed', message:'We will email you when maintenance concludes.', type:'success'});" class="flex gap-2 max-w-md mx-auto">
      <input type="email" placeholder="Enter work email to get notified..." required class="flex-1 px-4 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
      <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/25">Notify Me</button>
    </form>
  </div>
</body>
</html>
