<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_id',
        'week_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the year that owns the day.
     */
    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    /**
     * Get the week that owns the day.
     */
    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    /**
     * Get the workouts for this day.
     */
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * Get the activities for this day.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the day name (Monday, Tuesday, etc.).
     */
    public function getDayName()
    {
        return $this->date->format('l');
    }

    /**
     * Get the day number in the month.
     */
    public function getDayNumber()
    {
        return $this->date->format('j');
    }
    
    /**
     * Check if this day is today.
     */
    public function isToday()
    {
        return $this->date->isToday();
    }

    /**
     * Find a Day by date, or create if it doesn't exist.
     */
    public static function findByDateOrCreate($date)
    {
        $dateObj = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        $day = self::where('date', $dateObj->format('Y-m-d'))->first();
        
        if (!$day) {
            \Illuminate\Support\Facades\Log::info("Creating new day for date: " . $dateObj->format('Y-m-d'));
            
            // Find or create the year
            $yearObj = Year::firstOrCreate([
                'year' => $dateObj->year,
                'user_id' => Auth::id()
            ]);
            
            // Find or create the month
            $monthObj = Month::firstOrCreate([
                'year_id' => $yearObj->id,
                'month' => $dateObj->month
            ]);
            
            // Find or create the week
            $weekObj = Week::firstOrCreate([
                'year' => $dateObj->year,
                'week_number' => $dateObj->weekOfYear,
                'user_id' => Auth::id(),
                'month_id' => $monthObj->id
            ]);
            
            // Create the day using firstOrCreate to avoid integrity violations
            $day = self::firstOrCreate(
                ['date' => $dateObj->format('Y-m-d')],
                [
                    'month_id' => $monthObj->id,
                    'week_id' => $weekObj->id,
                    'date' => $dateObj->format('Y-m-d')
                ]
            );
            
            // Update week and month associations if needed
            if ($day->month_id !== $monthObj->id || $day->week_id !== $weekObj->id) {
                $day->month_id = $monthObj->id;
                $day->week_id = $weekObj->id;
                $day->save();
                \Illuminate\Support\Facades\Log::info("Day relations updated for existing day: " . $day->id);
            } else {
                \Illuminate\Support\Facades\Log::info("Day created successfully with ID: " . $day->id);
            }
        }
        
        return $day;
    }
}
