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
     * Cache TTL constants in seconds
     */
    private const CACHE_TTL_YEARS = 24 * 60 * 60; // 24 heures pour les années disponibles
    private const CACHE_TTL_ACTIVITIES = 60 * 60; // 60 minutes pour les activités
    private const CACHE_TTL_WORKOUTS = 5 * 60; // 5 minutes pour les workouts (modifiés souvent)
    private const CACHE_TTL_WEEKS = 30 * 60; // 30 minutes pour les semaines
    private const CACHE_TTL_STATS = 15 * 60; // 15 minutes pour les statistiques

    /**
     * Cache key prefixes
     */
    private const CACHE_KEY_YEARS = 'calendar-years-';
    private const CACHE_KEY_ACTIVITIES = 'calendar-activities-';
    private const CACHE_KEY_WORKOUTS = 'calendar-workouts-';
    private const CACHE_KEY_WEEKS = 'calendar-weeks-';
    private const CACHE_KEY_MONTH_STATS = 'calendar-month-stats-';
    private const CACHE_KEY_YEAR_STATS = 'calendar-year-stats-';
    
    /**
     * Event listeners for the component.
     * 
     * @var array
     */
    protected $listeners = [
        'refresh' => '$refresh',
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
        
        // Cache pour les années disponibles (longue durée)
        $years = Cache::remember(
            self::CACHE_KEY_YEARS . $userId, 
            self::CACHE_TTL_YEARS, 
            fn() => $this->getAvailableYears()
        );
        
        // Cache pour les activités (durée moyenne)
        $this->activities = Cache::remember(
            self::CACHE_KEY_ACTIVITIES . $userId . '-' . $this->year, 
            self::CACHE_TTL_ACTIVITIES, 
            fn() => $this->getActivities()
        );
        
        // Cache pour les workouts (durée courte car fréquemment modifiés)
        $this->workouts = Cache::remember(
            self::CACHE_KEY_WORKOUTS . $userId . '-' . $this->year, 
            self::CACHE_TTL_WORKOUTS, 
            fn() => $this->getWorkouts()
        );
        
        // Cache pour les semaines
        $this->weeks = Cache::remember(
            self::CACHE_KEY_WEEKS . $userId . '-' . $this->year, 
            self::CACHE_TTL_WEEKS, 
            fn() => $this->getWeeks()
        );
        
        // Groupement des semaines par mois (pas besoin de cacher car c'est une opération de mémoire)
        $this->months = $this->groupWeeksByMonth($this->weeks);
        
        // Cache pour les statistiques mensuelles
        $this->monthStats = Cache::remember(
            self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year, 
            self::CACHE_TTL_STATS, 
            fn() => $this->calculateMonthStatsFromDb()
        );
        
        // Cache pour les statistiques annuelles
        $this->yearStats = Cache::remember(
            self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year, 
            self::CACHE_TTL_STATS, 
            fn() => $this->calculateYearStatsFromDb()
        );
        
        return view('livewire.calendar', [
            'months' => $this->months,
            'monthStats' => $this->monthStats,
            'yearStats' => $this->yearStats,
            'weekTypes' => \App\Models\WeekType::all(),
            'activities' => $this->activities,
            'workouts' => $this->workouts,
            'year' => $this->year,
            'years' => $years
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
        $this->dispatch('reload-tooltips');
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
        $this->dispatch('reload-tooltips');
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

        // Récupérer la première semaine de l'année (qui peut débuter en décembre de l'année précédente)
        $firstWeekStart = $date->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
        
        // Récupérer la dernière semaine de l'année (qui peut finir en janvier de l'année suivante)
        $lastWeekStart = $date->endOfYear()->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
        $lastWeekNumber = $lastWeekStart->weekOfYear;

        // Ajuster pour les années où la dernière semaine est la semaine 1 de l'année suivante
        if ($lastWeekStart->weekOfYear === 1) {
            $lastPrevWeek = $lastWeekStart->subWeek();
            $lastWeekNumber = $lastPrevWeek->weekOfYear;
        }

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = $date->setISODate($this->year, $weekNumber)->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
            
            // Sortir de la boucle si on dépasse la dernière semaine de l'année
            if ($start->year > $this->year && $weekNumber > 1) 
                break;
                
            $end = $start->endOfWeek(\Carbon\CarbonImmutable::SUNDAY);
            $thursday = $start->addDays(3);  // Le jeudi détermine l'année à laquelle appartient la semaine ISO

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

            // Pour les semaines qui chevauchent l'année, on doit récupérer les statistiques correctement
            $weekActualStats = [
                'distance' => 0,
                'elevation' => 0,
                'duration' => 0,
            ];
            
            $weekPlannedStats = [
                'distance' => 0,
                'elevation' => 0,
                'duration' => 0,
            ];
            
            // Utiliser les stats groupées pour cette semaine
            if (isset($activityStats[$weekNumber])) {
                $weekActualStats['distance'] = round($activityStats[$weekNumber]->dist / 1000, 1);
                $weekActualStats['elevation'] = $activityStats[$weekNumber]->ele;
                $weekActualStats['duration'] = $activityStats[$weekNumber]->time;
            }
            
            if (isset($workoutStats[$weekNumber])) {
                $weekPlannedStats['distance'] = $workoutStats[$weekNumber]->dist;
                $weekPlannedStats['elevation'] = $workoutStats[$weekNumber]->ele;
                $weekPlannedStats['duration'] = $workoutStats[$weekNumber]->time * 60;
            }
            
            // Pour les semaines qui chevauchent deux années, on doit aussi récupérer les stats de l'autre année
            // Première semaine de l'année pouvant avoir des jours en décembre de l'année précédente
            if ($weekNumber === 1 && $start->year < $this->year) {
                // Récupérer les statistiques de la dernière semaine de l'année précédente
                $lastWeekPrevYearStats = $this->getWeekStatsFromRange($start, $end->copy()->startOfYear()->subDay());
                $weekActualStats['distance'] += round($lastWeekPrevYearStats['actual']['distance'] / 1000, 1);
                $weekActualStats['elevation'] += $lastWeekPrevYearStats['actual']['elevation'];
                $weekActualStats['duration'] += $lastWeekPrevYearStats['actual']['duration'];
                
                $weekPlannedStats['distance'] += $lastWeekPrevYearStats['planned']['distance'];
                $weekPlannedStats['elevation'] += $lastWeekPrevYearStats['planned']['elevation'];
                $weekPlannedStats['duration'] += $lastWeekPrevYearStats['planned']['duration'] * 60;
            }
            
            // Dernière semaine de l'année pouvant avoir des jours en janvier de l'année suivante
            if ($weekNumber === $lastWeekNumber && $end->year > $this->year) {
                // Récupérer les statistiques de la première semaine de l'année suivante
                $firstWeekNextYearStats = $this->getWeekStatsFromRange($end->copy()->startOfYear(), $end);
                $weekActualStats['distance'] += round($firstWeekNextYearStats['actual']['distance'] / 1000, 1);
                $weekActualStats['elevation'] += $firstWeekNextYearStats['actual']['elevation'];
                $weekActualStats['duration'] += $firstWeekNextYearStats['actual']['duration'];
                
                $weekPlannedStats['distance'] += $firstWeekNextYearStats['planned']['distance'];
                $weekPlannedStats['elevation'] += $firstWeekNextYearStats['planned']['elevation'];
                $weekPlannedStats['duration'] += $firstWeekNextYearStats['planned']['duration'] * 60;
            }

            $week->start = $start->translatedFormat(__('calendar.date_formats.day_month'));
            $week->end = $end->translatedFormat(__('calendar.date_formats.day_month'));
            $week->month = $thursday->format('Y-m');
            $week->actual_stats = $weekActualStats;
            $week->planned_stats = $weekPlannedStats;
            $week->days = $this->generateWeekDays($start);
            $week->is_current_week = $this->isCurrentWeek($start, $end);

            $weeks->push($week);
        }

        return $weeks;
    }
    
    /**
     * Récupère les statistiques d'activities et workouts pour une plage de dates spécifique
     * Utilisé pour les semaines qui chevauchent deux années
     *
     * @param \Carbon\Carbon|\Carbon\CarbonImmutable $startDate
     * @param \Carbon\Carbon|\Carbon\CarbonImmutable $endDate
     * @return array
     */
    private function getWeekStatsFromRange($startDate, $endDate): array
    {
        $activities = Activity::selectRaw('SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->where('user_id', Auth::id())
            ->whereBetween('start_date', [$startDate, $endDate])
            ->first();
            
        $workouts = Workout::selectRaw('SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->first();
        
        return [
            'actual' => [
                'distance' => $activities ? $activities->dist : 0,
                'elevation' => $activities ? $activities->ele : 0,
                'duration' => $activities ? $activities->time : 0,
            ],
            'planned' => [
                'distance' => $workouts ? $workouts->dist : 0,
                'elevation' => $workouts ? $workouts->ele : 0,
                'duration' => $workouts ? $workouts->time : 0,
            ]
        ];
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
    
    /**
     * Invalide les caches pertinents de manière sélective
     * 
     * @param string $type Type de données modifiées (workout, activity, week)
     * @param int|null $weekNumber Numéro de semaine modifiée (optionnel)
     * @param int|null $month Mois modifié (optionnel)
     * @return void
     */
    private function invalidateCache($type = 'all', $weekNumber = null, $month = null)
    {
        $userId = Auth::id();
        
        // Invalidation sélective selon le type de données modifiées
        switch ($type) {
            case 'workout':
                // Invalide le cache des workouts
                Cache::forget(self::CACHE_KEY_WORKOUTS . $userId . '-' . $this->year);
                // Invalide les stats de la semaine, du mois et de l'année 
                Cache::forget(self::CACHE_KEY_WEEKS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year);
                break;
                
            case 'activity':
                // Invalide le cache des activités
                Cache::forget(self::CACHE_KEY_ACTIVITIES . $userId . '-' . $this->year);
                // Invalide les stats hebdomadaires, mensuelles et annuelles
                Cache::forget(self::CACHE_KEY_WEEKS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year);
                break;
                
            case 'week':
                // Invalide le cache des semaines
                Cache::forget(self::CACHE_KEY_WEEKS . $userId . '-' . $this->year);
                break;
                
            case 'all':
            default:
                // Invalide tous les caches liés à l'année courante
                Cache::forget(self::CACHE_KEY_ACTIVITIES . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_WORKOUTS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_WEEKS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year);
                break;
        }
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
        $this->dispatch('toast', __('calendar.messages.week_type_updated'), 'success');
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
     * Updates the date of a workout when it's moved in the calendar.
     *
     * @param int $workoutId The workout identifier
     * @param string $newDate The new date for the workout
     * @return void
     */
    #[On('workout-moved')]
    public function updateWorkoutDate($workoutId, $newDate)
    {
        try {
            // Vérifier si un déplacement similaire a été effectué récemment
            // pour éviter le traitement en double du même événement
            $workout = Workout::with('type')->findOrFail($workoutId);
            $parsedDate = Carbon::parse($newDate);
            
            // Si le workout est déjà à cette date, ne rien faire
            if ($workout->date->isSameDay($parsedDate)) {
                return;
            }

            $workout->update([
                'date' => $parsedDate
            ]);
            
            // Utilisation de l'invalidation sélective du cache
            $this->invalidateCache('workout');
            $this->dispatch('toast', __('calendar.messages.workout_moved', [
                'type' => $workout->type->name,
                'date' => Carbon::parse($newDate)->translatedFormat(__('calendar.date_formats.full_date'))
            ]), 'success');
            
            // Émettre l'événement pour recharger les tooltips
            $this->dispatch('reload-tooltips');
        } catch (\Exception $e) {
            $this->dispatch('toast', __('calendar.messages.error_moving_workout', ['error' => $e->getMessage()]), 'error');
        }
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
        try {
            // Vérifier si ce workout a déjà été copié sur cette date pour éviter les doublons
            $existingWorkout = Workout::where('user_id', Auth::id())
                ->where('date', Carbon::parse($newDate))
                ->where('workout_type_id', function($query) use ($workoutId) {
                    $query->select('workout_type_id')
                        ->from('workouts')
                        ->where('id', $workoutId);
                })
                ->where('created_at', '>=', now()->subSeconds(5)) // Vérifie si créé dans les 5 dernières secondes
                ->exists();

            if ($existingWorkout) {
                // Un workout identique a déjà été créé très récemment à cette date
                return;
            }
            
            $originalWorkout = Workout::with('type')->findOrFail($workoutId);
            
            $newWorkout = $originalWorkout->replicate();
            $newWorkout->date = Carbon::parse($newDate);
            $newWorkout->save();

            // Utilisation de l'invalidation sélective du cache
            $this->invalidateCache('workout');
            $this->dispatch('toast', __('calendar.messages.workout_copied', [
                'type' => $originalWorkout->type->name,
                'date' => Carbon::parse($newDate)->translatedFormat(__('calendar.date_formats.full_date'))
            ]), 'success');
            
            // Émettre l'événement pour recharger les tooltips
            $this->dispatch('reload-tooltips');
        } catch (\Exception $e) {
            $this->dispatch('toast', __('calendar.messages.error_copying_workout', ['error' => $e->getMessage()]), 'error');
        }
    }

    /**
     * Refreshes the workouts.
     *
     * @return void
     */
    #[On('workout-created')]
    #[On('workout-deleted')]
    #[On('workout-updated')]
    public function refreshWorkouts()
    {
        $this->invalidateCache('workout');
        $this->dispatch('reload-tooltips');
    }
    
    /**
     * Refreshes the activities.
     *
     * @return void
     */
    #[On('activity-deleted')]
    public function refreshActivities()
    {
        $this->invalidateCache('activity');
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
                $this->dispatch('toast', __('calendar.messages.auth_required'), 'error');
                return;
            }
            
            $result = $syncService->sync($user);
            
            if ($result['success']) {
                if ($result['count'] > 0) {
                    $this->dispatch('toast', $result['message'], 'success');
                    // Invalider le cache des activités avant de les récupérer à nouveau
                    $this->invalidateCache('activity');
                    $this->activities = $this->getActivities();
                    $this->dispatch('reload-tooltips');
                } else {
                    $this->dispatch('toast', $result['message'], 'info');
                }
            } else {
                $this->dispatch('toast', $result['message'], 'error');
            }

        } catch (\Exception $e) {
            $this->dispatch('toast', __('calendar.messages.generic_error', ['message' => $e->getMessage()]), 'error');
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
            'title' => __('calendar.delete_modal.confirm_deletion'),
            'message' => __('calendar.delete_modal.confirm_delete_all', [
                'year' => $this->year,
                'count' => $count
            ]),
            'confirmButtonText' => __('calendar.delete_modal.delete_all'),
            'cancelButtonText' => __('calendar.delete_modal.cancel'),
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
        // Invalidation de tous les caches liés à l'année
        $this->invalidateCache('all');
        $this->dispatch('toast', __('calendar.messages.workouts_deleted', ['count' => $count]), 'success');
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
        $monthName = $month->translatedFormat('F');
        
        $count = Workout::where('user_id', Auth::id())
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => __('calendar.delete_modal.confirm_monthly_deletion'),
            'message' => __('calendar.delete_modal.confirm_delete_month', [
                'month' => $monthName,
                'year' => $month->year,
                'count' => $count
            ]),
            'confirmButtonText' => __('calendar.delete_modal.delete_sessions'),
            'cancelButtonText' => __('calendar.delete_modal.cancel'),
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
        // Invalidation des caches liés aux workouts
        $this->invalidateCache('workout');
        $this->dispatch('toast', __('calendar.messages.workouts_deleted', ['count' => $count]), 'success');
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
            'title' => __('calendar.delete_modal.confirm_weekly_deletion'),
            'message' => __('calendar.delete_modal.confirm_delete_week', [
                'week' => $week->week_number,
                'count' => $count
            ]),
            'confirmButtonText' => __('calendar.delete_modal.delete_sessions'),
            'cancelButtonText' => __('calendar.delete_modal.cancel'),
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
        $this->dispatch('toast', __('calendar.messages.workouts_deleted', ['count' => $count]), 'success');
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
     * Calcule la progression entre la semaine courante et une semaine précédente valide avec des données planifiées.
     * On cherche la semaine précédente (avec des données planifiées) qui n'est pas "reduced", "recovery" ou "race".
     * Si une telle semaine n'est pas trouvée immédiatement, on continue à remonter jusqu'à trouver une semaine valide.
     * 
     * @param \App\Models\Week $currentWeek La semaine courante
     * @return array Résultats de progression pour distance et durée
     */
    public function calculateDevelopmentWeekProgress(Week $currentWeek): array
    {
        $result = [
            'distance' => null,
            'duration' => null,
            'isValid' => false
        ];

        try {
            // Récupérer toutes les semaines (déjà calculées avec les bons stats même pour les semaines à cheval)
            $allWeeks = $this->weeks;
            if ($allWeeks->isEmpty()) {
                return $result;
            }

            // Filtrer les semaines pour ne garder que celles qui précèdent la semaine courante
            $previousWeeks = $allWeeks->filter(function($week) use ($currentWeek) {
                return $week->week_number < $currentWeek->week_number;
            })->sortByDesc('week_number');

            // Si aucune semaine précédente n'existe
            if ($previousWeeks->isEmpty()) {
                return $result;
            }
            
            // Types de semaines à ignorer pour la comparaison
            $ignoredWeekTypes = ['reduced', 'recovery', 'race'];
            
            // Trouver la première semaine précédente valide avec des données planifiées
            $comparisonWeek = null;
            
            foreach ($previousWeeks as $week) {
                // Vérifier si la semaine a des données planifiées
                $hasPlannedData = $week->planned_stats['distance'] > 0 || $week->planned_stats['duration'] > 0;
                
                if (!$hasPlannedData) {
                    // Ignorer les semaines sans données planifiées
                    continue;
                }
                
                // Vérifier si le type de semaine doit être ignoré
                $weekTypeName = $week->type ? strtolower($week->type->name) : '';
                $isIgnoredType = in_array($weekTypeName, $ignoredWeekTypes);
                
                if (!$isIgnoredType) {
                    // On a trouvé une semaine valide pour la comparaison
                    $comparisonWeek = $week;
                    break;
                }
            }

            // Si aucune semaine valide n'a été trouvée
            if (!$comparisonWeek) {
                return $result;
            }

            // Calculer la progression de distance
            if ($comparisonWeek->planned_stats['distance'] > 0 && $currentWeek->planned_stats['distance'] > 0) {
                $diff = $currentWeek->planned_stats['distance'] - $comparisonWeek->planned_stats['distance'];
                $percent = ($diff / $comparisonWeek->planned_stats['distance']) * 100;
                $result['distance'] = [
                    'value' => $percent > 0 ? '+' . round($percent, 1) : round($percent, 1),
                    'previous' => $comparisonWeek->planned_stats['distance'],
                    'comparedTo' => $comparisonWeek->type ? $comparisonWeek->type->name : 'Previous Week',
                    'weekNumber' => $comparisonWeek->week_number
                ];
                $result['isValid'] = true;
            }

            // Calculer la progression de durée
            if ($comparisonWeek->planned_stats['duration'] > 0 && $currentWeek->planned_stats['duration'] > 0) {
                $diff = $currentWeek->planned_stats['duration'] - $comparisonWeek->planned_stats['duration'];
                $percent = ($diff / $comparisonWeek->planned_stats['duration']) * 100;
                $result['duration'] = [
                    'value' => $percent > 0 ? '+' . round($percent, 1) : round($percent, 1),
                    'previous' => $comparisonWeek->planned_stats['duration'],
                    'comparedTo' => $comparisonWeek->type ? $comparisonWeek->type->name : 'Previous Week',
                    'weekNumber' => $comparisonWeek->week_number
                ];
                $result['isValid'] = true;
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner le résultat par défaut
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
     * Retourne les informations du mois à partir de la clé (YYYY-MM).
     *
     * @param string $monthKey Mois au format YYYY-MM
     * @return array Informations du mois : name et number
     */
    public function getMonthInfo(string $monthKey): array
    {
        try {
            list($yearPart, $monthPart) = explode('-', $monthKey);
            $monthNumber = (int)$monthPart;
            
            // Créer un objet Carbon pour le mois concerné
            $date = \Carbon\Carbon::createFromDate($yearPart, $monthNumber, 1);
            
            // Utiliser la traduction via le helper trans() ou __()
            $monthName = __('calendar.months.' . strtolower($date->format('F')));
            
            return [
                'name' => $monthName,
                'number' => sprintf('%02d', $monthNumber)
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
