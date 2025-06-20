<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\sendEmailVerificationNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',        
        'last_login_at',
        'last_ip_address',
        'last_sync_at',
        'strava_token',
        'strava_refresh_token',
        'strava_expires_at',
        'email_verified_at',
        'theme_preference',
        'settings'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_sync_at' => 'datetime',
            'password' => 'hashed',
            'strava_expires_at' => 'integer',
            'settings' => 'array',
        ];
    }

    // Si les paramètres sont null, retourne un tableau par défaut
    public function getSettingsAttribute($value)
    {        
        return json_decode($value, true);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function years()
    {
        return $this->hasMany(Year::class);
    }

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    public function days()
    {
        return Day::whereHas('month', function ($query) {
            $query->whereHas('year', function ($q) {
                $q->where('user_id', $this->id);
            });
        });
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }  

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new sendEmailVerificationNotification());
    }

    /**
     * Retourne le fuseau horaire de l'utilisateur ou celui de l'application par défaut
     * 
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->settings['timezone'] ?? config('app.timezone');
    }

    /**
     * Retourne une date Carbon dans le fuseau horaire de l'utilisateur
     * 
     * @param string|null $time
     * @return \Carbon\Carbon
     */
    public function nowInUserTimezone(?string $time = null): \Carbon\Carbon
    {
        return $time ? \Carbon\Carbon::parse($time, $this->getTimezone()) : \Carbon\Carbon::now($this->getTimezone());
    }
}
