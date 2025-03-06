<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'week_number', 'week_type_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(WeekType::class, 'week_type_id');
    }

    public static function getWeekType($year, $week)
    {
        return self::where('year', $year)->where('week_number', $week)->value('week_type_id') ?? null;
    }
}
