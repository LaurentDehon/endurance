<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
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
            'password' => 'hashed',
            'strava_expires_at' => 'integer',
            'settings' => 'array',
        ];
    }

    // Si les paramètres sont null, retourne un tableau par défaut
    public function getSettingsAttribute($value)
    {
        if (is_null($value)) {
            return [
                'theme' => 'system',
                'language' => 'en',
                'notification_email' => true,
                'notification_app' => true,
            ];
        }

        return json_decode($value, true);
    }

    /**
     * Get the user settings
     * 
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set the user settings
     * 
     * @param array $settings
     * @return void
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function weeks()
    {
        return $this->hasMany(Week::class);
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
}
