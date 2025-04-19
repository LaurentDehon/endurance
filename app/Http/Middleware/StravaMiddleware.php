<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StravaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user->strava_token || Carbon::now()->timestamp >= $user->strava_expires_at) {
                // Sauvegarder l'URL actuelle dans la session
                Session::put('url.intended', $request->fullUrl());
                
                // Forcer le stockage de la session avant la redirection
                Session::save();
                
                // Stocker l'ID de l'utilisateur dans la session pour le récupérer après
                Session::put('auth_user_id', $user->id);
                
                return redirect()->route('strava.connect');
            }
        }

        return $next($request);
    }
}