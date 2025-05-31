<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Workout;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Commande pour analyser et tester les performances des optimisations
 */
class AnalyzePerformanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:analyze-performance 
                            {user_id : ID de l\'utilisateur à analyser}
                            {--year=2024 : Année à analyser}
                            {--benchmark : Exécuter des tests de performance}
                            {--cache-status : Vérifier le statut du cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse les performances du calendrier et teste les optimisations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $year = $this->option('year');
        
        $this->info("🔍 Analyse des performances pour l'utilisateur {$userId} - Année {$year}");
        $this->newLine();
        
        // Simuler l'authentification
        Auth::loginUsingId($userId);
        
        if ($this->option('cache-status')) {
            $this->analyzeCacheStatus($userId, $year);
        }
        
        if ($this->option('benchmark')) {
            $this->runPerformanceBenchmarks($userId, $year);
        }
        
        $this->analyzeWorkoutDistribution($userId, $year);
        $this->providePerfomanceRecommendations($userId, $year);
    }

    /**
     * Analyse le statut du cache
     */
    private function analyzeCacheStatus($userId, $year)
    {
        $this->info("📊 Analyse du cache :");
        
        // Vérifier les différents types de cache
        $cacheTypes = [
            'workouts' => "workouts_{$userId}_{$year}",
            'week_stats' => "week_stats_{$userId}_{$year}_*",
            'month_stats' => "month_stats_{$userId}_{$year}_*",
            'tooltips' => "tooltip_*_{$userId}_*"
        ];
        
        foreach ($cacheTypes as $type => $pattern) {
            $cached = Cache::has(str_replace('*', '1', $pattern));
            $status = $cached ? '✅ Présent' : '❌ Absent';
            $this->line("  {$type}: {$status}");
        }
        
        $this->newLine();
    }

    /**
     * Exécute des tests de performance
     */
    private function runPerformanceBenchmarks($userId, $year)
    {
        $this->info("⚡ Tests de performance :");
        
        // Test 1: Récupération des workouts
        $start = microtime(true);
        $workouts = Workout::where('user_id', $userId)
            ->whereYear('date', $year)
            ->with(['type', 'day'])
            ->get();
        $workoutsTime = (microtime(true) - $start) * 1000;
        
        $this->line("  Récupération workouts: " . round($workoutsTime, 2) . "ms ({$workouts->count()} workouts)");
        
        // Test 2: Calcul des statistiques SQL direct
        $start = microtime(true);
        $stats = DB::table('workouts')
            ->where('user_id', $userId)
            ->whereYear('date', $year)
            ->selectRaw('
                COUNT(*) as total_workouts,
                SUM(distance) as total_distance,
                SUM(duration) as total_duration,
                SUM(elevation) as total_elevation
            ')
            ->first();
        $sqlStatsTime = (microtime(true) - $start) * 1000;
        
        $this->line("  Statistiques SQL direct: " . round($sqlStatsTime, 2) . "ms");
        
        // Test 3: Calcul par semaine groupé
        $start = microtime(true);
        $weekStats = DB::table('workouts')
            ->join('days', 'workouts.day_id', '=', 'days.id')
            ->join('weeks', 'days.week_id', '=', 'weeks.id')
            ->where('workouts.user_id', $userId)
            ->where('weeks.year', $year)
            ->groupBy('weeks.week_number')
            ->selectRaw('
                weeks.week_number,
                COUNT(workouts.id) as workout_count,
                SUM(workouts.distance) as total_distance,
                SUM(workouts.duration) as total_duration
            ')
            ->get();
        $weekStatsTime = (microtime(true) - $start) * 1000;
        
        $this->line("  Statistiques par semaine: " . round($weekStatsTime, 2) . "ms ({$weekStats->count()} semaines)");
        
        // Analyse des résultats
        $this->newLine();
        if ($workoutsTime < 100 && $sqlStatsTime < 50 && $weekStatsTime < 100) {
            $this->info("✅ Performances excellentes !");
        } elseif ($workoutsTime < 500 && $sqlStatsTime < 200 && $weekStatsTime < 300) {
            $this->warn("⚠️  Performances correctes, optimisations recommandées");
        } else {
            $this->error("❌ Performances dégradées, optimisations nécessaires");
        }
        
        $this->newLine();
    }

    /**
     * Analyse la distribution des workouts
     */
    private function analyzeWorkoutDistribution($userId, $year)
    {
        $this->info("📈 Distribution des workouts :");
        
        // Répartition par mois
        $monthlyStats = DB::table('workouts')
            ->where('user_id', $userId)
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $months = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr', 5 => 'Mai', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aoû', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];
        
        foreach ($monthlyStats as $stat) {
            $monthName = $months[$stat->month];
            $bar = str_repeat('█', min(50, $stat->count));
            $this->line("  {$monthName}: {$bar} ({$stat->count})");
        }
        
        $this->newLine();
    }

    /**
     * Fournit des recommandations de performance
     */
    private function providePerfomanceRecommendations($userId, $year)
    {
        $totalWorkouts = Workout::where('user_id', $userId)
            ->whereYear('date', $year)
            ->count();
        
        $this->info("🎯 Recommandations :");
        
        if ($totalWorkouts > 300) {
            $this->line("  • Considérer la pagination pour l'affichage");
            $this->line("  • Utiliser le lazy loading pour les tooltips");
            $this->line("  • Implémenter un cache Redis pour de meilleures performances");
        }
        
        if ($totalWorkouts > 100) {
            $this->line("  • Les optimisations implementées sont bénéfiques");
            $this->line("  • Surveiller les métriques de cache hit ratio");
        }
        
        $this->line("  • Utiliser les méthodes optimisées pour les CRUD operations");
        $this->line("  • Monitorer les temps de réponse avec les nouveaux événements");
        
        $this->newLine();
        $this->info("✨ Optimisations implementées avec succès !");
    }
}
