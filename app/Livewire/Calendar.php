<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Week;
use App\Models\Workout;
use Livewire\Component;
use App\Models\Activity;
use App\Models\WeekType;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Services\StravaSyncService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Calendar extends Component
{    
    /**
     * Event listeners for the component.
     * 
     * @var array
     */
    protected $listeners = [
        'refresh' => '$refresh',
        'refresh-calendar' => 'refreshCalendar',
        'confirmDeleteAll',
        'confirmDeleteMonth',
        'confirmDeleteWeek'
    ];

    /**
     * The current year being displayed.
     * 
     * @var int
     */
    public $year;
    
    /**
     * Collection of user activities for the displayed year.
     * 
     * @var \Illuminate\Support\Collection
     */
    public $activities;
    
    /**
     * Collection of user workouts for the displayed year.
     * 
     * @var \Illuminate\Support\Collection
     */
    public $workouts;
    
    /**
     * Collection of weeks in the displayed year.
     * 
     * @var \Illuminate\Support\Collection
     */
    public $weeks;
    
    /**
     * Collection of months with associated weeks.
     * 
     * @var \Illuminate\Support\Collection
     */
    public $months;
    
    /**
     * Monthly statistics for the displayed year.
     * 
     * @var array
     */
    public $monthStats;
    
    /**
     * Yearly statistics aggregation.
     * 
     * @var array
     */
    public $yearStats;

    /**
     * Icons used for different statistics.
     * 
     * @var array
     */
    public $statIcons = [
        'distance' => 'route',
        'elevation' => 'mountain', 
        'duration' => 'stopwatch'
    ];    
    
    /**
     * Colors used for different statistics.
     * 
     * @var array
     */
    public $statColors = [
        'distance' => 'blue',
        'elevation' => 'red',
        'duration' => 'green'
    ];

    /**
     * Initialize the component with the given year.
     *
     * @param int|null $year The year to display, defaults to current year
     * @return void
     */
    public function mount($year = null)
    {
        $this->year = $year ?: now()->year;
    }    

    /**
     * Render the calendar component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $userId = Auth::id();
        $cacheKey = "calendar-{$userId}-{$this->year}";
        $data = Cache::remember($cacheKey, 60, function () {
            $activities = $this->getActivities();
            $workouts = $this->getWorkouts();
            $weeks = $this->getWeeks($activities, $workouts);
            $months = $this->groupWeeksByMonth($weeks);
            $monthStats = $this->calculateMonthStatsFromDb();
            $yearStats = $this->calculateYearStatsFromDb();
            return compact('activities', 'workouts', 'weeks', 'months', 'monthStats', 'yearStats');
        });
        $this->activities = $data['activities'];
        $this->workouts = $data['workouts'];
        $this->weeks = $data['weeks'];
        $this->months = $data['months'];
        $this->monthStats = $data['monthStats'];
        $this->yearStats = $data['yearStats'];
        return view('livewire.calendar', [
            'months' => $this->months,
            'monthStats' => $this->monthStats,
            'yearStats' => $this->yearStats,
            'weekTypes' => \App\Models\WeekType::all(),
            'activities' => $this->activities,
            'workouts' => $this->workouts,
            'year' => $this->year,
            'years' => $this->getAvailableYears()
        ]);
    }

    /**
     * Get a list of available years for the calendar view based on user data and date range.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAvailableYears()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        
        return collect([
            Activity::where('user_id', $user->id)
                ->selectRaw('YEAR(start_date) as year')
                ->distinct()
                ->pluck('year'),
            Workout::where('user_id', $user->id)
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

    /**
     * Set the current year for the calendar view and update URL.
     *
     * @param int $selectedYear The year to display
     * @return void
     */
    public function setYear(int $selectedYear): void
    {
        $this->year = $selectedYear;
        $this->dispatch('update-url', year: $selectedYear);
    }

    /**
     * Navigate to the previous year.
     *
     * @return void
     */
    public function previousYear()
    {
        $this->year--;
        $this->dispatch('update-url', year: $this->year);
    }

    /**
     * Navigate to the next year.
     *
     * @return void
     */
    public function nextYear()
    {
        $this->year++;
        $this->dispatch('update-url', year: $this->year);
    }

    /**
     * Get activities for the current year.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Get workouts for the current year.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorkouts()
    {
        $year = $this->year;
        
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfWeek(Carbon::SUNDAY);

        return Workout::with('type')
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    /**
     * Generate all weeks for the selected year and calculate their statistics.
     *
     * @return Collection Collection of week objects with calculated statistics
     */
    private function getWeeks(): Collection
    {
        // Récupérer les stats groupées par semaine pour les activités réelles
        $activityStats = Activity::selectRaw('WEEK(start_date, 1) as week, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->whereYear('start_date', $this->year)
            ->where('user_id', Auth::id())
            ->groupBy('week')
            ->get()
            ->keyBy('week');

        // Récupérer les stats groupées par semaine pour les workouts planifiés
        $workoutStats = Workout::selectRaw('WEEK(date, 1) as week, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->whereYear('date', $this->year)
            ->where('user_id', Auth::id())
            ->groupBy('week')
            ->get()
            ->keyBy('week');

        // Pré-charger tous les Week de l'année avec leur type
        $weeksFromDb = Week::with('type')
            ->where('user_id', Auth::id())
            ->where('year', $this->year)
            ->get()
            ->keyBy('week_number');

        $weeks = collect();
        $date = \Carbon\CarbonImmutable::create($this->year, 1, 1)->startOfYear();

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = $date->addWeeks($weekNumber - 1)->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
            if ($start->year > $this->year) 
                break;
            $end = $start->endOfWeek(\Carbon\CarbonImmutable::SUNDAY);
            $thursday = $start->addDays(3);

            // Récupérer le Week de la collection, sinon en créer un nouveau en mémoire
            $week = $weeksFromDb->get($weekNumber);
            if (!$week) {
                $week = new Week([
                    'year' => $this->year,
                    'week_number' => $weekNumber,
                    'user_id' => Auth::id(),
                    'week_type_id' => null
                ]);
                $week->setRelation('type', null);
            }

            // Utiliser les stats groupées pour cette semaine
            $actual = [
                'distance' => isset($activityStats[$weekNumber]) ? round($activityStats[$weekNumber]->dist / 1000, 1) : 0,
                'elevation' => isset($activityStats[$weekNumber]) ? $activityStats[$weekNumber]->ele : 0,
                'duration' => isset($activityStats[$weekNumber]) ? $activityStats[$weekNumber]->time : 0,
            ];
            $planned = [
                'distance' => isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->dist : 0,
                'elevation' => isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->ele : 0,
                'duration' => isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->time * 60 : 0,
            ];

            $week->start = $start->format('d M');
            $week->end = $end->format('d M');
            $week->month = $thursday->format('Y-m');
            $week->actual_stats = $actual;
            $week->planned_stats = $planned;
            $week->days = $this->generateWeekDays($start);
            $week->is_current_week = $this->isCurrentWeek($start, $end);

            $weeks->push($week);
        }

        return $weeks;
    }

    /**
     * Récupère les statistiques groupées par année pour Activity et Workout
     */
    private function getYearlyStats(): array
    {
        $activityStats = Activity::selectRaw('YEAR(start_date) as year, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->where('user_id', Auth::id())
            ->groupBy('year')
            ->get()
            ->keyBy('year');

        $workoutStats = Workout::selectRaw('YEAR(date) as year, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->where('user_id', Auth::id())
            ->groupBy('year')
            ->get()
            ->keyBy('year');

        return [
            'activity' => $activityStats,
            'workout' => $workoutStats
        ];
    }

    /**
     * Récupère les statistiques groupées par mois pour Activity et Workout
     */
    private function getMonthlyStats(): array
    {
        $activityStats = Activity::selectRaw('YEAR(start_date) as year, MONTH(start_date) as month, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->whereYear('start_date', $this->year)
            ->where('user_id', Auth::id())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function($item) { return sprintf('%04d-%02d', $item->year, $item->month); });

        $workoutStats = Workout::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->whereYear('date', $this->year)
            ->where('user_id', Auth::id())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function($item) { return sprintf('%04d-%02d', $item->year, $item->month); });

        return [
            'activity' => $activityStats,
            'workout' => $workoutStats
        ];
    }

    /**
     * Calculate yearly statistics from SQL group by year.
     *
     * @return array Array of yearly statistics for actual and planned values
     */
    private function calculateYearStatsFromDb(): array
    {
        $yearly = $this->getYearlyStats();
        $activity = $yearly['activity'][$this->year] ?? null;
        $workout = $yearly['workout'][$this->year] ?? null;
        return [
            'actual' => [
                'distance' => $activity ? round($activity->dist / 1000, 1) : 0,
                'elevation' => $activity ? $activity->ele : 0,
                'duration' => $activity ? $activity->time : 0,
            ],
            'planned' => [
                'distance' => $workout ? $workout->dist : 0,
                'elevation' => $workout ? $workout->ele : 0,
                'duration' => $workout ? $workout->time * 60 : 0,
            ]
        ];
    }

    /**
     * Calculate monthly statistics from SQL group by month.
     *
     * @return array Array of monthly statistics organized by month key
     */
    private function calculateMonthStatsFromDb(): array
    {
        $monthly = $this->getMonthlyStats();
        $monthStats = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthKey = sprintf('%04d-%02d', $this->year, $m);
            $activity = $monthly['activity'][$monthKey] ?? null;
            $workout = $monthly['workout'][$monthKey] ?? null;
            $monthStats[$monthKey] = [
                'actual' => [
                    'distance' => $activity ? round($activity->dist / 1000, 1) : 0,
                    'elevation' => $activity ? $activity->ele : 0,
                    'duration' => $activity ? $activity->time : 0,
                ],
                'planned' => [
                    'distance' => $workout ? $workout->dist : 0,
                    'elevation' => $workout ? $workout->ele : 0,
                    'duration' => $workout ? $workout->time * 60 : 0,
                ]
            ];
        }
        return $monthStats;
    }    
    
    private function invalidateCache()
    {
        $userId = Auth::id();
        $cacheKey = "calendar-{$userId}-{$this->year}";
        Cache::forget($cacheKey);
    }

    /**
     * Updates the type of a week.
     *
     * @param int $weekId The week identifier
     * @param int|null $weekTypeId The week type identifier
     * @return void
     */
    public function setWeekType($weekId, $weekTypeId)
    {
        $week = Week::findOrFail($weekId);
        if ($week->user_id !== Auth::id()) {
            return;
        }
        $week->week_type_id = $weekTypeId;
        $week->save();
        $this->invalidateCache();
        $this->dispatch('refresh');
    }

    /**
     * Génère un tableau d'informations pour chaque jour de la semaine.
     *
     * @param Carbon|\Carbon\CarbonImmutable $start Premier jour de la semaine
     * @return array
     */
    private function generateWeekDays($start): array
    {
        // S'assurer d'avoir un objet mutable pour les opérations
        if ($start instanceof \Carbon\CarbonImmutable) {
            $start = $start->toMutable();
        }
        return collect(range(0, 6))->map(function ($day) use ($start) {
            $date = $start->copy()->addDays($day);
            return [
                'name' => $date->isoFormat('ddd'),
                'number' => $date->day,
                'date' => $date,
                'is_today' => $date->isToday()
            ];
        })->toArray();
    }  

    /**
     * Vérifie si une semaine contient le jour courant.
     *
     * @param Carbon|\Carbon\CarbonImmutable $start Premier jour de la semaine
     * @param Carbon|\Carbon\CarbonImmutable $end Dernier jour de la semaine
     * @return bool
     */
    private function isCurrentWeek($start, $end): bool
    {
        if ($start instanceof \Carbon\CarbonImmutable) {
            $start = $start->toMutable();
        }
        if ($end instanceof \Carbon\CarbonImmutable) {
            $end = $end->toMutable();
        }
        $today = Carbon::today();
        return $today->greaterThanOrEqualTo($start) && $today->lessThanOrEqualTo($end);
    }

    /**
     * Groups weeks by month for the selected year.
     *
     * @param Collection $weeks Collection of weeks to group
     * @return Collection Collection of weeks grouped by month
     */
    private function groupWeeksByMonth(Collection $weeks): Collection
    {
        return $weeks->filter(function ($week) {
            return Carbon::createFromFormat('Y-m', $week->month)->year == $this->year;
        })->groupBy('month');    
    }
    
    /**
     * Updates a week's type and sends a success notification.
     *
     * @param int $weekId The week identifier
     * @param int|null $weekTypeId The week type identifier (null to clear)
     * @return void
     */
    public function updateWeekType($weekId, $weekTypeId = null)
    {
        $week = Week::findOrFail($weekId);
        $weekTypeId = $weekTypeId === '' ? null : $weekTypeId;
        
        $week->update([
            'week_type_id' => $weekTypeId
        ]);
        
        $this->dispatch('toast', 'Week type updated successfully', 'success');
    }  

    /**
     * Updates the date of a workout when it's moved in the calendar.
     *
     * @param int $workoutId The workout identifier
     * @param string $newDate The new date for the workout
     * @return void
     */
    #[On('workout-moved')]
    public function updateWorkoutDate($workoutId, $newDate)
    {
        $workout = Workout::with('type')->findOrFail($workoutId);

        $workout->update([
            'date' => Carbon::parse($newDate)
        ]);
        
        $this->refreshCalendar();
        $this->dispatch('toast', $workout->type->name . ' moved to ' . Carbon::parse($newDate)->format('jS \\of F'), 'success');
    }

    /**
     * Creates a copy of a workout on a new date.
     *
     * @param int $workoutId The workout identifier to copy
     * @param string $newDate The date for the new copy
     * @return void
     */
    #[On('workout-copied')]
    public function copyWorkout($workoutId, $newDate)
    {
        $originalWorkout = Workout::with('type')->findOrFail($workoutId);
        
        $newWorkout = $originalWorkout->replicate();
        $newWorkout->date = Carbon::parse($newDate);
        $newWorkout->save();

        $this->refreshCalendar();
        $this->dispatch('toast', $originalWorkout->type->name . ' copied to ' . Carbon::parse($newDate)->format('jS \\of F'), 'success');
    }

    /**
     * Refreshes the workouts after creating a new one.
     *
     * @return void
     */
    #[On('workout-created')]
    public function refreshCalendar()
    {
        $this->invalidateCache();
        $this->workouts = $this->getWorkouts();
        $this->dispatch('reload-tooltips');
    }
    
    /**
     * Synchronizes activities from Strava.
     *
     * @param StravaSyncService $syncService The Strava synchronization service
     * @return void
     */
    public function startSync(StravaSyncService $syncService)
    {
        try {
            $user = Auth::user();
            if (!$user || !($user instanceof \App\Models\User)) {
                $this->dispatch('toast', 'User authentication required', 'error');
                return;
            }
            
            $result = $syncService->sync($user);
            
            if ($result['success']) {
                if ($result['count'] > 0) {
                    $this->dispatch('toast', $result['message'], 'success');
                    $this->refreshCalendar();
                    $this->activities = $this->getActivities();                    
                    $this->dispatch('reload-tooltips');
                } else {
                    $this->dispatch('toast', $result['message'], 'info');
                }
            } else {
                $this->dispatch('toast', $result['message'], 'error');
            }

        } catch (\Exception $e) {
            $this->dispatch('toast', $e->getMessage(), 'error');
        }
    }

    /**
     * Shows a confirmation dialog for deleting all workouts in the current year.
     *
     * @return void
     */
    public function deleteAll()
    {
        $count = Workout::where('user_id', Auth::id())
            ->whereYear('date', $this->year)
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => "Are you sure you want to delete all workout sessions for the year {$this->year}?<br>This will remove {$count} workout sessions and cannot be undone.",
            'confirmButtonText' => 'Delete All',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteAll',
            'icon' => 'calendar-times',
            'iconColor' => 'red'
        ]);
    }

    /**
     * Deletes all workouts for the current year after confirmation.
     *
     * @return void
     */
    public function confirmDeleteAll()
    {
        $workouts = Workout::where('user_id', Auth::id())
            ->whereYear('date', $this->year);

        $count = $workouts->count();
        $workouts->delete(); 
        $this->invalidateCache();
        $this->dispatch('toast', $count . ' workout sessions deleted successfully', 'success');
    }

    /**
     * Shows a confirmation dialog for deleting workouts in a specific month.
     *
     * @param string $monthKey The month identifier in YYYY-MM format
     * @return void
     */
    public function deleteMonth($monthKey)
    {
        $month = Carbon::createFromFormat('Y-m', $monthKey);
        $monthName = $month->format('F');
        
        $count = Workout::where('user_id', Auth::id())
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm Monthly Deletion',
            'message' => "Are you sure you want to delete all workout sessions for {$monthName} {$month->year}?<br>This will remove {$count} workout sessions and cannot be undone.",
            'confirmButtonText' => 'Delete Sessions',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteMonth',
            'params' => [$monthKey],
            'icon' => 'calendar-minus',
            'iconColor' => 'red'
        ]);
    }

    /**
     * Deletes all workouts for a specific month after confirmation.
     *
     * @param array $params Parameters passed from the confirmation dialog
     * @return void
     */
    public function confirmDeleteMonth($params)
    {
        $monthKey = $params[0];
        $month = Carbon::createFromFormat('Y-m', $monthKey);

        $workouts = Workout::where('user_id', Auth::id())
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month);

        $count = $workouts->count();
        $workouts->delete(); 
        $this->invalidateCache();
        $this->dispatch('toast', $count . ' workout sessions deleted successfully', 'success');
    }

    /**
     * Shows a confirmation dialog for deleting workouts in a specific week.
     *
     * @param int $weekId The week identifier
     * @return void
     */
    public function deleteWeek($weekId)
    {
        $week = Week::findOrFail($weekId);
        
        $date = Carbon::createFromDate($this->year, 1, 1)->startOfYear();
        $start = $date->copy()->setISODate($this->year, $week->week_number, 1)->startOfWeek();
        $end = $start->copy()->endOfWeek();
        
        $count = Workout::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end])
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm Weekly Deletion',
            'message' => "Are you sure you want to delete all workout sessions for Week {$week->week_number}?<br>This will remove {$count} workout sessions and cannot be undone.",
            'confirmButtonText' => 'Delete Sessions',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteWeek',
            'params' => [$weekId],
            'icon' => 'calendar-week',
            'iconColor' => 'red'
        ]);
    }

    /**
     * Deletes all workouts for a specific week after confirmation.
     *
     * @param array $params Parameters passed from the confirmation dialog
     * @return void
     */
    public function confirmDeleteWeek(array $params)
    {
        $weekId = $params[0];
        $week = Week::findOrFail($weekId);

        $date = Carbon::createFromDate($this->year, 1, 1)->startOfYear();
        $start = $date->copy()->setISODate($this->year, $week->week_number, 1)->startOfWeek();
        $end = $start->copy()->endOfWeek();

        $workouts = Workout::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end]);
            
        $count = $workouts->count();
        $workouts->delete(); 
        $this->invalidateCache();
        $this->dispatch('toast', $count . ' workout sessions deleted successfully', 'success');
    }
    
    /**
     * Formats time in seconds to a readable format (h:mm:ss or mm:ss).
     *
     * @param int $seconds Time in seconds
     * @return string Formatted time string
     */
    public function formatTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return sprintf('%d:%02d', $minutes, $seconds);
        }
    }

    /**
     * Formats a distance in kilometers with specified precision.
     *
     * @param float $distance Distance in kilometers
     * @param int $precision Number of decimal places
     * @return string Formatted distance string with km unit
     */
    public function formatDistance(float $distance, int $precision = 1): string
    {
        return number_format($distance, $precision) . ' km';
    }

    /**
     * Calculates progression statistics between two development weeks.
     *
     * @param \App\Models\Week $currentWeek Current week
     * @param Collection $weeksInMonth All weeks in the month
     * @param int $currentIndex Index of the current week
     * @return array Result with distance and duration progression data
     */
    public function calculateDevelopmentWeekProgress(Week $currentWeek): array
    {
        $result = [
            'distance' => null,
            'duration' => null,
            'isValid' => false
        ];
        try {
            if (!$currentWeek->type || strtolower($currentWeek->type->name) !== 'development') {
                return $result;
            }
            $year = $currentWeek->year;
            $weekNumber = $currentWeek->week_number;
            $prevWeekNumber = $weekNumber - 1;
            if ($prevWeekNumber < 1) return $result;

            // Stats groupées pour les semaines de l'année
            $workoutStats = Workout::selectRaw('WEEK(date, 1) as week, SUM(distance) as dist, SUM(duration) as time')
                ->whereYear('date', $year)
                ->where('user_id', Auth::id())
                ->groupBy('week')
                ->get()
                ->keyBy('week');

            // Semaine courante
            $currentPlanned = isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->dist : 0;
            $currentDuration = isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->time * 60 : 0;
            // Semaine précédente
            $prevPlanned = isset($workoutStats[$prevWeekNumber]) ? $workoutStats[$prevWeekNumber]->dist : 0;
            $prevDuration = isset($workoutStats[$prevWeekNumber]) ? $workoutStats[$prevWeekNumber]->time * 60 : 0;

            $result['isValid'] = true;
            if ($prevPlanned > 0 && $currentPlanned > 0) {
                $diff = $currentPlanned - $prevPlanned;
                if ($diff != 0) {
                    $percent = ($diff / $prevPlanned) * 100;
                    $result['distance'] = [
                        'value' => $percent > 0 ? '+' . round($percent, 1) : round($percent, 1),
                        'previous' => $prevPlanned
                    ];
                }
            }
            if ($prevDuration > 0 && $currentDuration > 0) {
                $diff = $currentDuration - $prevDuration;
                if ($diff != 0) {
                    $percent = ($diff / $prevDuration) * 100;
                    $result['duration'] = [
                        'value' => $percent > 0 ? '+' . round($percent, 1) : round($percent, 1),
                        'previous' => $prevDuration
                    ];
                }
            }
        } catch (\Exception $e) {}
        return $result;
    }
    
    /**
     * Gets status information for a statistical increase.
     *
     * @param float $increase Percentage increase
     * @return array Status information with color, icon, status text and message
     */
    public function getIncreaseStatus(float $increase): array
    {
        if ($increase > 10) {
            return [
                'color' => 'text-red-400',
                'icon' => 'fa-exclamation-triangle',
                'status' => 'Significant Increase',
                'message' => 'An increase greater than 10% may increase the risk of injury.'
            ];
        } elseif ($increase < 0) {
            return [
                'color' => 'text-amber-400',
                'icon' => 'fa-exclamation-circle',
                'status' => 'Significant Decrease',
                'message' => 'A significant decrease may affect training progression and consistency.'
            ];
        } elseif ($increase == 0) {
            return [
                'color' => 'text-emerald-400',
                'icon' => 'fa-check-circle',
                'status' => 'No Change',
                'message' => 'No change is ideal for maintaining a steady training load.'
            ];
        } else {
            return [
                'color' => 'text-emerald-400',
                'icon' => 'fa-check-circle',
                'status' => 'Ideal Progression',
                'message' => 'A increase below +10% is recommended for safe progression.'
            ];
        }
    }
    
    /**
     * Retourne les informations du mois à partir de la clé (YYYY-MM).
     *
     * @param string $monthKey Mois au format YYYY-MM
     * @return array Informations du mois : name et number
     */
    public function getMonthInfo(string $monthKey): array
    {
        static $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
        ];
        try {
            list($yearPart, $monthPart) = explode('-', $monthKey);
            $monthName = $months[$monthPart] ?? 'Month ' . $monthKey;
            $monthNumber = (int)$monthPart;
            return [
                'name' => $monthName,
                'number' => $monthNumber
            ];
        } catch (\Exception $e) {
            return [
                'name' => 'Month ' . $monthKey,
                'number' => 0
            ];
        }
    }
    
    /**
     * Gets color palette information for a week.
     * 
     * @param string $baseColor Base color
     * @return array Color palette with dark, mid, light shades and border color
     */
    public function getWeekColorPalette(string $baseColor): array
    {
        $color = str_replace('bg-', '', $baseColor);
        
        // Extraire la teinte de couleur et la luminosité
        preg_match('/(.*)-(\d{3})$/', $color, $matches);
        $colorName = $matches[1] ?? 'slate';
        $colorWeight = isset($matches[2]) ? intval($matches[2]) : 500;
        
        // Créer une palette de couleurs plus marquée
        return [
            'darkShade' => $colorName . '-' . min(900, $colorWeight + 200),
            'midShade' => $color,
            'lightShade' => $colorName . '-' . max(100, $colorWeight - 200),
            'borderColor' => $colorName . '-' . min(500, $colorWeight)
        ];
    }
    
    /**
     * Calculates the completion percentage of a statistic.
     *
     * @param float $actual Actual value
     * @param float $planned Planned value
     * @return float Completion percentage (capped at 100%)
     */
    public function calculateCompletionPercentage(float $actual, float $planned): float
    {
        if ($planned <= 0) {
            return 0;
        }
        
        return min(($actual / $planned) * 100, 100);
    }
}
