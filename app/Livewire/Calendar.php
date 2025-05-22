<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Week;
use App\Models\Year;
use App\Models\Month;
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
            ['user_id' => $userId, 'year' => $this->year],
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
        $userId = Auth::id();
        // Cache pour les années disponibles (longue durée)
        $years = Cache::remember(
            self::CACHE_KEY_YEARS . $userId, 
            self::CACHE_TTL_YEARS, 
            fn() => $this->getAvailableYears()
        );

        // Assurez-vous que l'année est correctement initialisée
        if (!$this->yearModel) {
            $this->initializeYearModel();
        }

        // Récupérer l'année, les mois, les semaines et les jours via Eloquent avec les relations nécessaires
        $yearModel = $this->yearModel->fresh(['months.weeks.days', 'months.days']);
        $months = $yearModel->months->sortBy('month');
        
        // Récupérer les semaines avec leurs jours et types
        $weeks = $yearModel->weeks()->with(['type', 'days'])->get()->sortBy('week_number');
        
        // Parcourir chaque semaine et calculer ses statistiques actuelles
        foreach ($weeks as $week) {
            $stats = $week->calculateStats();
            $week->actual_stats = $stats['actual_stats'];
            $week->planned_stats = $stats['planned_stats'];
        }
        
        $activities = $yearModel->activities()->with('day')->get();
        $workouts = $yearModel->workouts()->with(['type', 'day'])->get();

        // Group weeks by month (key = YYYY-MM)
        $monthsGrouped = $months->mapWithKeys(function($month) use ($weeks) {
            $monthKey = sprintf('%04d-%02d', $month->year->year, $month->month);
            $weeksInMonth = $weeks->filter(function($week) use ($month) {
                return $week->month_id === $month->id;
            });
            return [$monthKey => $weeksInMonth];
        });

        // Calculer les stats mensuelles et annuelles (en DB ou via helpers existants)
        $this->monthStats = Cache::remember(
            self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year, 
            self::CACHE_TTL_STATS, 
            fn() => $this->calculateMonthStatsFromDb()
        );
        $this->yearStats = Cache::remember(
            self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year, 
            self::CACHE_TTL_STATS, 
            fn() => $this->calculateYearStatsFromDb()
        );

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
        // Initialize months collection for the current year
        $months = Month::where('year_id', $this->yearModel->id)->get()->keyBy('month');

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
            $lastWeekPrev = $lastWeekStart->subWeek();
            $lastWeekNumber = $lastWeekPrev->weekOfYear;
        }

        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = $date->setISODate($this->year, $weekNumber)->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
            
            // Sortir de la boucle si on dépasse la dernière semaine de l'année
            if ($start->year > $this->year && $weekNumber > 1) 
                break;
                
            $end = $start->endOfWeek(\Carbon\CarbonImmutable::SUNDAY);
            $thursday = $start->addDays(3);  // Le jeudi détermine l'année à laquelle appartient la semaine ISO

            // Récupérer le Week de la collection, sinon en créer un nouveau et le sauvegarder
            $week = $weeksFromDb->get($weekNumber);
            if (!$week) {
                $week = new Week([
                    'year' => $this->year,
                    'week_number' => $weekNumber,
                    'user_id' => Auth::id(),
                    'week_type_id' => null
                ]);
                // Sauvegarder pour obtenir un ID
                $week->save();
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

            // Déterminer le mois auquel appartient cette semaine (selon le jeudi)
            $monthNumber = $thursday->month;
            
            // Récupérer ou créer le mois si nécessaire
            $month = $months->get($monthNumber);
            if (!$month) {
                $month = Month::create([
                    'year_id' => $this->yearModel->id,
                    'month' => $monthNumber
                ]);
                $months->put($monthNumber, $month);
            }
            
            // Récupérer la semaine de la collection, sinon en créer une nouvelle
            $week = $weeksFromDb->get($weekNumber);
            if (!$week) {
                $week = new Week([
                    'year' => $this->year,
                    'week_number' => $weekNumber,
                    'user_id' => Auth::id(),
                    'month_id' => $month->id,
                    'week_type_id' => null
                ]);
                // Sauvegarder pour obtenir un ID
                $week->save();
                $week->setRelation('type', null);
                $weeksFromDb->put($weekNumber, $week);
            } else {
                // S'assurer que la semaine est associée au bon mois
                if ($week->month_id !== $month->id) {
                    $week->month_id = $month->id;
                    $week->save();
                }
            }
            
            // Créer ou mettre à jour les jours de la semaine
            $this->createOrUpdateDays($week, $start, $end);

            $week->start = $start->translatedFormat(__('calendar.date_formats.day_month'));
            $week->end = $end->translatedFormat(__('calendar.date_formats.day_month'));
            $week->month_key = $thursday->format('Y-m'); // Use a new property for grouping if needed
            $week->actual_stats = $weekActualStats;
            $week->planned_stats = $weekPlannedStats;
            $week->computed_days = $this->generateWeekDays($start); // Use a local property for computed days
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
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year);
                \Illuminate\Support\Facades\Log::info("Cache workout invalidé");
                break;
                
            case 'activity':
                // Invalide le cache des activités
                Cache::forget(self::CACHE_KEY_ACTIVITIES . $userId . '-' . $this->year);
                // Invalide les stats mensuelles et annuelles
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year);
                \Illuminate\Support\Facades\Log::info("Cache activity invalidé");
                break;
                
            case 'week':
                // Pas besoin d'invalider les semaines car elles sont maintenant stockées en DB
                // et mises à jour individuellement avec refreshWeekStats()
                \Illuminate\Support\Facades\Log::info("Week stats seront mis à jour directement");
                break;
                
            case 'all':
            default:
                // Invalide tous les caches liés à l'année courante
                Cache::forget(self::CACHE_KEY_ACTIVITIES . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_WORKOUTS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_MONTH_STATS . $userId . '-' . $this->year);
                Cache::forget(self::CACHE_KEY_YEAR_STATS . $userId . '-' . $this->year);
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
    
    /**
     * Gets or creates a Day model for a specific date
     *
     * @param \Carbon\Carbon|\Carbon\CarbonImmutable|string $date The date
     * @return \App\Models\Day
     */
    private function getOrCreateDayForDate($date)
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        $dateString = $date->format('Y-m-d');
        
        // Vérifier si le jour existe déjà
        $day = Day::where('date', $dateString)->first();
        
        if ($day) {
            return $day;
        }
        
        // Obtenir l'année et le mois pour ce jour
        $year = Year::firstOrCreate(
            ['user_id' => Auth::id(), 'year' => $date->year],
            ['user_id' => Auth::id(), 'year' => $date->year]
        );
        
        $month = Month::firstOrCreate(
            ['year_id' => $year->id, 'month' => $date->month],
            ['year_id' => $year->id, 'month' => $date->month]
        );
        
        // Obtenir la semaine pour ce jour
        $weekNumber = $date->weekOfYear;
        $week = Week::firstOrCreate(
            ['user_id' => Auth::id(), 'year' => $date->year, 'week_number' => $weekNumber],
            ['user_id' => Auth::id(), 'year' => $date->year, 'week_number' => $weekNumber, 'month_id' => $month->id]
        );
        
        // Créer le jour
        $day = Day::create([
            'month_id' => $month->id,
            'week_id' => $week->id,
            'date' => $dateString
        ]);
        
        return $day;
    }
    
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
            $workoutStats = Workout::selectRaw('SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
                ->where('user_id', $userId)
                ->whereIn('day_id', $dayIds)
                ->first();
            
            $activityStats = Activity::selectRaw('SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
                ->where('user_id', $userId)
                ->whereIn('day_id', $dayIds)
                ->first();
            
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
        $this->monthStats = $this->calculateMonthStatsFromDb();
        $this->yearStats = $this->calculateYearStatsFromDb();
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

            if (isset($result['redirect']) && $result['redirect'] === true) {
                return redirect(route($result['route']));
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
     * @param float|null $actual Actual value
     * @param float|null $planned Planned value
     * @return float Completion percentage (capped at 100%)
     */
    public function calculateCompletionPercentage($actual, $planned): float
    {
        // Convert null values to 0
        $actual = is_null($actual) ? 0.0 : (float)$actual;
        $planned = is_null($planned) ? 0.0 : (float)$planned;
        
        if ($planned <= 0) {
            return 0;
        }
        
        return min(($actual / $planned) * 100, 100);
    }
    
    /**
     * Gets or creates all needed weeks for the current year using the Week model.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getOrCreateWeeks(): \Illuminate\Support\Collection
    {
        $userId = Auth::id();
        $year = $this->year;
        
        // Initialize months collection for the current year
        $months = Month::where('year_id', $this->yearModel->id)->get()->keyBy('month');
        
        // Récupère ou crée les 53 semaines possibles pour l'année
        $date = \Carbon\CarbonImmutable::create($year, 1, 1)->startOfYear();
        
        // Première et dernière semaine de l'année
        $firstWeekStart = $date->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
        $lastWeekStart = $date->endOfYear()->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
        $lastWeekNumber = $lastWeekStart->weekOfYear;

        // Ajuster pour les années où la dernière semaine est la semaine 1 de l'année suivante
        if ($lastWeekStart->weekOfYear === 1) {
            $lastWeekPrev = $lastWeekStart->subWeek();
            $lastWeekNumber = $lastWeekPrev->weekOfYear;
        }
        
        // Récupérer toutes les semaines existantes pour cette année et cet utilisateur
        $weeksFromDb = Week::with('type')
            ->where('user_id', $userId)
            ->where('year', $year)
            ->get()
            ->keyBy('week_number');
            
        // Récupérer les stats groupées par semaine pour les activités réelles
        $activityStats = Activity::selectRaw('WEEK(start_date, 1) as week, SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->whereYear('start_date', $year)
            ->where('user_id', $userId)
            ->groupBy('week')
            ->get()
            ->keyBy('week');

        // Récupérer les stats groupées par semaine pour les workouts planifiés
        $workoutStats = Workout::selectRaw('WEEK(date, 1) as week, SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->whereYear('date', $year)
            ->where('user_id', $userId)
            ->groupBy('week')
            ->get()
            ->keyBy('week');
            
        $weeks = collect();
        
        for ($weekNumber = 1; $weekNumber <= 53; $weekNumber++) {
            $start = $date->setISODate($year, $weekNumber)->startOfWeek(\Carbon\CarbonImmutable::MONDAY);
            
            // Sortir de la boucle si on dépasse la dernière semaine de l'année
            if ($start->year > $year && $weekNumber > 1) 
                break;
                
            $end = $start->endOfWeek(\Carbon\CarbonImmutable::SUNDAY);
            $thursday = $start->addDays(3);  // Le jeudi détermine l'année à laquelle appartient la semaine ISO

            // Récupérer le Week de la collection, sinon en créer un nouveau et le sauvegarder
            $week = $weeksFromDb->get($weekNumber);
            if (!$week) {
                $week = new Week([
                    'year' => $year,
                    'week_number' => $weekNumber,
                    'user_id' => $userId,
                    'week_type_id' => null
                ]);
                // Sauvegarder pour obtenir un ID
                $week->save();
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
            if ($weekNumber === 1 && $start->year < $year) {
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
            if ($weekNumber === $lastWeekNumber && $end->year > $year) {
                // Récupérer les statistiques de la première semaine de l'année suivante
                $firstWeekNextYearStats = $this->getWeekStatsFromRange($end->copy()->startOfYear(), $end);
                $weekActualStats['distance'] += round($firstWeekNextYearStats['actual']['distance'] / 1000, 1);
                $weekActualStats['elevation'] += $firstWeekNextYearStats['actual']['elevation'];
                $weekActualStats['duration'] += $firstWeekNextYearStats['actual']['duration'];
                
                $weekPlannedStats['distance'] += $firstWeekNextYearStats['planned']['distance'];
                $weekPlannedStats['elevation'] += $firstWeekNextYearStats['planned']['elevation'];
                $weekPlannedStats['duration'] += $firstWeekNextYearStats['planned']['duration'] * 60;
            }

            // Déterminer le mois auquel appartient cette semaine (selon le jeudi)
            $monthNumber = $thursday->month;
            
            // Récupérer ou créer le mois si nécessaire
            $month = $months->get($monthNumber);
            if (!$month) {
                $month = Month::create([
                    'year_id' => $this->yearModel->id,
                    'month' => $monthNumber
                ]);
                $months->put($monthNumber, $month);
            }
            
            // Récupérer la semaine de la collection, sinon en créer une nouvelle
            $week = $weeksFromDb->get($weekNumber);
            if (!$week) {
                $week = new Week([
                    'year' => $this->year,
                    'week_number' => $weekNumber,
                    'user_id' => $userId,
                    'month_id' => $month->id,
                    'week_type_id' => null
                ]);
                // Sauvegarder pour obtenir un ID
                $week->save();
                $week->setRelation('type', null);
                $weeksFromDb->put($weekNumber, $week);
            } else {
                // S'assurer que la semaine est associée au bon mois
                if ($week->month_id !== $month->id) {
                    $week->month_id = $month->id;
                    $week->save();
                }
            }
            
            // Créer ou mettre à jour les jours de la semaine
            $this->createOrUpdateDays($week, $start, $end);

            $week->start = $start->translatedFormat(__('calendar.date_formats.day_month'));
            $week->end = $end->translatedFormat(__('calendar.date_formats.day_month'));
            $week->month_key = $thursday->format('Y-m'); // Use a new property for grouping if needed
            $week->actual_stats = $weekActualStats;
            $week->planned_stats = $weekPlannedStats;
            $week->computed_days = $this->generateWeekDays($start); // Use a local property for computed days
            $week->is_current_week = $this->isCurrentWeek($start, $end);

            $weeks->push($week);
        }

        return $weeks;
    }

    /**
     * Creates or updates days for a week
     *
     * @param Week $week The week model
     * @param \Carbon\CarbonInterface $start Start date of the week
     * @param \Carbon\CarbonInterface $end End date of the week
     * @return void
     */
    private function createOrUpdateDays(Week $week, \Carbon\CarbonInterface $start, \Carbon\CarbonInterface $end)
    {
        $startMutable = $start instanceof \Carbon\CarbonImmutable ? $start->toMutable() : $start->copy();
        
        // Récupérer les jours existants pour cette semaine
        $existingDays = $week->days()->get()->keyBy(function($day) {
            return $day->date->format('Y-m-d');
        });
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startMutable->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            
            // Récupérer le mois pour ce jour
            $monthNumber = $date->month;
            $yearId = $date->year == $this->year ? $this->yearModel->id : null;
            
            // Si le jour n'est pas dans l'année courante, il faut récupérer l'année correcte
            if (!$yearId) {
                $yearModel = Year::firstOrCreate(
                    ['user_id' => Auth::id(), 'year' => $date->year],
                    ['user_id' => Auth::id(), 'year' => $date->year]
                );
                $yearId = $yearModel->id;
            }
            
            $month = Month::firstOrCreate(
                ['year_id' => $yearId, 'month' => $monthNumber],
                ['year_id' => $yearId, 'month' => $monthNumber]
            );
            
            // Vérifier si le jour existe déjà
            if (!$existingDays->has($dateString)) {
                // Créer le jour s'il n'existe pas
                Day::create([
                    'week_id' => $week->id,
                    'month_id' => $month->id,
                    'date' => $date
                ]);
            }
        }
    }
    
    /**
     * Livewire event handler: update only the affected week's stats after a workout is saved/edited
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
     * Recompute stats and refresh after a workout is saved
     *
     * @param string $date The date in Y-m-d format
     * @return void
     */
    public function forceRefreshStatsFromDate($date)
    {
        // Convert string date to Carbon instance
        $carbonDate = Carbon::parse($date);
        
        // Get the week number for this date
        $weekNumber = $carbonDate->weekOfYear;
        $year = $carbonDate->year;
        $userId = Auth::id();
        
        // Log for debugging
        \Illuminate\Support\Facades\Log::info("Force refreshing stats for week {$weekNumber} of {$year} from date {$date}");
        
        // Find the week
        $week = Week::where('user_id', $userId)
                     ->where('year', $year)
                     ->where('week_number', $weekNumber)
                     ->first();
        
        if (!$week) {
            \Illuminate\Support\Facades\Log::warning("Week not found for date {$date}");
            return;
        }
        
        // Get all days for this week
        $dayIds = Day::where('week_id', $week->id)->pluck('id')->toArray();
        
        // Direct query to sum workout stats for this week's days
        $workoutStats = Workout::selectRaw('SUM(distance) as dist, SUM(elevation) as ele, SUM(duration) as time')
            ->where('user_id', $userId)
            ->whereIn('day_id', $dayIds)
            ->first();
        
        // Direct query to sum activity stats for this week's days
        $activityStats = Activity::selectRaw('SUM(distance) as dist, SUM(total_elevation_gain) as ele, SUM(moving_time) as time')
            ->where('user_id', $userId)
            ->whereIn('day_id', $dayIds)
            ->first();
        
        // Log the calculated stats
        \Illuminate\Support\Facades\Log::info("Week {$weekNumber} stats: " . 
                                            "Workout dist=" . ($workoutStats ? $workoutStats->dist : 0) . 
                                            ", Activity dist=" . ($activityStats ? $activityStats->dist : 0));
        
        // Update the week model with new stats
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
        
        // Force a full refresh of the component
        $this->dispatch('refresh');
    }
}
