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