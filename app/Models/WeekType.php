<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WeekType extends Model
{
    protected $fillable = ['name', 'color'];
    
    /**
     * Get the localized name of the week type
     *
     * @param string|null $locale The locale to use (default: current app locale)
     * @return string
     */
    public function getLocalizedName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $key = Str::snake(Str::lower($this->name));
        
        return __("week_types.{$key}", [], $locale);
    }
}
