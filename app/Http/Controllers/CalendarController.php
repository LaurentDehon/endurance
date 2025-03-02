<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Week;
use App\Models\Activity;
use App\Models\Training;
use App\Models\WeekType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index($year = null)
    {
        $year = $year ?: now()->year;
        $user = Auth::user();

        // Chargement des données
        $activities = Activity::whereYear('start_date', $year)
            ->where('user_id', $user->id)
            ->get();
        
        $trainings = Training::with('trainingType')
            ->whereYear('date', $year)
            ->where('user_id', $user->id)
            ->get();

        // Génération des semaines avec stats
        $weeks = $this->getWeeks($year, $activities, $trainings);

        return view('calendar.index', [
            'year' => $year,
            'months' => $this->groupWeeksByMonth($weeks),
            'monthStats' => $this->calculateMonthStats($weeks),
            'yearStats' => $this->calculateYearStats($weeks),
            'weekTypes' => WeekType::all(),
            'activities' => $activities,
            'trainings' => $trainings,
            'statIcons' => [
                'distance' => 'route',
                'elevation' => 'mountain', 
                'time' => 'stopwatch'
            ],
            'statColors' => [
                'distance' => 'blue',
                'elevation' => 'red',
                'time' => 'green'
            ]
        ]);
    }

    private function getWeeks(int $year, Collection $activities, Collection $trainings): Collection
    {
        $weeks = collect();

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = Carbon::now()->setISODate($year, $weekNumber, 1)->startOfWeek();
            
            if ($start->year > $year) break;

            $end = $start->clone()->endOfWeek();

            $week = Week::firstOrCreate(
                ['year' => $year, 'week_number' => $weekNumber, 'user_id' => Auth::id()],
                ['week_type_id' => null]
            );

            // Calcul des stats
            $weekStats = $this->calculateWeekStats($start, $end, $activities, $trainings);

            $week->start = $start->format('d M');
            $week->end = $end->format('d M');
            $week->month = $start->format('Y-m');
            $week->actual_stats = $weekStats['actual'];
            $week->planned_stats = $weekStats['planned'];
            $week->days = $this->generateWeekDays($start);

            $weeks->push($week);
        }

        return $weeks;
    }

    private function calculateWeekStats(Carbon $start, Carbon $end, Collection $activities, Collection $trainings): array
    {
        // Activités réelles
        $actualActivities = $activities->filter(function ($activity) use ($start, $end) {
            $date = Carbon::parse($activity->start_date);
            return $date->between($start, $end);
        });

        // Entraînements planifiés
        $plannedTrainings = $trainings->filter(function ($training) use ($start, $end) {
            $date = Carbon::parse($training->date);
            return $date->between($start, $end);
        });

        return [
            'actual' => [
                'distance' => round($actualActivities->sum('distance') / 1000, 1),
                'elevation' => $actualActivities->sum('total_elevation_gain'),
                'time' => $actualActivities->sum('moving_time')
            ],
            'planned' => [
                'distance' => $plannedTrainings->sum('distance'),
                'elevation' => $plannedTrainings->sum('elevation'),
                'time' => $plannedTrainings->sum('duration')
            ]
        ];
    }

    private function calculateMonthStats(Collection $weeks): array
    {
        $monthStats = [];

        foreach ($weeks as $week) {
            $monthKey = $week->month;

            if (!isset($monthStats[$monthKey])) {
                $monthStats[$monthKey] = [
                    'actual' => ['distance' => 0, 'elevation' => 0, 'time' => 0],
                    'planned' => ['distance' => 0, 'elevation' => 0, 'time' => 0]
                ];
            }

            foreach (['actual', 'planned'] as $type) {
                foreach (['distance', 'elevation', 'time'] as $metric) {
                    $monthStats[$monthKey][$type][$metric] += $week->{$type . '_stats'}[$metric];
                }
            }
        }

        return $monthStats;
    }

    private function calculateYearStats(Collection $weeks): array
    {
        $yearStats = [
            'actual' => ['distance' => 0, 'elevation' => 0, 'time' => 0],
            'planned' => ['distance' => 0, 'elevation' => 0, 'time' => 0]
        ];

        foreach ($weeks as $week) {
            foreach (['actual', 'planned'] as $type) {
                foreach (['distance', 'elevation', 'time'] as $metric) {
                    $yearStats[$type][$metric] += $week->{$type . '_stats'}[$metric];
                }
            }
        }

        return $yearStats;
    }

    private function groupWeeksByMonth(Collection $weeks): Collection
    {
        return $weeks->groupBy('month');        
    }

    private function generateWeekDays(Carbon $start): array
    {
        return collect(range(0, 6))->map(function ($day) use ($start) {
            $date = $start->clone()->addDays($day);
            
            // $trainings = Training::whereDate('date', $date)
            //     ->where('user_id', Auth::id())
            //     ->get()
            //     ->map(function ($training) {
            //         return [
            //             'id' => $training->id,
            //             'type' => $training->training_type_id,
            //             'distance' => $training->distance,
            //             'duration' => $training->duration,
            //             'type_color' => $training->trainingType->color,
            //             'type_icon' => $training->trainingType->icon,
            //             'type_name' => $training->trainingType->name,
            //         ];
            //     });

            return [
                'name' => $date->isoFormat('ddd'),
                'number' => $date->day,
                'date' => $date,
                'is_today' => $date->isToday(),
                // 'trainings' => $trainings
            ];
        })->toArray();
    }         

    public function updateWeekType(Request $request, int $week_id)
    {
        $week = Week::findOrFail($week_id);
        
        $request->validate([
            'week_type_id' => 'nullable|exists:week_types,id',
        ]);

        $week->week_type_id = $request->week_type_id;
        $week->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Semaine mise à jour',
                'week_type_color' => $week->type->color ?? null
            ]);
        }

        return back()->with('success', 'Week successfully updated');
    }
}