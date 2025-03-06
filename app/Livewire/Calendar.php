<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Week;
use Livewire\Component;
use App\Models\Activity;
use App\Models\Training;
use App\Models\WeekType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Calendar extends Component
{
    public $year;
    public $statIcons = [
        'distance' => 'route',
        'elevation' => 'mountain', 
        'time' => 'stopwatch'
    ];
    
    public $statColors = [
        'distance' => 'blue',
        'elevation' => 'red',
        'time' => 'green'
    ];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount($year = null)
    {
        $this->year = $year ?: now()->year;
    }

    public function render()
    {
        $activities = Activity::whereYear('start_date', $this->year)
            ->where('user_id', Auth::id())
            ->get();
        
        $trainings = Training::with('trainingType')
            ->whereYear('date', $this->year)
            ->where('user_id', Auth::id())
            ->get();

        $weeks = $this->getWeeks($activities, $trainings);
        
        return view('livewire.calendar', [
            'months' => $this->groupWeeksByMonth($weeks),
            'monthStats' => $this->calculateMonthStats($weeks),
            'yearStats' => $this->calculateYearStats($weeks),
            'weekTypes' => WeekType::all(),
            'activities' => $activities,
            'trainings' => $trainings,
        ]);
    }

    public function nextYear()
    {
        $this->year++;
        $this->dispatch('refresh');
    }

    public function previousYear()
    {
        $this->year--;
        $this->dispatch('refresh');
    }

    private function getWeeks(Collection $activities, Collection $trainings): Collection
    {
        $weeks = collect();
        $date = Carbon::createFromDate($this->year, 1, 1)->startOfYear();

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = $date->copy()->setISODate($this->year, $weekNumber, 1)->startOfWeek();
            
            if ($start->year > $this->year) 
                break;

            $end = $start->copy()->endOfWeek();

            $week = Week::firstOrCreate(
                ['year' => $this->year, 'week_number' => $weekNumber, 'user_id' => Auth::id()],
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

    private function generateWeekDays(Carbon $start): array
    {
        return collect(range(0, 6))->map(function ($day) use ($start) {
            $date = $start->clone()->addDays($day);

            return [
                'name' => $date->isoFormat('ddd'),
                'number' => $date->day,
                'date' => $date,
                'is_today' => $date->isToday()
            ];
        })->toArray();
    }  

    private function groupWeeksByMonth(Collection $weeks): Collection
    {
        return $weeks->groupBy('month');        
    }
    
    public function updateWeekType($weekId, $weekTypeId = null)
    {
        $week = Week::findOrFail($weekId);
        
        $week->update([
            'week_type_id' => $weekTypeId ?: null
        ]);
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Week type updated successfully'
        ]);
    }    
}