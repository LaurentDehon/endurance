<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Activity;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user instanceof User) {
            return redirect()->route('login');
        }
        
        // Get next workouts (for the first card)
        $nextWorkouts = $this->getNextWorkouts($user);
        
        // Get next race (for the second card)
        $nextRace = $this->getNextRace($user);
        
        // Calculate weekly stats
        $weeklyStats = $this->calculateWeeklyStats($user);
        
        // Get weekly goals
        $weeklyGoal = $this->getWeeklyGoals($user);
        
        // Calculate monthly consistency
        $monthlyConsistency = $this->calculateMonthlyConsistency($user);
        
        return view('dashboard', compact('nextWorkouts', 'nextRace', 'weeklyStats', 'weeklyGoal', 'monthlyConsistency'));
    }
    
    private function getNextWorkouts(User $user)
    {
        $nextWorkouts = Workout::where('user_id', $user->id)
            ->where('date', '>=', Carbon::today())
            ->whereHas('type', function($query) {
            $query->where('name', '!=', 'Race');
            })
            ->orderBy('date', 'asc')
            ->with('type')
            ->take(2)
            ->get();
            
        foreach ($nextWorkouts as $workout) {
            // Add a title property that combines workout type and date
            $typeName = $workout->type ? $workout->type->getLocalizedName() : 'Workout';
            $workout->title = $typeName;
        }
            
        return $nextWorkouts;
    }

    private function getNextRace(User $user)
    {
        // Assuming there is a 'type_id' that defines a race (adjust as needed)
        // You might need to adjust this query based on how races are identified in your system
        $nextRace = Workout::where('user_id', $user->id)
            ->where('date', '>=', Carbon::today())
            ->whereHas('type', function($query) {
                $query->where('name', 'Race');
            })
            ->orderBy('date', 'asc')
            ->first();
            
        if ($nextRace) {
            $nextRace->title = $nextRace->notes ?? '';
        }
            
        return $nextRace;
    }

    private function calculateWeeklyStats(User $user)
    {
        $stats = new \stdClass();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        // Calculer les statistiques avec une requête SQL directe optimisée
        $weeklyStats = Activity::selectRaw('
            SUM(distance) as total_distance, 
            SUM(moving_time) as total_duration, 
            SUM(total_elevation_gain) as total_elevation
        ')
        ->where('user_id', $user->id)
        ->whereBetween('start_date', [$weekStart, $weekEnd])
        ->first();
        
        $stats->distance = $weeklyStats ? $weeklyStats->total_distance / 1000 : 0;
        $stats->duration = $weeklyStats ? $weeklyStats->total_duration : 0;
        $stats->elevation = $weeklyStats ? $weeklyStats->total_elevation : 0;
        
        return $stats;
    }
    
    private function getWeeklyGoals(User $user)
    {
        $goals = new \stdClass();
        
        // Calculer les objectifs hebdomadaires avec une requête SQL directe optimisée
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        $weeklyGoals = Workout::selectRaw('
            SUM(distance) as total_distance, 
            SUM(duration) as total_duration, 
            SUM(elevation) as total_elevation
        ')
        ->where('user_id', $user->id)
        ->whereBetween('date', [$weekStart, $weekEnd])
        ->first();
            
        $goals->distance = $weeklyGoals ? $weeklyGoals->total_distance : 0;
        $goals->duration = $weeklyGoals ? $weeklyGoals->total_duration * 60 : 0;
        $goals->elevation = $weeklyGoals ? $weeklyGoals->total_elevation : 0;
        
        return $goals;
    }
    
    private function calculateMonthlyConsistency(User $user)
    {
        $monthStart = Carbon::now()->startOfMonth();
        $today = Carbon::now();
        
        // Get days with planned workouts
        $plannedDays = Workout::where('user_id', $user->id)
            ->where('date', '>=', $monthStart)
            ->where('date', '<=', $today)
            ->distinct('date')
            ->count('date');
            
        // No planned workouts means we can't calculate consistency
        if ($plannedDays === 0) {
            return 0;
        }
            
        // Get days with completed activities
        $completedDays = Activity::where('user_id', $user->id)
            ->where('start_date', '>=', $monthStart)
            ->where('start_date', '<=', $today)
            ->distinct()
            ->count(Activity::raw('DATE(start_date)'));
            
        // Calculate consistency (completed vs planned)
        $consistencyRate = min(($completedDays / $plannedDays) * 100, 100);
        
        return round($consistencyRate);
    }
}
