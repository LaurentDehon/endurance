<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'description'
    ];
}
