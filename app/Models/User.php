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
        'strava_token',
        'strava_refresh_token',
        'strava_expires_at',
        'email_verified_at',
        'theme_preference'
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
        ];
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
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
