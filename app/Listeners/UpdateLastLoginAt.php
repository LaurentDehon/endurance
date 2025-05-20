<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Model;

class UpdateLastLoginAt
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event): void
    {
        $user = $event->user;
        $user->last_login_at = now();
        
        if (request()->ip()) {
            $user->last_ip_address = request()->ip();
        }
        
        if ($user instanceof Model) {
            $user->save();
        }
    }
}
