<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Service optimisé pour la gestion du cache des workouts
 * Implémente un système de cache intelligent avec invalidation ciblée
 */
class OptimizedCacheService
{
    /** @var int Durée de cache par défaut (24 heures) */
    const DEFAULT_CACHE_TTL = 24 * 60 * 60;
    
    /** @var int Durée de cache pour les statistiques mensuelles (1 semaine) */
    const STATS_CACHE_TTL = 7 * 24 * 60 * 60;

    /**
     * Génère une clé de cache cohérente
     * 
     * @param string $type Type de cache (workout_stats, week_stats, etc.)
     * @param array $params Paramètres pour la clé
     * @return string
     */
    private static function generateCacheKey(string $type, array $params): string
    {
        $userId = Auth::id();
        $keyParts = array_merge([$type, $userId], $params);
        return implode('_', $keyParts);
    }

    /**
     * Met en cache les statistiques d'une semaine spécifique
     * 
     * @param int $year Année
     * @param int $weekNumber Numéro de semaine
     * @param array $stats Statistiques à cacher
     * @return void
     */
    public static function cacheWeekStats(int $year, int $weekNumber, array $stats): void
    {
        $key = self::generateCacheKey('week_stats', [$year, $weekNumber]);
        Cache::put($key, $stats, self::DEFAULT_CACHE_TTL);
    }

    /**
     * Récupère les statistiques d'une semaine depuis le cache
     * 
     * @param int $year Année
     * @param int $weekNumber Numéro de semaine
     * @return array|null
     */
    public static function getWeekStats(int $year, int $weekNumber): ?array
    {
        $key = self::generateCacheKey('week_stats', [$year, $weekNumber]);
        return Cache::get($key);
    }

    /**
     * Met en cache les workouts d'une période spécifique
     * 
     * @param Carbon $startDate Date de début
     * @param Carbon $endDate Date de fin
     * @param \Illuminate\Database\Eloquent\Collection $workouts Collection de workouts
     * @return void
     */
    public static function cacheWorkoutsPeriod(Carbon $startDate, Carbon $endDate, $workouts): void
    {
        $key = self::generateCacheKey('workouts_period', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ]);
        Cache::put($key, $workouts, self::DEFAULT_CACHE_TTL);
    }

    /**
     * Récupère les workouts d'une période depuis le cache
     * 
     * @param Carbon $startDate Date de début
     * @param Carbon $endDate Date de fin
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public static function getWorkoutsPeriod(Carbon $startDate, Carbon $endDate)
    {
        $key = self::generateCacheKey('workouts_period', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ]);
        return Cache::get($key);
    }

    /**
     * Met en cache les statistiques mensuelles
     * 
     * @param int $year Année
     * @param int $month Mois
     * @param array $stats Statistiques
     * @return void
     */
    public static function cacheMonthStats(int $year, int $month, array $stats): void
    {
        $key = self::generateCacheKey('month_stats', [$year, $month]);
        Cache::put($key, $stats, self::STATS_CACHE_TTL);
    }

    /**
     * Récupère les statistiques mensuelles depuis le cache
     * 
     * @param int $year Année
     * @param int $month Mois
     * @return array|null
     */
    public static function getMonthStats(int $year, int $month): ?array
    {
        $key = self::generateCacheKey('month_stats', [$year, $month]);
        return Cache::get($key);
    }

    /**
     * Invalide sélectivement le cache basé sur la date d'un workout
     * 
     * @param string $date Date du workout modifié (Y-m-d)
     * @return void
     */
    public static function invalidateSelectiveCache(string $date): void
    {
        $workoutDate = Carbon::parse($date);
        $userId = Auth::id();
        
        // Clés de cache à invalider
        $keysToInvalidate = [
            // Cache de la semaine concernée
            self::generateCacheKey('week_stats', [$workoutDate->year, $workoutDate->weekOfYear]),
            
            // Cache du mois concerné
            self::generateCacheKey('month_stats', [$workoutDate->year, $workoutDate->month]),
            
            // Cache des workouts de la semaine
            self::generateCacheKey('workouts_period', [
                $workoutDate->startOfWeek()->format('Y-m-d'),
                $workoutDate->endOfWeek()->format('Y-m-d')
            ]),
            
            // Cache des statistiques annuelles
            self::generateCacheKey('year_stats', [$workoutDate->year]),
        ];
        
        // Pattern pour les caches de tooltips
        $tooltipPatterns = [
            "tooltip_day_{$userId}_{$date}",
            "tooltip_week_{$userId}_{$workoutDate->year}_{$workoutDate->weekOfYear}",
        ];
        
        // Invalider les caches
        foreach ($keysToInvalidate as $key) {
            Cache::forget($key);
        }
        
        foreach ($tooltipPatterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    /**
     * Invalide tout le cache d'un utilisateur (pour changement d'année)
     * 
     * @return void
     */
    public static function invalidateUserCache(): void
    {
        $userId = Auth::id();
        
        // Pattern de cache utilisateur
        $patterns = [
            "week_stats_{$userId}_*",
            "month_stats_{$userId}_*",
            "year_stats_{$userId}_*",
            "workouts_period_{$userId}_*",
            "tooltip_*_{$userId}_*",
        ];
        
        foreach ($patterns as $pattern) {
            // Utiliser cache tags si disponible, sinon flush complet
            if (config('cache.default') === 'redis') {
                Cache::tags(["user_{$userId}"])->flush();
                break;
            } else {
                // Fallback : invalider les clés connues
                self::invalidatePatternCache($pattern);
            }
        }
    }

    /**
     * Invalide les caches correspondant à un pattern
     * 
     * @param string $pattern Pattern de clés à invalider
     * @return void
     */
    private static function invalidatePatternCache(string $pattern): void
    {
        // Cette méthode nécessiterait une implémentation spécifique
        // selon le driver de cache utilisé
        
        if (config('cache.default') === 'redis') {
            $redis = Cache::getRedis();
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                $redis->del($keys);
            }
        }
        // Pour les autres drivers, on peut implémenter un système de tags
    }

    /**
     * Pré-charge les données fréquemment utilisées
     * 
     * @param int $year Année à pré-charger
     * @return void
     */
    public static function preloadYearData(int $year): void
    {
        // Pré-charger les workouts de l'année en background
        $userId = Auth::id();
        
        dispatch(function() use ($year, $userId) {
            $workouts = Workout::where('user_id', $userId)
                ->whereYear('date', $year)
                ->with(['type', 'day'])
                ->get();
            
            $key = self::generateCacheKey('year_workouts', [$year]);
            Cache::put($key, $workouts, self::DEFAULT_CACHE_TTL);
        })->afterResponse();
    }

    /**
     * Obtient les statistiques en cache ou les calcule
     * 
     * @param string $cacheKey Clé de cache
     * @param callable $callback Fonction pour calculer les données si pas en cache
     * @param int $ttl Durée de vie du cache
     * @return mixed
     */
    public static function remember(string $cacheKey, callable $callback, int $ttl = self::DEFAULT_CACHE_TTL)
    {
        return Cache::remember($cacheKey, $ttl, $callback);
    }
}
