<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function index()
    {
        // Data for the changelog section - recent updates
        $changelog = [
            [
                'date' => '2025-04-21',
                'changes' => [
                    'Added new changelog and roadmap page',
                    'Improved Strava integration stability',
                    'Fixed calendar display issues on mobile devices',
                ]
            ],
            [
                'date' => '2025-04-15',
                'changes' => [
                    'Added new terms and privacy pages',
                    'Collapse / expand days in the calendar view',
                ]
            ],
            [
                'date' => '2025-04-01',
                'changes' => [
                    'New calendar view for workout planning',
                    'Help page revamped with new content',
                ]
            ],
            [
                'date' => '2025-03-30',
                'changes' => [
                    'Deployement of the website',
                ]
            ],
        ];

        // Data for the roadmap section - upcoming features
        $roadmap = [
            'Manual activity creation',                    
            'Weekly training goals',
            'Integration with Coros, Suunto, and Garmin',
            'French translations',
        ];

        return view('changelog', compact('changelog', 'roadmap'));
    }
}
