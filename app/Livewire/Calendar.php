<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Week;
use Livewire\Component;
use App\Models\Activity;
use App\Models\Training;
use App\Models\WeekType;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Services\StravaSyncService;
use Illuminate\Support\Facades\Auth;

class Calendar extends Component
{    
    protected $listeners = [
        'refresh' => '$refresh',
        'confirmDeleteAll',
        'confirmDeleteMonth',
        'confirmDeleteWeek'
    ];

    public $year;
    public $activities;
    public $trainings;
    public $weeks;
    public $months;
    public $monthStats;
    public $yearStats;

    public $statIcons = [
        'distance' => 'route',
        'elevation' => 'mountain', 
        'duration' => 'stopwatch'
    ];    
    public $statColors = [
        'distance' => 'blue',
        'elevation' => 'red',
        'duration' => 'green'
    ];

    public function mount($year = null)
    {
        $this->year = $year ?: now()->year;
    }

    public function render()
    {
        $this->activities = $this->getActivities();
        $this->trainings = $this->getTrainings();

        $this->weeks = $this->getWeeks($this->activities, $this->trainings);
        $this->months = $this->groupWeeksByMonth($this->weeks);
        $this->monthStats = $this->calculateMonthStats($this->weeks);
        $this->yearStats = $this->calculateYearStats($this->weeks);
        
        return view('livewire.calendar', [
            'months' => $this->months,
            'monthStats' => $this->monthStats,
            'yearStats' => $this->yearStats,
            'weekTypes' => WeekType::all(),
            'activities' => $this->activities,
            'trainings' => $this->trainings,
            'year' => $this->year,
            'years' => $this->getAvailableYears()
        ]);
    }

