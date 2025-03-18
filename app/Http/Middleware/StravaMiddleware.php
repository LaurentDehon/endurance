<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StravaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user->strava_token || Carbon::now()->timestamp >= $user->strava_expires_at) {
                return redirect()->route('strava.connect');
            }
        }

        return $next($request);
    }
}