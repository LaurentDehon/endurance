<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Month extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_id',
        'month',
    ];

    protected $casts = [
        'month' => 'integer',
    ];

    /**
     * Get the year that owns the month.
     */
    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    /**
     * Get the weeks that overlap with this month.
     * Note: A week can belong to multiple months due to month boundaries.
     */
    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    /**
     * Get the days for this month.
     */
    public function days()
    {
        return $this->hasMany(Day::class);
    }

    /**
     * Get all workouts in this month via days.
     */
    public function workouts()
    {
        return Workout::whereHas('day', function ($query) {
            $query->where('month_id', $this->id);
        });
    }

    /**
     * Get all activities in this month via days.
     */
    public function activities()
    {
        return Activity::whereHas('day', function ($query) {
            $query->where('month_id', $this->id);
        });
    }

    /**
     * Get the start date of the month.
     */
    public function getStartDate()
    {
        return Carbon::createFromDate($this->year->year, $this->month, 1)->startOfMonth();
    }

    /**
     * Get the end date of the month.
     */
    public function getEndDate()
    {
        return Carbon::createFromDate($this->year->year, $this->month, 1)->endOfMonth();
    }

    /**
     * Get name of the month.
     */
    public function getName()
    {
        return $this->getStartDate()->format('F');
    }

    /**
     * Create days for this month.
     */
    public function createDays()
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();
        
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            // Find or create the appropriate week
            $weekNumber = $currentDate->weekOfYear;
            $week = Week::firstOrCreate(
                [
                    'year' => $currentDate->year,
                    'week_number' => $weekNumber,
                    'user_id' => $this->year->user_id
                ]
            );
            
            // Create the day and associate it with this month and the week
            $this->days()->create([
                'date' => $currentDate->format('Y-m-d'),
                'week_id' => $week->id,
            ]);
            
            $currentDate->addDay();
        }
    }
}
