@extends('layouts.app')

@section('title', 'Forms & Input Controls')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Form Inputs & Controls</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Full collection of text inputs, custom selects, toggles, checkboxes, and drag-and-drop file uploaders.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Text Inputs -->
        <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4 text-xs">
          <h3 class="text-sm font-bold">Standard Text & Select Inputs</h3>
          <div>
            <label class="block font-bold mb-1">Standard Text Field</label>
            <input type="text" placeholder="Enter full name" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div>
            <label class="block font-bold mb-1">Select Menu</label>
            <select class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
              <option>Engineering Department</option>
              <option>Product & Design</option>
              <option>Sales & Growth</option>
            </select>
          </div>
          <div>
            <label class="block font-bold mb-1">Textarea</label>
            <textarea rows="3" placeholder="Add detailed notes..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
          </div>
        </div>

        <!-- Toggles & Checkboxes -->
        <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-5 text-xs">
          <h3 class="text-sm font-bold">Toggles & Checkbox Controls</h3>
          <!-- Toggle 1 -->
          <div class="flex items-center justify-between">
            <div>
              <p class="font-bold">Real-time Push Notifications</p>
              <p class="text-slate-400 text-[11px]">Receive in-app sound and browser alerts</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" checked class="sr-only peer">
              <div class="w-11 h-6 bg-slate-200 dark:bg-dark-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
            </label>
          </div>

          <!-- Checkboxes -->
          <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-dark-800">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" checked class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
              <span class="font-medium">Enable automatic weekly digest report</span>
            </label>
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
              <span class="font-medium">Send SMS alerts for critical billing events</span>
            </label>
          </div>

          <!-- File Upload Dropzone -->
          <div class="p-4 rounded-xl border-2 border-dashed border-slate-200 dark:border-dark-700 text-center cursor-pointer hover:border-brand-500 transition-colors">
            <svg class="w-8 h-8 mx-auto text-slate-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            <p class="font-bold text-slate-700 dark:text-dark-200">Click to upload or drag files here</p>
            <p class="text-[10px] text-slate-400 mt-0.5">PNG, JPG, or SVG up to 10MB</p>
          </div>
        </div>
      </div>
    
@endsection
