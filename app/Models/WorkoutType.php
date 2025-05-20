<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkoutType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'short',
        'description'
    ];
    
    /**
     * Get the localized name of the workout type
     *
     * @param string|null $locale The locale to use (default: current app locale)
     * @return string
     */
    public function getLocalizedName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $key = Str::snake(Str::lower($this->name));
        
        return __("workout_types.{$key}", [], $locale);
    }
}