    private function getAvailableYears()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        
        return collect([
            Activity::where('user_id', $user->id)
                ->selectRaw('YEAR(start_date) as year')
                ->distinct()
                ->pluck('year'),
            Training::where('user_id', $user->id)
                ->selectRaw('YEAR(date) as year')
                ->distinct()
                ->pluck('year'),
            range($currentYear - 2, $currentYear + 5)
        ])
        ->flatten()
        ->unique()
        ->sortDesc()
        ->values();
    }

    public function setYear(int $selectedYear): void
    {
        $this->year = $selectedYear;
        $this->dispatch('update-url', year: $selectedYear);
    }

    // Méthode pour naviguer à l'année précédente
    public function previousYear()
    {
        $this->year--;
        $this->dispatch('update-url', year: $this->year);
    }

    // Méthode pour naviguer à l'année suivante
    public function nextYear()
    {
        $this->year++;
        $this->dispatch('update-url', year: $this->year);
    }

    public function getActivities()
    {
        $year = $this->year;
        
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfWeek(Carbon::SUNDAY);

        return Activity::where('user_id', Auth::id())
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date')
            ->get();
    }

    public function getTrainings()
    {
        $year = $this->year;
        
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfWeek(Carbon::SUNDAY);

        return Training::with('type')
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
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

            // Determine the Thursday of the week for correct month assignment
            $thursday = $start->copy()->addDays(3);

            $week = Week::with('type')->firstOrCreate(
                ['year' => $this->year, 'week_number' => $weekNumber, 'user_id' => Auth::id()],
                ['week_type_id' => null]
            );

            // Calcul des stats
            $weekStats = $this->calculateWeekStats($start, $end, $activities, $trainings);

            $week->start = $start->format('d M');
            $week->end = $end->format('d M');
            $week->month = $thursday->format('Y-m'); // Use Thursday's month
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
                'duration' => $actualActivities->sum('moving_time')
            ],
            'planned' => [
                'distance' => $plannedTrainings->sum('distance'),
                'elevation' => $plannedTrainings->sum('elevation'),
                'duration' => $plannedTrainings->sum('duration') * 60
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
                    'actual' => ['distance' => 0, 'elevation' => 0, 'duration' => 0],
                    'planned' => ['distance' => 0, 'elevation' => 0, 'duration' => 0]
                ];
            }

            foreach (['actual', 'planned'] as $type) {
                foreach (['distance', 'elevation', 'duration'] as $metric) {
                    $monthStats[$monthKey][$type][$metric] += $week->{$type . '_stats'}[$metric];
                }
            }
        }

        return $monthStats;
    }

    private function calculateYearStats(Collection $weeks): array
    {
        $yearStats = [
            'actual' => ['distance' => 0, 'elevation' => 0, 'duration' => 0],
            'planned' => ['distance' => 0, 'elevation' => 0, 'duration' => 0]
        ];

        foreach ($weeks as $week) {
            foreach (['actual', 'planned'] as $type) {
                foreach (['distance', 'elevation', 'duration'] as $metric) {
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
        return $weeks->filter(function ($week) {
            return Carbon::createFromFormat('Y-m', $week->month)->year == $this->year;
        })->groupBy('month');    
    }
    
    public function updateWeekType($weekId, $weekTypeId = null)
    {
        $week = Week::findOrFail($weekId);
        $weekTypeId = $weekTypeId === '' ? null : $weekTypeId;
        
        $week->update([
            'week_type_id' => $weekTypeId
        ]);
        
        $this->dispatch('toast', 'Week type updated successfully', 'success');
    }  

    #[On('training-moved')]
    public function updateTrainingDate($trainingId, $newDate)
    {
        $training = Training::with('type')->findOrFail($trainingId);

        $training->update([
            'date' => Carbon::parse($newDate)
        ]);
        
        $this->refreshCalendar();
        $this->dispatch('toast', $training->type->name . ' moved to ' . Carbon::parse($newDate)->format('jS \\of F'), 'success');
    }

    #[On('training-copied')]
    public function copyTraining($trainingId, $newDate)
    {
        $originalTraining = Training::with('type')->findOrFail($trainingId);
        
        $newTraining = $originalTraining->replicate();
        $newTraining->date = Carbon::parse($newDate);
        $newTraining->save();

        $this->refreshCalendar();
        $this->dispatch('toast', $originalTraining->type->name . ' copied to ' . Carbon::parse($newDate)->format('jS \\of F'), 'success');
    }

    #[On('training-created')]
    public function refreshCalendar()
    {
        $this->trainings = $this->getTrainings();
    }
    
    public function startSync(StravaSyncService $syncService)
    {
        try {
            $result = $syncService->sync(Auth::user());
            
            if ($result['success']) {
                if ($result['count'] > 0) {
                    $this->dispatch('toast', $result['message'], 'success');
                } else {
                    $this->dispatch('toast', $result['message'], 'info');
                }
            } else {
                $this->dispatch('toast', $result['message'], 'error');
            }

        } catch (\Exception $e) {
            $this->dispatch('toast', $e->getMessage(), 'error');
        }

        $this->dispatch('refresh');
    }

    public function deleteAll()
    {
        $count = Training::where('user_id', Auth::id())
            ->whereYear('date', $this->year)
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => "Are you sure you want to delete all training sessions for the year {$this->year}?<br>This will remove {$count} training sessions and cannot be undone.",
            'confirmButtonText' => 'Delete All',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteAll',
            'icon' => 'calendar-times',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteAll()
    {
        $trainings = Training::where('user_id', Auth::id())
            ->whereYear('date', $this->year);

        $count = $trainings->count();
        $trainings->delete(); 

        $this->dispatch('toast', $count . ' training sessions deleted successfully', 'success');
    }

    public function deleteMonth($monthKey)
    {
        $month = Carbon::createFromFormat('Y-m', $monthKey);
        $monthName = $month->format('F');
        
        $count = Training::where('user_id', Auth::id())
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm Monthly Deletion',
            'message' => "Are you sure you want to delete all training sessions for {$monthName} {$month->year}?<br>This will remove {$count} training sessions and cannot be undone.",
            'confirmButtonText' => 'Delete Sessions',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteMonth',
            'params' => [$monthKey],
            'icon' => 'calendar-minus',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteMonth($params)
    {
        $monthKey = $params[0];
        $month = Carbon::createFromFormat('Y-m', $monthKey);

        $trainings = Training::where('user_id', Auth::id())
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month);

        $count = $trainings->count();
        $trainings->delete(); 

        $this->dispatch('toast', $count . ' training sessions deleted successfully', 'success');
    }

    public function deleteWeek($weekId)
    {
        $week = Week::findOrFail($weekId);
        
        $date = Carbon::createFromDate($this->year, 1, 1)->startOfYear();
        $start = $date->copy()->setISODate($this->year, $week->week_number, 1)->startOfWeek();
        $end = $start->copy()->endOfWeek();
        
        $count = Training::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end])
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm Weekly Deletion',
            'message' => "Are you sure you want to delete all training sessions for Week {$week->week_number}?<br>This will remove {$count} training sessions and cannot be undone.",
            'confirmButtonText' => 'Delete Sessions',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteWeek',
            'params' => [$weekId],
            'icon' => 'calendar-week',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteWeek(array $params)
    {
        $weekId = $params[0];
        $week = Week::findOrFail($weekId);

        $date = Carbon::createFromDate($this->year, 1, 1)->startOfYear();
        $start = $date->copy()->setISODate($this->year, $week->week_number, 1)->startOfWeek();
        $end = $start->copy()->endOfWeek();

        $trainings = Training::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end]);
            
        $count = $trainings->count();
        $trainings->delete(); 

        $this->dispatch('toast', $count . ' training sessions deleted successfully', 'success');
    }
}