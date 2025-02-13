<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Week;
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
        $weeks = $this->getWeeks($year);
        $weekTypes = WeekType::all();    

        return view('calendar.yearly', [
            'year' => $year,
            'months' => $this->groupWeeksByMonth($weeks),
            'monthStats' => $this->calculateMonthStats($weeks),
            'yearStats' => $this->calculateYearStats($year),
            'weekTypes' => $weekTypes
        ]);
    }

    private function getWeeks(int $year): Collection
    {
        $weeks = collect();

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = Carbon::now()->setISODate($year, $weekNumber, 1)->startOfWeek();
            
            if ($start->year > $year) {
                break;
            }

            $end = $start->clone()->endOfWeek();

            $week = Week::firstOrCreate(
                ['year' => $year, 'week_number' => $weekNumber, 'user_id' => Auth::id()],
                ['week_type_id' => null]
            );

            $week->start = $start->format('d M');
            $week->end = $end->format('d M');
            $week->month = $start->format('Y-m');
            $week->training_stats = $this->calculateWeekStats($start, $end);
            $week->days = $this->generateWeekDays($start);

            $weeks->push($week);
        }

        return $weeks;
    }

    private function groupWeeksByMonth(Collection $weeks): Collection
    {
        return $weeks->groupBy('month');        
    }  

    private function calculateWeekStats(Carbon $start, Carbon $end): array
    {
        $trainings = Training::whereBetween('date', [$start, $end])
            ->where('user_id', Auth::id())
            ->get();

        return [
            'distance' => $trainings->sum('distance'),
            'time' => $trainings->sum('duration'),
            'elevation' => $trainings->sum('elevation')
        ];
    }

    private function calculateMonthStats(Collection $weeks): Collection
    {
        $months = $weeks->pluck('month')->unique();

        return $months->mapWithKeys(function ($month) {
            $start = Carbon::parse($month)->startOfMonth();
            $end = Carbon::parse($month)->endOfMonth();

            $trainings = Training::whereBetween('date', [$start, $end])
                ->where('user_id', Auth::id())
                ->get();

            return [$month => [
                'distance' => $trainings->sum('distance'),
                'time' => $trainings->sum('duration'),
                'elevation' => $trainings->sum('elevation')
            ]];
        });
    }

    private function calculateYearStats(int $year): array
    {
        $start = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $end = Carbon::createFromDate($year, 12, 31)->endOfYear();

        $trainings = Training::whereBetween('date', [$start, $end])
            ->where('user_id', Auth::id())
            ->get();

        return [
            'distance' => $trainings->sum('distance'),
            'time' => $trainings->sum('duration'),
            'elevation' => $trainings->sum('elevation')
        ];
    }

    private function generateWeekDays(Carbon $start): array
    {
        return collect(range(0, 6))->map(function ($day) use ($start) {
            $date = $start->clone()->addDays($day);
            
            $trainings = Training::whereDate('date', $date)
                ->where('user_id', Auth::id())
                ->get()
                ->map(function ($training) {
                    return [
                        'id' => $training->id,
                        'type' => $training->training_type_id,
                        'distance' => $training->distance,
                        'duration' => $training->duration,
                        'type_color' => $training->trainingType->color,
                        'type_icon' => $training->trainingType->icon,
                        'type_name' => $training->trainingType->name,
                    ];
                });

            return [
                'name' => $date->isoFormat('ddd'),
                'number' => $date->day,
                'date' => $date,
                'is_today' => $date->isToday(),
                'trainings' => $trainings
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