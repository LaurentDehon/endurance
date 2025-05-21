<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Week extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'week_number', 'week_type_id', 'user_id', 'month_id', 'notes', 'settings'];

    protected $casts = [
        'settings' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function type()
    {
        return $this->belongsTo(WeekType::class, 'week_type_id');
    }

    public function days()
    {
        return $this->hasMany(Day::class);
    }

    /**
     * Get all workouts in this week via days.
     */
    public function workouts()
    {
        return Workout::whereHas('day', function ($query) {
            $query->where('week_id', $this->id);
        });
    }

    /**
     * Get all activities in this week via days.
     */
    public function activities()
    {
        return Activity::whereHas('day', function ($query) {
            $query->where('week_id', $this->id);
        });
    }

    public static function getWeekType($year, $week)
    {
        return self::where('year', $year)->where('week_number', $week)->value('week_type_id') ?? null;
    }

    /**
     * Get the start date of the week.
     */
    public function getStartDate()
    {
        return Carbon::now()->setISODate($this->year, $this->week_number, 1)->startOfWeek();
    }

    /**
     * Get the end date of the week.
     */
    public function getEndDate()
    {
        return Carbon::now()->setISODate($this->year, $this->week_number, 1)->endOfWeek();
    }
}
