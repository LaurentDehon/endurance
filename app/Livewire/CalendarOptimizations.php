<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;

/**
 * Trait pour optimiser les performances du calendrier
 * Contient les méthodes optimisées pour réduire la latence
 */
trait CalendarOptimizations
{
    /**
     * Rafraîchit seulement les workouts d'une semaine spécifique
     * au lieu de recharger tous les workouts de l'année
     * 
     * @param string $date Date du workout modifié
     * @return void
     */
    public function refreshWorkoutsOptimized($date = null)
    {
        if (!$date) {
            // Fallback sur le comportement original si pas de date
            $this->workouts = $this->getWorkouts();
            return;
        }

        $workoutDate = Carbon::parse($date);
        
        // Trouver la semaine concernée par la modification
        $weekStart = $workoutDate->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $workoutDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        // Récupérer seulement les workouts de cette semaine
        $weekWorkouts = Workout::where('user_id', Auth::id())
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->with(['type', 'day'])
            ->get();
        
        // Mettre à jour seulement les workouts de cette semaine dans la collection
        $this->workouts = $this->workouts->filter(function($workout) use ($weekStart, $weekEnd) {
            $workoutDate = Carbon::parse($workout->date);
            return !($workoutDate->between($weekStart, $weekEnd));
        })->merge($weekWorkouts);
    }

    /**
     * Met à jour seulement les statistiques de la semaine affectée
     * au lieu de recalculer toutes les semaines de l'année
     * 
     * @param string $date Date du workout modifié
     * @return void
     */
    public function updateWeekStatsOptimized($date)
    {
        $workoutDate = Carbon::parse($date);
        
        // Trouver la semaine ISO concernée
        $year = $workoutDate->year;
        $weekNumber = $workoutDate->weekOfYear;
        
        // Mettre à jour seulement cette semaine spécifique
        $week = $this->weeks->firstWhere('week_number', $weekNumber);
        if ($week) {
            $week->calculateStats();
            
            // Émettre un événement spécifique pour cette semaine
            $this->dispatch('week-stats-updated', [
                'weekNumber' => $weekNumber,
                'stats' => $week->toArray()
            ]);
        }
    }

    /**
     * Invalidation sélective du cache
     * au lieu d'invalider tous les caches
     * 
     * @param string $date Date du workout modifié
     * @return void
     */
    public function invalidateCacheOptimized($date)
    {
        $workoutDate = Carbon::parse($date);
        $userId = Auth::id();
        
        // Invalider seulement les caches concernés par cette date
        $cachesToInvalidate = [
            "workout_stats_{$userId}_{$workoutDate->year}_{$workoutDate->month}",
            "week_stats_{$userId}_{$workoutDate->year}_{$workoutDate->weekOfYear}",
            "day_stats_{$userId}_{$workoutDate->format('Y-m-d')}"
        ];
        
        foreach ($cachesToInvalidate as $cacheKey) {
            cache()->forget($cacheKey);
        }
    }

    /**
     * Recharge seulement les tooltips des éléments modifiés
     * au lieu de détruire et recréer tous les tooltips
     * 
     * @param string $date Date du workout modifié
     * @return void
     */
    public function reloadTooltipsOptimized($date)
    {
        $workoutDate = Carbon::parse($date);
        
        // Émettre un événement spécifique avec la date pour un rafraîchissement ciblé
        $this->dispatch('reload-tooltips-selective', [
            'date' => $workoutDate->format('Y-m-d'),
            'weekNumber' => $workoutDate->weekOfYear
        ]);
    }

    /**
     * Méthode optimisée principale appelée lors des événements workout
     * Remplace les appels individuels par un seul appel optimisé
     * 
     * @param string|null $date Date du workout modifié
     * @return void
     */
    public function refreshOptimized($date = null)
    {
        if (!$date) {
            // Fallback sur le comportement original pour les cas sans date
            $this->refreshWorkouts();
            $this->refreshWeekStats();
            $this->invalidateCache();
            $this->dispatch('reload-tooltips');
            return;
        }

        // Optimisations ciblées
        $this->refreshWorkoutsOptimized($date);
        $this->updateWeekStatsOptimized($date);
        $this->invalidateCacheOptimized($date);
        $this->reloadTooltipsOptimized($date);
        
        // Log pour mesurer l'amélioration (à retirer en production)
        logger('Optimized refresh completed for date: ' . $date);
    }
}
