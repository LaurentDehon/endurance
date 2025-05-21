<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'distance',
        'duration',
        'elevation',
        'notes',
        'workout_type_id',
        'user_id',
        'day_id'
    ];

    protected $casts = [
        'date' => 'date',
        'distance' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function type()
    {
        return $this->belongsTo(WorkoutType::class, 'workout_type_id');
    }
    
    /**
     * Auto-associate with day based on date when creating or updating
     */
    protected static function booted()
    {
        static::saving(function ($workout) {
            if ($workout->date && !$workout->day_id) {
                $day = Day::findByDateOrCreate($workout->date);
                $workout->day_id = $day->id;
            }
        });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeBetweenDates($query, string $start, string $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }
}