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
    

}
