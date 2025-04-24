<?php

use Carbon\Carbon;
use App\Models\Workout;

function formatTime(int $seconds): string
{
    $totalMinutes = floor($seconds / 60);
    $hours = floor($totalMinutes / 60);
    $remainingMinutes = $totalMinutes % 60;

    if ($hours > 0) {
        return $hours . 'h' . ($remainingMinutes > 0 ? str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT) : '');
    }

    return $remainingMinutes . 'm';
}

function formatTimeCompact($seconds) 
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}

function formatDistance(float $distance): string
{
    return rtrim(rtrim(number_format($distance, 2), '0'), '.'). 'km';
}

function getDayData($date)
{
    $date = Carbon::parse($date);
    return [
        'date' => $date,
        'name' => $date->format('D'),
        'number' => $date->day,
        'workouts' => Workout::whereDate('date', $date)->get(),
        'is_today' => $date->isToday()
    ];
}

/**
 * Récupère les classes CSS pour un élément spécifique du thème actif
 * 
 * @param string $key La clé de l'élément du thème (ex: 'text-primary', 'background', etc.)
 * @param string $default Classes CSS par défaut si la clé n'existe pas
 * @return string Les classes CSS à appliquer
 */
function themeClass($key, $default = '') {
    // Récupération du thème préféré de l'utilisateur depuis la session
    $themeName = session('theme_preference', config('themes.default', 'blue'));
    
    // Vérification si le thème existe dans la configuration
    if (!array_key_exists($themeName, config('themes.themes', []))) {
        $themeName = 'blue'; // Thème par défaut si celui demandé n'existe pas
    }
    
    // Récupération des données du thème
    $theme = config('themes.themes.'.$themeName, [
        'name' => 'Blue Performance',
        'colors' => [
            'background' => ['from-indigo-900', 'via-blue-800', 'to-blue-600'],
            'text-primary' => ['text-white'],
            'text-secondary' => ['text-blue-200'],
            'button-bg' => ['bg-blue-500', 'hover:bg-blue-600'],
            'button-text' => ['text-white'],
            'card-bg' => ['bg-white', 'bg-opacity-10'],
            'card-border' => ['border-white', 'border-opacity-20'],
            'modal-bg' => ['bg-white'],
            'modal-text' => ['text-gray-800'],
        ],
    ]);
    
    return isset($theme['colors'][$key]) ? implode(' ', $theme['colors'][$key]) : $default;
}