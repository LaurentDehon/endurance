<?php

use Carbon\Carbon;
use App\Models\Training;

function formatTime(int $seconds): string
{
    $totalMinutes = floor($seconds / 60);
    $hours = floor($totalMinutes / 60);
    $remainingMinutes = $totalMinutes % 60;

    if ($remainingMinutes === 0) {
        return $hours . 'h';
    }

    return $hours . 'h' . str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT);
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
        'trainings' => Training::whereDate('date', $date)->get(),
        'is_today' => $date->isToday()
    ];
}