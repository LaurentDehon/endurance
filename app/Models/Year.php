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
     * Get all weeks that belong to this year.
     */
    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    /**
     * Get all days in this year.
     */
    public function days()
    {
        return $this->hasMany(Day::class);
    }

    /**
     * Get all workouts in this year.
     */
    public function workouts()
    {
        return Workout::whereHas('day', function ($query) {
            $query->where('year_id', $this->id);
        });
    }

    /**
     * Get all activities in this year.
     */
    public function activities()
    {
        return Activity::whereHas('day', function ($query) {
            $query->where('year_id', $this->id);
        });
    }

    /**
     * Create a Year record.
     */
    public static function createForUser($userId, $year)
    {
        $yearModel = self::create([
            'user_id' => $userId,
            'year' => $year,
        ]);

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
