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
     * 
     * @param mixed $date The date to find or create
     * @param int|null $userId The user ID (required for queue jobs where Auth::id() is null)
     */
    public static function findByDateOrCreate($date, $userId = null)
    {
        $dateObj = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        $day = self::where('date', $dateObj->format('Y-m-d'))->first();
        
        if (!$day) {
            // Use provided userId or fallback to Auth::id() for web requests
            $effectiveUserId = $userId ?? Auth::id();
            
            if (!$effectiveUserId) {
                throw new \Exception('User ID is required to create Day, Week, and Year records');
            }
            
            // Find or create the year
            $yearObj = Year::firstOrCreate([
                'year' => $dateObj->year,
                'user_id' => $effectiveUserId
            ]);
            
            // Find or create the week
            $weekObj = Week::firstOrCreate([
                'year' => $dateObj->year,
                'week_number' => $dateObj->weekOfYear,
                'user_id' => $effectiveUserId
            ], [
                'year_id' => $yearObj->id
            ]);
            
            // Create the day using firstOrCreate to avoid integrity violations
            $day = self::firstOrCreate(
                ['date' => $dateObj->format('Y-m-d')],
                [
                    'year_id' => $yearObj->id,
                    'week_id' => $weekObj->id,
                    'date' => $dateObj->format('Y-m-d')
                ]
            );
            
            // Update week and month associations if needed
            if ($day->week_id !== $weekObj->id) {
                $day->week_id = $weekObj->id;
                $day->save();
            }
        }
        
        return $day;
    }
}
