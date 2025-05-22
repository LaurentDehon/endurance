<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Week;
use App\Models\Year;
use App\Models\Workout;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;

trait CalendarHelpers {
    /**
     * Gets or creates a Day model for a specific date without using months
     *
     * @param Carbon|\Carbon\CarbonImmutable|string $date The date
     * @return Day
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
        
        // Obtenir l'année pour ce jour
        $year = Year::firstOrCreate(
            ['user_id' => Auth::id(), 'year' => $date->year]
        );
        
        // Obtenir la semaine pour ce jour
        $weekNumber = $date->weekOfYear;
        $week = Week::firstOrCreate(
            ['user_id' => Auth::id(), 'year' => $date->year, 'week_number' => $weekNumber],
            [
                'user_id' => Auth::id(),
                'year' => $date->year,
                'week_number' => $weekNumber, 
                'year_id' => $year->id
            ]
        );
        
        // Créer le jour en utilisant firstOrCreate pour éviter les violations de contrainte d'unicité
        $day = Day::firstOrCreate(
            ['date' => $dateString],
            [
                'year_id' => $year->id,
                'week_id' => $week->id,
                'date' => $dateString
            ]
        );
        
        // Si le jour existe mais avec des relations différentes, mettre à jour ses relations
        if ($day->year_id !== $year->id || $day->week_id !== $week->id) {
            $day->year_id = $year->id;
            $day->week_id = $week->id;
            $day->save();
        }
        
        return $day;
    }
    
    /**
     * Creates or updates days for a week without using months
     *
     * @param Week $week The week model
     * @param CarbonInterface $start Start date of the week
     * @param CarbonInterface $end End date of the week
     * @return void
     */
    private function createOrUpdateDays(Week $week, CarbonInterface $start, CarbonInterface $end)
    {
        $startMutable = $start instanceof CarbonImmutable ? $start->toMutable() : $start->copy();
        
        // Récupérer les jours existants pour cette semaine
        $existingDays = $week->days()->get()->keyBy(function($day) {
            return $day->date->format('Y-m-d');
        });
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startMutable->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            
            // Récupérer l'année pour ce jour
            $yearId = $date->year == $this->year ? $this->yearModel->id : null;
            
            // Si le jour n'est pas dans l'année courante, il faut récupérer l'année correcte
            if (!$yearId) {
                $yearModel = Year::firstOrCreate(
                    ['user_id' => Auth::id(), 'year' => $date->year]
                );
                $yearId = $yearModel->id;
            }
            
            // Vérifier si le jour existe déjà dans cette semaine
            if (!$existingDays->has($dateString)) {
                // Utiliser firstOrCreate pour éviter les violations de contrainte d'unicité
                // si le jour existe déjà dans une autre semaine
                $day = Day::firstOrCreate(
                    ['date' => $dateString],
                    [
                        'week_id' => $week->id,
                        'year_id' => $yearId,
                        'date' => $date
                    ]
                );
                
                // Si le jour existe mais est associé à une autre semaine, le mettre à jour
                if ($day->week_id !== $week->id) {
                    $day->week_id = $week->id;
                    $day->year_id = $yearId;
                    $day->save();
                }
            }
        }
    }
    
    /**
     * S'assure que les workouts sont correctement associés aux jours correspondants
     * Cette méthode est utile pour corriger les problèmes avec les workouts qui sont
     * à la frontière entre deux années (ex: 30-31 décembre dans la première semaine de janvier)
     *
     * @param Carbon|\Carbon\CarbonImmutable|string $startDate Date de début
     * @param Carbon|\Carbon\CarbonImmutable|string $endDate Date de fin
     * @return void
     */
    private function ensureWorkoutsHaveCorrectDays($startDate, $endDate)
    {
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }
        
        // Récupérer tous les workouts dans la plage de dates spécifiée
        $workouts = Workout::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
            
        foreach ($workouts as $workout) {
            // Vérifier si le workout a un jour associé
            if (!$workout->day_id) {
                $day = $this->getOrCreateDayForDate($workout->date);
                $workout->day_id = $day->id;
                $workout->save();
            } else {
                // Vérifier si le jour associé correspond à la date du workout
                $day = Day::find($workout->day_id);
                if (!$day || !$day->date->isSameDay($workout->date)) {
                    $correctDay = $this->getOrCreateDayForDate($workout->date);
                    $workout->day_id = $correctDay->id;
                    $workout->save();
                }
            }
        }
    }
    
    /**
     * Initialise la première semaine de l'année et s'assure que tous les jours
     * sont correctement créés, y compris ceux de l'année précédente si nécessaire
     *
     * @param int $year L'année à initialiser
     * @return Week La première semaine de l'année
     */
    private function initializeFirstWeekOfYear(int $year)
    {
        $userId = Auth::id();
        
        // Obtenir les dates de début et de fin de la première semaine
        $firstWeekStart = Carbon::create($year, 1, 1)->startOfWeek(Carbon::MONDAY);
        $firstWeekEnd = $firstWeekStart->copy()->endOfWeek(Carbon::SUNDAY);
        $weekNumber = $firstWeekStart->weekOfYear;
        
        // Obtenir ou créer l'année
        $yearModel = Year::firstOrCreate(
            ['user_id' => $userId, 'year' => $year]
        );
        
        // Obtenir ou créer la semaine
        $week = Week::firstOrCreate(
            ['user_id' => $userId, 'year' => $year, 'week_number' => $weekNumber],
            [
                'user_id' => $userId,
                'year' => $year,
                'week_number' => $weekNumber,
                'year_id' => $yearModel->id
            ]
        );
        
        // Créer tous les jours de la semaine
        $this->createOrUpdateDays($week, $firstWeekStart, $firstWeekEnd);
        
        // Si la première semaine commence en décembre de l'année précédente
        if ($firstWeekStart->year < $year) {
            // S'assurer que les workouts de la fin de l'année précédente sont correctement associés aux jours
            $this->ensureWorkoutsHaveCorrectDays($firstWeekStart, Carbon::create($year, 1, 1)->subDay());
        }
        
        return $week;
    }
    
    /**
     * Calcule la progression entre la semaine courante et une semaine précédente valide avec des données planifiées.
     * On cherche la semaine précédente (avec des données planifiées) qui n'est pas "recovery" ou "race".
     * Pour les semaines "reduced", on calcule quand même leur progression par rapport à la semaine précédente,
     * mais on ne les prend pas en compte pour calculer la progression des semaines suivantes.
     * Si une semaine valide n'est pas trouvée immédiatement, on continue à remonter jusqu'à trouver une semaine valide.
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

            // Types de semaines à ignorer complètement pour le calcul de progression
            $ignoredWeekTypes = ['recovery', 'race'];
            
            // Types de semaines à ne pas considérer comme référence pour les semaines suivantes,
            // mais pour lesquelles on calcule quand même leur propre progression
            $skipAsReferenceTypes = ['reduced'];
            
            // Si la semaine courante est de type à ignorer complètement, ne pas calculer de progression
            $currentWeekTypeName = $currentWeek->type ? strtolower($currentWeek->type->name) : '';
            if (in_array($currentWeekTypeName, $ignoredWeekTypes)) {
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
            
            // Trouver la première semaine précédente valide selon les règles suivantes:
            // 1. Si la semaine courante est "reduced", on prend la semaine précédente sans restrictions de type
            // 2. Sinon, on cherche une semaine qui n'est ni "reduced", ni "recovery", ni "race"
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
                
                // Si la semaine courante est "reduced", on compare avec la semaine précédente quelle qu'elle soit
                // (sauf si elle est dans les types complètement ignorés)
                if (in_array($currentWeekTypeName, $skipAsReferenceTypes)) {
                    if (!in_array($weekTypeName, $ignoredWeekTypes)) {
                        $comparisonWeek = $week;
                        break;
                    }
                } 
                // Sinon, on cherche une semaine qui n'est ni "reduced", ni "recovery", ni "race"
                else {
                    $typesToSkip = array_merge($ignoredWeekTypes, $skipAsReferenceTypes);
                    if (!in_array($weekTypeName, $typesToSkip)) {
                        $comparisonWeek = $week;
                        break;
                    }
                }
            }

            // Si aucune semaine valide n'a été trouvée, essayer de trouver une semaine quelconque avec des données
            if (!$comparisonWeek) {
                foreach ($previousWeeks as $week) {
                    $hasPlannedData = $week->planned_stats['distance'] > 0 || $week->planned_stats['duration'] > 0;
                    $weekTypeName = $week->type ? strtolower($week->type->name) : '';
                    
                    if ($hasPlannedData && !in_array($weekTypeName, $ignoredWeekTypes)) {
                        $comparisonWeek = $week;
                        break;
                    }
                }
            }

            // Si toujours aucune semaine valide n'a été trouvée
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
}
