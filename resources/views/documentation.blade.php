@extends('layouts.app')

@section('title', 'Documentation')

@section('content')
  <div class="max-w-3xl mx-auto">
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-8 docs-body">
      {!! $html !!}
    </div>
  </div>

  <style>
    .docs-body h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 1rem; }
    .docs-body h2 { font-size: 1.05rem; font-weight: 800; margin: 1.75rem 0 0.5rem; padding-top: 1rem; border-top: 1px solid rgb(241 245 249 / 1); }
    .dark .docs-body h2 { border-top-color: rgb(30 41 59 / 1); }
    .docs-body h3 { font-size: 0.9rem; font-weight: 700; margin: 1.25rem 0 0.35rem; }
    .docs-body p, .docs-body li { font-size: 0.8rem; line-height: 1.65; color: rgb(100 116 139 / 1); margin-bottom: 0.5rem; }
    .dark .docs-body p, .dark .docs-body li { color: rgb(148 163 184 / 1); }
    .docs-body ul, .docs-body ol { padding-left: 1.25rem; margin-bottom: 0.75rem; }
    .docs-body ul { list-style: disc; }
    .docs-body ol { list-style: decimal; }
    .docs-body code { font-size: 0.72rem; background: rgb(241 245 249 / 1); padding: 0.1rem 0.35rem; border-radius: 0.35rem; }
    .dark .docs-body code { background: rgb(30 41 59 / 1); }
    .docs-body pre { background: rgb(15 23 42 / 1); color: rgb(226 232 240 / 1); font-size: 0.72rem; padding: 1rem; border-radius: 0.75rem; overflow-x: auto; margin: 0.75rem 0 1rem; }
    .docs-body pre code { background: transparent; padding: 0; color: inherit; }
    .docs-body a { color: rgb(79 70 229 / 1); font-weight: 600; }
    .docs-body hr { border-color: rgb(241 245 249 / 1); margin: 1.5rem 0; }
    .dark .docs-body hr { border-color: rgb(30 41 59 / 1); }
    .docs-body strong { color: rgb(15 23 42 / 1); }
    .dark .docs-body strong { color: rgb(241 245 249 / 1); }
  </style>
@endsection
