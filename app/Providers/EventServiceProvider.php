<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\HandleUserLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Les événements et leurs listeners associés
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            HandleUserLogin::class,
        ],
    ];
}
