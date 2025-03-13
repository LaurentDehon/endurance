<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'distance',
        'duration',
        'elevation',
        'notes',
        'training_type_id',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'distance' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(TrainingType::class, 'training_type_id');
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