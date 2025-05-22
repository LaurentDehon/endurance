<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Week;
use App\Models\Year;
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
    use CalendarHelpers;
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
        'confirmDeleteWeek',
        'setWeekType',
        'workout-saved' => 'updateWeekStatsForDate', // Ajout du listener pour maj semaine
    ];

    /**
     * The current year model.
     * 
     * @var \App\Models\Year
     */
    public $yearModel;
    
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
        
        // Make sure the year model exists for this user
        $this->initializeYearModel();
        
        // Initialize the weeks and days for the year
        $this->setupYearData();
    }
    
    /**
     * Initializes or retrieves the Year model for the current user and year.
     *
     * @return void
     */
    private function initializeYearModel()
    {
        $userId = Auth::id();
        
        // Get or create the Year model
        $this->yearModel = Year::firstOrCreate(
            ['user_id' => $userId, 'year' => $this->year]
        );
    }    

    /**
     * Render the calendar component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Assurez-vous que l'année est correctement initialisée
        if (!$this->yearModel) {
            $this->initializeYearModel();
        }

        // S'assurer que les semaines et les jours sont créés lors du premier rendu
        if (!$this->weeks || $this->weeks->isEmpty()) {
            $this->weeks = $this->getWeeks();
            
            // S'assurer que les workouts sont correctement associés aux jours
            $this->reassociateWorkoutsWithDays();
        }

        // Récupérer l'année, les semaines et les jours via Eloquent avec les relations nécessaires
        $yearModel = $this->yearModel->fresh(['weeks.days', 'days']);
        
        // Récupérer les semaines avec leurs jours et types
        $weeks = $yearModel->weeks()->with(['type', 'days'])->get()->sortBy('week_number');
        
        // Si aucune semaine n'existe encore, générer les semaines
        if ($weeks->isEmpty()) {
            $this->weeks = $this->getWeeks();
            $yearModel = $this->yearModel->fresh(['weeks.days', 'days']);
            $weeks = $yearModel->weeks()->with(['type', 'days'])->get()->sortBy('week_number');
        }
        
        // Parcourir chaque semaine et calculer ses statistiques actuelles
        foreach ($weeks as $week) {
            $stats = $week->calculateStats();
            $week->actual_stats = $stats['actual_stats'];
            $week->planned_stats = $stats['planned_stats'];
        }
        
        // Récupérer les workouts et activités y compris ceux de la dernière semaine de l'année précédente
        // qui font partie de la première semaine de l'année en cours
        $activities = $this->getActivities();
        $workouts = $this->getWorkouts();
        
        // Vérifier spécifiquement les workouts de la première semaine
        $this->reloadFirstWeekWorkouts();

        // Group weeks by month format for view compatibility (key = YYYY-MM)
        $monthsGrouped = collect();
        foreach ($weeks as $week) {
            // Utiliser le jeudi pour déterminer le mois de la semaine (règle ISO)
            $startOfWeek = Carbon::now()->setISODate($this->year, $week->week_number)->startOfWeek(Carbon::MONDAY);
            $thursday = $startOfWeek->copy()->addDays(3);
            $monthKey = sprintf('%04d-%02d', $thursday->year, $thursday->month);
            
            if (!$monthsGrouped->has($monthKey)) {
                $monthsGrouped[$monthKey] = collect();
            }
            $monthsGrouped[$monthKey]->push($week);
        }

        // Calculer les stats mensuelles et annuelles (en DB ou via helpers existants)
        $this->monthStats = $this->getCachedData(
            self::CACHE_KEY_MONTH_STATS, 
            self::CACHE_TTL_STATS, 
            fn() => $this->calculateStatsFromDb('month')
        );
        $this->yearStats = $this->getCachedData(
            self::CACHE_KEY_YEAR_STATS, 
            self::CACHE_TTL_STATS, 
            fn() => $this->calculateStatsFromDb('year')
        );

        // Assurez-vous que monthsGrouped contient toutes les clés mensuelles pour l'année en cours
        // afin d'éviter les erreurs "Undefined array key" dans la vue
        for ($m = 1; $m <= 12; $m++) {
            $monthKey = sprintf('%04d-%02d', $this->year, $m);
            if (!isset($monthsGrouped[$monthKey])) {
                $monthsGrouped[$monthKey] = collect([]);
            }
        }

        // Pour compatibilité avec la vue
        $this->months = $monthsGrouped;
        $this->weeks = $weeks;
        $this->activities = $activities;
        $this->workouts = $workouts;

        return view('livewire.calendar', [
            'months' => $this->months,
            'monthStats' => $this->monthStats,
            'yearStats' => $this->yearStats,
            'weekTypes' => \App\Models\WeekType::all(),
            'activities' => $this->activities,
            'workouts' => $this->workouts,
            'year' => $this->year
        ]);
    }

    /**
     * Navigate to the previous year.
     *
     * @return void
     */
    public function previousYear()
    {
        $this->year--;
        $this->setupYearData();
    }

    /**
     * Navigate to the next year.
     *
     * @return void
     */
    public function nextYear()
    {
        $this->year++;
        
        $this->setupYearData();
    }

    /**
     * Get activities for the current year.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivities()
    {
        $year = $this->year;
        
        // Get the first week of the year, which might start in the previous year
        $firstWeekStart = Carbon::createFromDate($year, 1, 1)->startOfWeek(Carbon::MONDAY);
        
        // Use the first week's start date as our start date, which may include days from the previous year
        $startDate = $firstWeekStart;
        
        // Similarly, get the last week which might extend into the next year
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
        
        // Get the first week of the year, which might start in the previous year
        $firstWeekStart = Carbon::createFromDate($year, 1, 1)->startOfWeek(Carbon::MONDAY);
        
        // Use the first week's start date as our start date, which may include days from the previous year
        $startDate = $firstWeekStart;
        
        // Similarly, get the last week which might extend into the next year
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
        $userId = Auth::id();
        $year = $this->year;
        
        // Déterminer les dates de début et de fin pour inclure les jours de la dernière semaine de l'année précédente
        $firstWeekStart = \Carbon\CarbonImmutable::create($year, 1, 1)->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
        $endDate = \Carbon\CarbonImmutable::create($year, 12, 31)->endOfWeek(\Carbon\CarbonImmutable::SUNDAY);
        
        // Récupérer les stats groupées par semaine pour les activités réelles
        $activityQuery = Activity::selectRaw('WEEK(start_date, 1) as week, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->where('user_id', $userId)
            ->whereBetween('start_date', [$firstWeekStart, $endDate])
            ->groupBy('week');
            
        $activityStats = $activityQuery->get()->keyBy('week');

        $workoutQuery = Workout::selectRaw('WEEK(date, 1) as week, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->where('user_id', $userId)
            ->whereBetween('date', [$firstWeekStart, $endDate])
            ->groupBy('week');
            
        $workoutStats = $workoutQuery->get()->keyBy('week');

        // Pré-charger tous les Week de l'année avec leur type
        $weeksFromDb = Week::with('type')
            ->where('user_id', $userId)
            ->where('year', $year)
            ->get()
            ->keyBy('week_number');

        $date = \Carbon\CarbonImmutable::create($year, 1, 1)->startOfYear();
        $weeks = collect();

        // Calculer les semaines de début et de fin d'année
        $lastWeekStart = $date->endOfYear()->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
        $lastWeekNumber = $lastWeekStart->weekOfYear;

        // Ajuster pour les années où la dernière semaine est la semaine 1 de l'année suivante
        if ($lastWeekStart->weekOfYear === 1) {
            $lastWeekPrev = $lastWeekStart->subWeek();
            $lastWeekNumber = $lastWeekPrev->weekOfYear;
        }

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = $date->setISODate($year, $weekNumber)->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
            
            // Sortir de la boucle si on dépasse la dernière semaine de l'année
            if ($start->year > $year && $weekNumber > 1) 
                break;
                
            $end = $start->endOfWeek(\Carbon\CarbonImmutable::SUNDAY);
            $thursday = $start->addDays(3);  // Le jeudi détermine l'année à laquelle appartient la semaine ISO

            // Récupérer la semaine de la collection, sinon en créer une nouvelle
            $week = $weeksFromDb->get($weekNumber);
            if (!$week) {
                $week = new Week([
                    'year' => $year,
                    'year_id' => $this->yearModel->id,
                    'week_number' => $weekNumber,
                    'user_id' => $userId,
                    'week_type_id' => null
                ]);
                $week->save();
                $week->setRelation('type', null);
                $weeksFromDb->put($weekNumber, $week);
            } else if ($week->year_id !== $this->yearModel->id) {
                // S'assurer que la semaine est associée à la bonne année
                $week->year_id = $this->yearModel->id;
                $week->save();
            }

            // Initialiser les stats pour cette semaine
            $weekActualStats = ['distance' => 0, 'elevation' => 0, 'duration' => 0];
            $weekPlannedStats = ['distance' => 0, 'elevation' => 0, 'duration' => 0];
            
            // Ajouter les stats de la semaine courante
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
            
            // Gérer les semaines qui chevauchent deux années
            if ($weekNumber === 1 && $start->year < $year) {
                // Semaine qui commence en décembre de l'année précédente
                $lastWeekPrevYearStats = $this->getWeekStatsFromRange($start, $end->copy()->startOfYear()->subDay());
                $weekActualStats['distance'] += round($lastWeekPrevYearStats['actual']['distance'] / 1000, 1);
                $weekActualStats['elevation'] += $lastWeekPrevYearStats['actual']['elevation'];
                $weekActualStats['duration'] += $lastWeekPrevYearStats['actual']['duration'];
                
                $weekPlannedStats['distance'] += $lastWeekPrevYearStats['planned']['distance'];
                $weekPlannedStats['elevation'] += $lastWeekPrevYearStats['planned']['elevation'];
                $weekPlannedStats['duration'] += $lastWeekPrevYearStats['planned']['duration'] * 60;
            }
            
            if ($weekNumber === $lastWeekNumber && $end->year > $year) {
                // Semaine qui finit en janvier de l'année suivante
                $firstWeekNextYearStats = $this->getWeekStatsFromRange($end->copy()->startOfYear(), $end);
                $weekActualStats['distance'] += round($firstWeekNextYearStats['actual']['distance'] / 1000, 1);
                $weekActualStats['elevation'] += $firstWeekNextYearStats['actual']['elevation'];
                $weekActualStats['duration'] += $firstWeekNextYearStats['actual']['duration'];
                
                $weekPlannedStats['distance'] += $firstWeekNextYearStats['planned']['distance'];
                $weekPlannedStats['elevation'] += $firstWeekNextYearStats['planned']['elevation'];
                $weekPlannedStats['duration'] += $firstWeekNextYearStats['planned']['duration'] * 60;
            }
            
            // Créer ou mettre à jour les jours de la semaine
            $this->createOrUpdateDays($week, $start, $end);

            // Assigner les propriétés de la semaine
            $week->setStartAttribute($start->translatedFormat(__('calendar.date_formats.day_month')));
            $week->setEndAttribute($end->translatedFormat(__('calendar.date_formats.day_month')));
            $week->month_key = $thursday->format('Y-m');
            $week->actual_stats = $weekActualStats;
            $week->planned_stats = $weekPlannedStats;
            $week->computed_days = $this->generateWeekDays($start);
            $week->is_current_week = $this->isCurrentWeek($start, $end);

            $weeks->push($week);
        }

        return $weeks;
    }
    
    /**
     * Helper function to query statistics for activities or workouts
     * 
     * @param string $type Either 'activity' or 'workout'
     * @param array $filter Array with start and end dates or day_id array
     * @return object Query result with dist, ele, and time properties
     */
    private function queryStatsForRange(string $type, array $filter): object
    {
        $userId = Auth::id();
        $query = null;
        
        if ($type === 'activity') {
            $query = Activity::selectRaw('SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
                ->where('user_id', $userId);
                
            if (isset($filter['day_id'])) {
                $query->whereIn('day_id', $filter['day_id']);
            } elseif (isset($filter[0]) && isset($filter[1])) {
                $query->whereBetween('start_date', [$filter[0], $filter[1]]);
            }
        } else { // workout
            $query = Workout::selectRaw('SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
                ->where('user_id', $userId);
                
            if (isset($filter['day_id'])) {
                $query->whereIn('day_id', $filter['day_id']);
            } elseif (isset($filter[0]) && isset($filter[1])) {
                $query->whereBetween('date', [$filter[0], $filter[1]]);
            }
        }
        
        return $query->first() ?: (object)['dist' => 0, 'ele' => 0, 'time' => 0];
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
        $activities = $this->queryStatsForRange('activity', [$startDate, $endDate]);
        $workouts = $this->queryStatsForRange('workout', [$startDate, $endDate]);
        
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
     * Récupère les statistiques groupées pour Activity et Workout
     * 
     * @param string $groupBy Le niveau de regroupement ('year' ou 'month')
     * @return array Les statistiques regroupées par année ou mois
     */
    private function getAggregatedStats(string $groupBy = 'year'): array
    {
        $userId = Auth::id();
        $isMonthly = ($groupBy === 'month');
        
        // Construction des requêtes selon le type de regroupement
        if ($isMonthly) {
            $activitySelect = 'YEAR(start_date) as year, MONTH(start_date) as month, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time';
            $workoutSelect = 'YEAR(date) as year, MONTH(date) as month, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time';
            $groupByClause = 'year, month';
            $keyFunc = function($item) { return sprintf('%04d-%02d', $item->year, $item->month); };
        } else {
            $activitySelect = 'YEAR(start_date) as year, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time';
            $workoutSelect = 'YEAR(date) as year, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time';
            $groupByClause = 'year';
            $keyFunc = function($item) { return $item->year; };
        }
        
        // Requête pour les activités
        $activityQuery = Activity::selectRaw($activitySelect)
            ->where('user_id', $userId);
            
        // Ajouter le filtre par année uniquement pour les stats mensuelles
        if ($isMonthly) {
            $activityQuery->whereYear('start_date', $this->year);
        }
        
        $activityStats = $activityQuery->groupBy(explode(', ', $groupByClause))
            ->get()
            ->keyBy($keyFunc);

        // Requête pour les workouts
        $workoutQuery = Workout::selectRaw($workoutSelect)
            ->where('user_id', $userId);
            
        // Ajouter le filtre par année uniquement pour les stats mensuelles
        if ($isMonthly) {
            $workoutQuery->whereYear('date', $this->year);
        }
        
        $workoutStats = $workoutQuery->groupBy(explode(', ', $groupByClause))
            ->get()
            ->keyBy($keyFunc);

        return [
            'activity' => $activityStats,
            'workout' => $workoutStats
        ];
    }

    // Removed redundant methods getYearlyStats and getMonthlyStats
    // These methods were just wrappers for getAggregatedStats('year') and getAggregatedStats('month')

    /**
     * Calculate statistics from SQL grouped data.
     *
     * @param string $type Either 'month' or 'year'
     * @return array Array of statistics for the specified type
     */
    private function calculateStatsFromDb(string $type = 'month'): array
    {
        if ($type === 'year') {
            $yearly = $this->getAggregatedStats('year');
            $activity = $yearly['activity'][$this->year] ?? null;
            return [
                'actual' => [
                    'distance' => $activity ? round($activity->dist / 1000, 1) : 0,
                    'elevation' => $activity ? $activity->ele : 0,
                    'duration' => $activity ? $activity->time : 0,
                ],
                'planned' => [
                    'distance' => 0,
                    'elevation' => 0,
                    'duration' => 0,
                ]
            ];
        } else {
            $monthly = $this->getAggregatedStats('month');
            $monthStats = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthKey = sprintf('%04d-%02d', $this->year, $m);
                $activity = $monthly['activity'][$monthKey] ?? null;
                $monthStats[$monthKey] = [
                    'actual' => [
                        'distance' => $activity ? round($activity->dist / 1000, 1) : 0,
                        'elevation' => $activity ? $activity->ele : 0,
                        'duration' => $activity ? $activity->time : 0,
                    ],
                    'planned' => [
                        'distance' => 0,
                        'elevation' => 0,
                        'duration' => 0,
                    ]
                ];
            }
            return $monthStats;
        }
    }
    
    // Removed redundant methods calculateYearStatsFromDb and calculateMonthStatsFromDb
    // These methods were just wrappers for calculateStatsFromDb('year') and calculateStatsFromDb('month')
    
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
        $yearCacheKeySuffix = $userId . '-' . $this->year;
        
        // Invalidation sélective selon le type de données modifiées
        switch ($type) {
            case 'workout':
                // Invalide le cache des workouts et les statistiques
                Cache::forget(self::CACHE_KEY_WORKOUTS . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $yearCacheKeySuffix);
                \Illuminate\Support\Facades\Log::info("Cache workout invalidé");
                break;
                
            case 'activity':
                // Invalide le cache des activités et les statistiques
                Cache::forget(self::CACHE_KEY_ACTIVITIES . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $yearCacheKeySuffix);
                \Illuminate\Support\Facades\Log::info("Cache activity invalidé");
                break;
                
            case 'week':
                // Pas besoin d'invalider les semaines car elles sont maintenant stockées en DB
                \Illuminate\Support\Facades\Log::info("Week stats seront mis à jour directement");
                break;
                
            case 'all':
            default:
                // Invalide tous les caches liés à l'année courante
                Cache::forget(self::CACHE_KEY_ACTIVITIES . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_WORKOUTS . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $yearCacheKeySuffix);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $yearCacheKeySuffix);
                \Illuminate\Support\Facades\Log::info("Tous les caches invalidés");
                break;
        }
    }

    /**
     * Updates the type of a week.
     *
     * @param int|null $weekId The week identifier
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
        $this->invalidateCache('week');
        
        // Mise à jour de la semaine dans la collection actuelle
        $this->weeks = $this->weeks->map(function($w) use ($week, $weekTypeId) {
            if ($w->id === $week->id) {
                $w->week_type_id = $weekTypeId;
                $w->type = $weekTypeId ? WeekType::find($weekTypeId) : null;
            }
            return $w;
        });
        
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
            
            // Récupérer ou créer le jour pour la nouvelle date
            $day = $this->getOrCreateDayForDate($parsedDate);
            
            // Mettre à jour le workout
            $workout->day_id = $day->id;
            $workout->date = $parsedDate;
            $workout->save();
            
            // Mise à jour des statistiques des semaines concernées
            $this->updateWeekStats($workout->date);
            if ($workout->getOriginal('date')) {
                $this->updateWeekStats(Carbon::parse($workout->getOriginal('date')));
            }
            
            $this->dispatch('toast', __('calendar.messages.workout_moved', [
                'type' => $workout->type->getLocalizedName(),
                'date' => Carbon::parse($newDate)->translatedFormat(__('calendar.date_formats.full_date'))
            ]), 'success');
            
            // Émettre l'événement pour recharger les tooltips
            $this->dispatch('reload-tooltips');
        } catch (\Exception $e) {
            $this->dispatch('toast', __('calendar.messages.error_moving_workout', ['error' => $e->getMessage()]), 'error');
        }
    }
    
    // getOrCreateDayForDate method moved to CalendarHelpers trait
    
    /**
     * Updates the statistics of a week based on a date
     *
     * @param \Carbon\Carbon|\Carbon\CarbonImmutable $date The date
     * @return void
     */
    private function updateWeekStats($date)
    {
        try {
            $weekNumber = $date->weekOfYear;
            $year = $date->year;
            $userId = Auth::id();
            
            // Log debug information
            \Illuminate\Support\Facades\Log::info("Updating stats for week {$weekNumber} of {$year}");
            
            $week = Week::where('user_id', $userId)
                         ->where('year', $year)
                         ->where('week_number', $weekNumber)
                         ->first();
                         
            if (!$week) {
                \Illuminate\Support\Facades\Log::warning("Week not found for week {$weekNumber} of {$year}");
                return;
            }
            
            // Force reload days to ensure we have the latest data
            $week->load('days');
            
            // Get all day IDs for this week
            $dayIds = $week->days->pluck('id')->toArray();
            
            // Log the found days
            \Illuminate\Support\Facades\Log::info("Found " . count($dayIds) . " days for week {$weekNumber}");
            
            // Recalculate stats for this week only using direct queries on workout and activity tables
            $dayIds = $week->days->pluck('id')->toArray();
            
            $workoutStats = $this->queryStatsForRange('workout', ['day_id' => $dayIds]);
            $activityStats = $this->queryStatsForRange('activity', ['day_id' => $dayIds]);
            
            // Update dynamic attributes
            $week->actual_stats = [
                'distance' => $activityStats ? round($activityStats->dist / 1000, 1) : 0,
                'elevation' => $activityStats ? $activityStats->ele : 0,
                'duration' => $activityStats ? $activityStats->time : 0,
            ];
            
            $week->planned_stats = [
                'distance' => $workoutStats ? $workoutStats->dist : 0,
                'elevation' => $workoutStats ? $workoutStats->ele : 0,
                'duration' => $workoutStats ? $workoutStats->time * 60 : 0,
            ];
            
            // Save the week to persist any database-stored attributes
            $week->save();
            
            // Log success
            \Illuminate\Support\Facades\Log::info("Week stats updated: actual_distance={$week->actual_stats['distance']}, planned_distance={$week->planned_stats['distance']}");
            
            return $week;
        } catch (\Exception $e) {
            // Log any errors
            \Illuminate\Support\Facades\Log::error("Error in updateWeekStats: " . $e->getMessage());
            return null;
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
            
            // Récupérer ou créer le jour pour la nouvelle date
            $parsedDate = Carbon::parse($newDate);
            $day = $this->getOrCreateDayForDate($parsedDate);
            
            $newWorkout = $originalWorkout->replicate();
            $newWorkout->date = $parsedDate;
            $newWorkout->day_id = $day->id;
            $newWorkout->save();

            // Mise à jour des statistiques de la semaine concernée
            $this->updateWeekStats($parsedDate);
            
            $this->dispatch('toast', __('calendar.messages.workout_copied', [
                'type' => $originalWorkout->type->getLocalizedName(),
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
        // Récupérer les workouts mis à jour
        $this->workouts = $this->getWorkouts();
        
        // Mettre à jour les statistiques des semaines
        $this->refreshWeekStats();
        
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
        // Récupérer les activités mises à jour
        $this->activities = $this->getActivities();
        
        // Mettre à jour les statistiques des semaines
        $this->refreshWeekStats();
        
        $this->dispatch('reload-tooltips');
    }
    
    /**
     * Refreshes the weekly statistics for all weeks in the current year
     *
     * @return void
     */
    private function refreshWeekStats()
    {
        // Récupérer toutes les semaines pour l'année courante
        $weeks = $this->weeks;
        
        if ($weeks->isEmpty()) {
            return;
        }
        
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
        
        // Mettre à jour les statistiques de chaque semaine
        $this->weeks = $weeks->map(function($week) use ($activityStats, $workoutStats) {
            $weekNumber = $week->week_number;
            
            $week->actual_stats = [
                'distance' => isset($activityStats[$weekNumber]) ? round($activityStats[$weekNumber]->dist / 1000, 1) : 0,
                'elevation' => isset($activityStats[$weekNumber]) ? $activityStats[$weekNumber]->ele : 0,
                'duration' => isset($activityStats[$weekNumber]) ? $activityStats[$weekNumber]->time : 0,
            ];
            
            $week->planned_stats = [
                'distance' => isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->dist : 0,
                'elevation' => isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->ele : 0,
                'duration' => isset($workoutStats[$weekNumber]) ? $workoutStats[$weekNumber]->time * 60 : 0,
            ];
            
            return $week;
        });
        
        // Mettre à jour les statistiques mensuelles et annuelles
        $this->monthStats = $this->calculateStatsFromDb('month');
        $this->yearStats = $this->calculateStatsFromDb('year');
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

            if (isset($result['redirect']) && $result['redirect'] === true && isset($result['route'])) {
                // Use dispatch instead of trying to redirect directly
                $this->dispatch('redirectTo', ['route' => $result['route']]);
                return;
            }
            
            if ($result['success']) {
                if ($result['count'] > 0) {
                    $this->dispatch('toast', $result['message'], 'success');
                    // Récupérer les activités mises à jour et mettre à jour les statistiques
                    $this->activities = $this->getActivities();
                    $this->refreshWeekStats();
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
        
        // Mettre à jour les statistiques
        $this->refreshWeekStats();
        
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
        
        // Mettre à jour les statistiques
        $this->refreshWeekStats();
        
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
        
        // Mettre à jour les statistiques
        $this->refreshWeekStats();
        
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

        return $hours > 0 
            ? sprintf('%d:%02d:%02d', $hours, $minutes, $seconds) 
            : sprintf('%d:%02d', $minutes, $seconds);
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
                $weekTypeName = $week->type ? strtolower($week->type->getLocalizedName()) : '';
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
                    'comparedTo' => $comparisonWeek->type ? $comparisonWeek->type->getLocalizedName() : 'Previous Week',
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
                    'comparedTo' => $comparisonWeek->type ? $comparisonWeek->type->getLocalizedName() : 'Previous Week',
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
        } 
        
        if ($increase < 0) {
            return [
                'color' => 'text-amber-400',
                'icon' => 'fa-exclamation-circle',
                'status' => 'Significant Decrease',
                'message' => 'A significant decrease may affect training progression and consistency.'
            ];
        } 
        
        if ($increase == 0) {
            return [
                'color' => 'text-emerald-400',
                'icon' => 'fa-check-circle',
                'status' => 'No Change',
                'message' => 'No change is ideal for maintaining a steady training load.'
            ];
        }
        
        return [
            'color' => 'text-emerald-400',
            'icon' => 'fa-check-circle',
            'status' => 'Ideal Progression',
            'message' => 'A increase below +10% is recommended for safe progression.'
        ];
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
     * @param float|null $actual Actual value
     * @param float|null $planned Planned value
     * @return float Completion percentage (capped at 100%)
     */
    public function calculateCompletionPercentage($actual, $planned): float
    {
        $actual = (float)($actual ?? 0);
        $planned = (float)($planned ?? 0);
        
        return $planned <= 0 ? 0 : min(($actual / $planned) * 100, 100);
    }
    
    // ensureMonthsExist method removed since we no longer use months
    
    // createOrUpdateDays method moved to CalendarHelpers trait
    
    /**
     * Recompute stats and refresh after a workout is saved or edited
     *
     * @param string|\Carbon\Carbon $date The date of the workout
     * @return void
     */
    public function updateWeekStatsForDate($date)
    {
        try {
            // Parse the date if it's a string
            $carbonDate = $date instanceof \Carbon\Carbon ? $date : Carbon::parse($date);
            
            // Log debugging information
            \Illuminate\Support\Facades\Log::info("Updating week stats for date: " . $carbonDate->format('Y-m-d'));
            
            // Get the week number for this date
            $weekNumber = $carbonDate->weekOfYear;
            $year = $carbonDate->year;
            $userId = Auth::id();
            
            // Find the week
            $week = Week::where('user_id', $userId)
                         ->where('year', $year)
                         ->where('week_number', $weekNumber)
                         ->first();
            
            if (!$week) {
                \Illuminate\Support\Facades\Log::warning("Week not found for date " . $carbonDate->format('Y-m-d'));
                return;
            }
            
            // Reload the week with its days to ensure we have the latest data
            $week->load('days');
            
            // Calculate the stats using the Week model method
            $stats = $week->calculateStats();
            
            // Log the calculated stats
            \Illuminate\Support\Facades\Log::info("Week {$weekNumber} stats: planned distance=" . 
                                                $stats['planned_stats']['distance'] . 
                                                ", actual distance=" . $stats['actual_stats']['distance']);
            
            // Update the week object with the calculated stats
            $week->actual_stats = $stats['actual_stats'];
            $week->planned_stats = $stats['planned_stats'];
            
            // Invalidate cache
            $this->invalidateCache('workout');
            
            // Refresh the page to show updated stats
            $this->dispatch('refresh');
            
            // Log success
            \Illuminate\Support\Facades\Log::info("Week stats updated successfully");
        } catch (\Exception $e) {
            // Log any errors
            \Illuminate\Support\Facades\Log::error("Error updating week stats: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
    
    /**
     * Alias for updateWeekStatsForDate to maintain compatibility with existing code
     *
     * @param string $date The date in Y-m-d format
     * @return void
     */
    public function forceRefreshStatsFromDate($date)
    {
        $this->updateWeekStatsForDate($date);
    }
    
    /**
     * Helper method to get cached data or compute and cache it if not found
     * 
     * @param string $cacheKey The cache key prefix
     * @param int $ttl Cache time to live in seconds
     * @param callable $callback Function to compute the data if not cached
     * @return mixed The cached or computed data
     */
    private function getCachedData(string $cacheKey, int $ttl, callable $callback)
    {
        $userId = Auth::id();
        $fullCacheKey = $cacheKey . $userId . '-' . $this->year;
        
        return Cache::remember($fullCacheKey, $ttl, $callback);
    }
    
    /**
     * Helper method to update a week's statistics
     *
     * @param array $weekStats The statistics array to update
     * @param object $stats The stats source object with dist, ele, and time properties
     * @param bool $isActivity Whether the stats are from an activity (needs distance conversion)
     * @return array The updated statistics array
     */
    private function updateWeekStatsArray(array $weekStats, object $stats, bool $isActivity = false): array
    {
        if (!$stats) {
            return $weekStats;
        }
        
        $weekStats['distance'] += $isActivity ? round($stats->dist / 1000, 1) : $stats->dist;
        $weekStats['elevation'] += $stats->ele;
        $weekStats['duration'] += $isActivity ? $stats->time : ($stats->time * 60);
        
        return $weekStats;
    }
    
    /**
     * Helper method to initialize year data after year change
     *
     * @return void
     */
    private function setupYearData(): void
    {
        // Création complète de la structure calendrier pour l'année
        $this->initializeYearModel();
        
        // Initialiser la première semaine spécifiquement pour gérer les jours à cheval sur deux années
        $this->initializeFirstWeekOfYear($this->year);
        
        // Générer toutes les semaines de l'année
        $this->weeks = $this->getWeeks();
        
        // Réassocier les workouts aux jours corrects (important pour les jours à cheval sur deux années)
        $this->reassociateWorkoutsWithDays();
        
        // Invalider les caches pour forcer le rechargement des données
        $this->invalidateCache('all');
        
        // Mise à jour de l'URL et rechargement des tooltips
        $this->dispatch('update-url', year: $this->year);
        $this->dispatch('reload-tooltips');
        
        // Envoi direct de l'événement de fin de navigation après un court délai
        // pour permettre au DOM de se mettre à jour
        $this->dispatch('year-navigation-end');
    }
    
    /**
     * Réassocier les workouts aux jours corrects, particulièrement utile pour les jours
     * à cheval sur deux années (ex: 30-31 décembre 2024 dans la première semaine de 2025)
     * 
     * @return void
     */
    private function reassociateWorkoutsWithDays()
    {
        // Déterminer la première semaine de l'année
        $firstWeekStart = Carbon::createFromDate($this->year, 1, 1)->startOfWeek(Carbon::MONDAY);
        $yearStart = Carbon::createFromDate($this->year, 1, 1);
        
        // Si la première semaine commence en décembre de l'année précédente
        if ($firstWeekStart->year < $this->year) {
            // S'assurer que les workouts de la fin de l'année précédente sont correctement associés aux jours
            $this->ensureWorkoutsHaveCorrectDays($firstWeekStart, $yearStart->subDay());
        }
        
        // Également vérifier tous les workouts de l'année en cours
        $yearEnd = Carbon::createFromDate($this->year, 12, 31);
        $this->ensureWorkoutsHaveCorrectDays($yearStart, $yearEnd);
    }
    
    /**
     * Recharge les workouts de la première semaine de l'année, y compris ceux de l'année précédente
     * Cette méthode est utile pour s'assurer que les workouts du 30-31 décembre
     * qui appartiennent à la première semaine de l'année suivante sont bien affichés
     *
     * @return void
     */
    private function reloadFirstWeekWorkouts()
    {
        // Déterminer les dates de la première semaine
        $firstWeekStart = Carbon::createFromDate($this->year, 1, 1)->startOfWeek(Carbon::MONDAY);
        $firstWeekEnd = $firstWeekStart->copy()->endOfWeek(Carbon::SUNDAY);
        
        // Si la première semaine commence en décembre de l'année précédente
        if ($firstWeekStart->year < $this->year) {
            // Récupérer tous les workouts de cette période
            $firstWeekWorkouts = Workout::with(['type', 'day'])
                ->where('user_id', Auth::id())
                ->whereBetween('date', [$firstWeekStart, $firstWeekEnd])
                ->get();
            
            // Ajouter ces workouts à la collection existante
            // Mais d'abord, filtrer les workouts existants pour éviter les doublons
            if ($this->workouts) {
                $existingIds = $this->workouts->pluck('id')->toArray();
                $newWorkouts = $firstWeekWorkouts->filter(function($workout) use ($existingIds) {
                    return !in_array($workout->id, $existingIds);
                });
                
                if ($newWorkouts->isNotEmpty()) {
                    $this->workouts = $this->workouts->merge($newWorkouts);
                }
            }
        }
    }
}
