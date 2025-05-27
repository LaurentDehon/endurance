<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'strava_id', 
        'user_id',
        'start_date',
        'name',
        'type',
        'distance',
        'moving_time', // in seconds
        'elapsed_time',
        'average_speed',
        'max_speed',
        'average_heartrate',
        'max_heartrate',
        'total_elevation_gain',
        'elev_high',
        'elev_low',
        'sync_date',
        'kudos_count',
        'description',
        'calories',
        'map_polyline',
        'day_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'sync_date' => 'datetime',
        'map_polyline' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function day()
    {
        return $this->belongsTo(Day::class);
    }
    
    /**
     * Auto-associate with day based on start_date when creating or updating
     */
    protected static function booted()
    {
        static::saving(function ($activity) {
            if ($activity->start_date && !$activity->day_id) {
                $day = Day::findByDateOrCreate($activity->start_date);
                $activity->day_id = $day->id;
            }
        });
    }
}
