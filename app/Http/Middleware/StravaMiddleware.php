<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Services\StravaAuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StravaMiddleware
{
    protected $authService;

    public function __construct(StravaAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->strava_token) {
                // Token is expired
                if (Carbon::now()->timestamp >= $user->strava_expires_at) {
                    try {
                        // Attempt to refresh the token automatically
                        $this->authService->refreshUserToken($user);
                        // If successful, continue with the renewed token
                        return $next($request);
                    } catch (\Exception $e) {
                        // If token renewal fails, redirect to Strava connection
                        // Sauvegarder l'URL actuelle dans la session
                        Session::put('url.intended', $request->fullUrl());
                        
                        // Forcer le stockage de la session avant la redirection
                        Session::save();
                        
                        // Stocker l'ID de l'utilisateur dans la session pour le récupérer après
                        Session::put('auth_user_id', $user->id);
                        
                        return redirect()->route('strava.redirect');
                    }
                }
            }
        }

        return $next($request);
    }
}