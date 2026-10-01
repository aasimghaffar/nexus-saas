<?php

namespace App\Http\Controllers;

class UiKitController extends Controller
{
    private const PAGES = ['components', 'alerts', 'badges', 'buttons', 'cards', 'forms', 'modals', 'tables', 'tabs', 'typography'];

    public function show(string $page)
    {
        abort_unless(in_array($page, self::PAGES, true), 404);

        return view("ui.{$page}");
    }
}
