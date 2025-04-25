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
            $monthStats = $this->calculateMonthStats($weeks);
            $yearStats = $this->calculateYearStats($weeks);
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
     * @param Collection $activities Collection of user activities
     * @param Collection $workouts Collection of user workouts
     * @return Collection Collection of week objects with calculated statistics
     */
    private function getWeeks(Collection $activities, Collection $workouts): Collection
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
            $weekStats = $this->calculateWeekStats($start, $end, $activities, $workouts);

            $week->start = $start->format('d M');
            $week->end = $end->format('d M');
            $week->month = $thursday->format('Y-m'); // Use Thursday's month
            $week->actual_stats = $weekStats['actual'];
            $week->planned_stats = $weekStats['planned'];
            $week->days = $this->generateWeekDays($start);
            $week->is_current_week = $this->isCurrentWeek($start, $end); // Ajouter l'indicateur de semaine courante

            $weeks->push($week);
        }

        return $weeks;
    }

    /**
     * Calculate statistics for a specific week.
     *
     * @param Carbon $start Start date of the week
     * @param Carbon $end End date of the week
     * @param Collection $activities Collection of user activities
     * @param Collection $workouts Collection of user workouts
     * @return array Array containing actual and planned statistics
     */
    private function calculateWeekStats(Carbon $start, Carbon $end, Collection $activities, Collection $workouts): array
    {
        // Activités réelles
        $actualActivities = $activities->filter(function ($activity) use ($start, $end) {
            $date = Carbon::parse($activity->start_date);
            return $date->between($start, $end);
        });

        // Entraînements planifiés
        $plannedWorkouts = $workouts->filter(function ($workout) use ($start, $end) {
            $date = Carbon::parse($workout->date);
            return $date->between($start, $end);
        });

        return [
            'actual' => [
                'distance' => round($actualActivities->sum('distance') / 1000, 1),
                'elevation' => $actualActivities->sum('total_elevation_gain'),
                'duration' => $actualActivities->sum('moving_time')
            ],
            'planned' => [
                'distance' => $plannedWorkouts->sum('distance'),
                'elevation' => $plannedWorkouts->sum('elevation'),
                'duration' => $plannedWorkouts->sum('duration') * 60
            ]
        ];
    }

    /**
     * Calculate monthly statistics from weekly data.
     *
     * @param Collection $weeks Collection of weeks with their statistics
     * @return array Array of monthly statistics organized by month key
     */
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

    /**
     * Calculate yearly statistics from weekly data.
     *
     * @param Collection $weeks Collection of weeks with their statistics
     * @return array Array of yearly statistics for actual and planned values
     */
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
     * Generates an array of day information for each day in a week.
     *
     * @param Carbon $start The first day of the week
     * @return array Array of day information for the week
     */
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

    /**
     * Check if a week contains the current day.
     *
     * @param Carbon $start The first day of the week
     * @param Carbon $end The last day of the week
     * @return bool True if the week contains the current day
     */
    private function isCurrentWeek(Carbon $start, Carbon $end): bool
    {
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
    public function calculateDevelopmentWeekProgress(Week $currentWeek, Collection $weeksInMonth, int $currentIndex): array
    {
        $result = [
            'distance' => null,
            'duration' => null,
            'isValid' => false
        ];
        
        try {
            // 1. Check that the current week is of development type
            if (!$currentWeek->type || strtolower($currentWeek->type->name) !== 'development') {
                return $result;
            }
            
            // 2. Get the start and end dates of the current week
            $year = $currentWeek->year;
            $weekNumber = $currentWeek->week_number;
            $startDate = Carbon::createFromDate($year, 1, 1)->setISODate($year, $weekNumber, 1)->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();
            
            // 3. Calculate the dates of the previous week
            $prevWeekStart = $startDate->copy()->subDays(7);
            $prevWeekEnd = $prevWeekStart->copy()->endOfWeek();
            
            // 4. Retrieve the previous week from the database
            $prevWeek = Week::where('user_id', Auth::id())
                ->where(function($query) use ($prevWeekStart) {
                    $query->where('year', $prevWeekStart->year)
                          ->where('week_number', $prevWeekStart->isoWeek);
                })
                ->with('type')
                ->first();
            
            // 5. Check that the previous week exists and is also of development type
            if (!$prevWeek || !$prevWeek->type || strtolower($prevWeek->type->name) !== 'development') {
                return $result;
            }
            
            // 6. Calculate statistics directly using calculateWeekStats
            $currentWeekStats = $this->calculateWeekStats(
                $startDate,
                $endDate,
                $this->activities,
                $this->workouts
            );
            
            $prevWeekStats = $this->calculateWeekStats(
                $prevWeekStart,
                $prevWeekEnd,
                $this->activities,
                $this->workouts
            );
            
            // 7. Calculate the percentage increases
            $result['isValid'] = true;
            
            // 8. Calculate progression for distance if both weeks have planned values > 0
            if ((float)$prevWeekStats['planned']['distance'] > 0 && (float)$currentWeekStats['planned']['distance'] > 0) {
                $difference = (float)$currentWeekStats['planned']['distance'] - (float)$prevWeekStats['planned']['distance'];
                if ($difference != 0) {  // Only display if there is a difference
                    $result['distance'] = [
                        'value' => ($difference / (float)$prevWeekStats['planned']['distance']) * 100,
                        'previous' => (float)$prevWeekStats['planned']['distance']
                    ];
                }
            }
            
            // 9. Calculate progression for duration if both weeks have planned values > 0
            if ((float)$prevWeekStats['planned']['duration'] > 0 && (float)$currentWeekStats['planned']['duration'] > 0) {
                $difference = (float)$currentWeekStats['planned']['duration'] - (float)$prevWeekStats['planned']['duration'];
                if ($difference != 0) {  // Only display if there is a difference
                    $result['duration'] = [
                        'value' => ($difference / (float)$prevWeekStats['planned']['duration']) * 100,
                        'previous' => (float)$prevWeekStats['planned']['duration']
                    ];
                }
            }
        } catch (\Exception $e) {
            // In case of an error, we simply return the default result
            // without displaying an error to the user
        }
        
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
     * Verifies and corrects a month name from its key.
     *
     * @param string $monthKey Month key in YYYY-MM format
     * @return array Month information with name, number and mismatch flag
     */
    public function getMonthInfo(string $monthKey): array
    {
        try {
            list($yearPart, $monthPart) = explode('-', $monthKey);
            
            // Créer l'objet date correct
            $monthDate = Carbon::createFromDate($yearPart, (int)$monthPart, 1);
            $monthName = $monthDate->format('F');
            
            // Pour validation
            $monthNumber = (int)$monthPart;
            $expectedMonthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
            
            // Vérifier si le nom du mois correspond à celui attendu
            $hasMismatch = ($monthName !== $expectedMonthName);
            
            // Forcer le nom correct du mois si nécessaire
            if ($hasMismatch) {
                $monthName = $expectedMonthName;
            }
            
            return [
                'name' => $monthName,
                'number' => $monthNumber,
                'hasMismatch' => $hasMismatch
            ];
        } catch (\Exception $e) {
            return [
                'name' => "Month $monthKey",
                'number' => 0,
                'hasMismatch' => true
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
