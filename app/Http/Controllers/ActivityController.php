<?php

namespace App\Http\Controllers;

use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('start_date', 'desc')->get();

        return view('activities.index', compact('activities'));
    }

    public function destroyAll()
    {
        Activity::truncate();

        return redirect()->route('activities.index')->with('success', 'All activities successfully deleted');
    }
}
