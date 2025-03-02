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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
