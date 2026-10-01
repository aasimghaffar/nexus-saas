<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class DocsController extends Controller
{
    public function show()
    {
        $path = base_path('DOCUMENTATION.md');
        abort_unless(file_exists($path), 404);

        return view('documentation', [
            'html' => Str::markdown(file_get_contents($path), [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        ]);
    }
}
