<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function index()
    {
        // Get changelog and roadmap data from translation files
        $changelog = __('changelog.updates');
        $roadmap = __('changelog.roadmap');

        return view('footer.changelog', compact('changelog', 'roadmap'));
    }
}
