<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * Get the user that owns the year.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the months for this year.
     */
    public function months()
    {
        return $this->hasMany(Month::class);
    }

    /**
     * Get all weeks that start in this year.
     */
    public function weeks()
    {
        return $this->hasManyThrough(Week::class, Month::class);
    }

    /**
     * Get all days in this year.
     */
    public function days()
    {
        return $this->hasManyThrough(Day::class, Month::class);
    }

    /**
     * Get all workouts in this year.
     */
    public function workouts()
    {
        return Workout::whereHas('day', function ($query) {
            $query->whereHas('month', function ($q) {
                $q->where('year_id', $this->id);
            });
        });
    }

    /**
     * Get all activities in this year.
     */
    public function activities()
    {
        return Activity::whereHas('day', function ($query) {
            $query->whereHas('month', function ($q) {
                $q->where('year_id', $this->id);
            });
        });
    }

    /**
     * Create a Year record with all its months.
     */
    public static function createWithMonths($userId, $year)
    {
        $yearModel = self::create([
            'user_id' => $userId,
            'year' => $year,
        ]);

        // Create 12 months for this year
        for ($month = 1; $month <= 12; $month++) {
            $yearModel->months()->create([
                'month' => $month,
            ]);
        }

        return $yearModel;
    }

    /**
     * Get start date of the year.
     */
    public function getStartDate()
    {
        return Carbon::createFromDate($this->year, 1, 1)->startOfYear();
    }

    /**
     * Get end date of the year.
     */
    public function getEndDate()
    {
        return Carbon::createFromDate($this->year, 12, 31)->endOfYear();
    }
}
