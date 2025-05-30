<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\StravaAuthService;
use App\Services\StravaSyncService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StravaController extends Controller
{
    protected $syncService;

    public function __construct(
        protected StravaAuthService $authService,
        StravaSyncService $syncService
    ) {
        $this->syncService = $syncService;
    }

    public function redirect(Request $request)
    {
        // Stocker l'URL de référence pour y revenir après la connexion
        if ($request->headers->has('referer')) {
            $referer = $request->headers->get('referer');
            Session::put('strava.referer', $referer);
        }
        
        // Stocker l'ID de l'utilisateur pour le récupérer après l'authentification si nécessaire
        if (Auth::check()) {
            Session::put('auth_user_id', Auth::id());
        }
        
        // Redirection vers l'URL d'autorisation de Strava
        return redirect($this->authService->getRedirectUrl());
    }

    public function handleCallback(Request $request)
    {
        try {
            $user = $this->authService->handleAuthorizationCode($request->code);
            
            // Vérifier si l'utilisateur est connecté et le réauthentifier si nécessaire
            if (!Auth::check() && Session::has('auth_user_id')) {
                Auth::loginUsingId(Session::get('auth_user_id'));
            }
            
            // Nouvelle logique : toujours lancer une sync automatique après connexion Strava
            // peu importe d'où vient la demande
            if (Auth::check()) {
                // Marquer la synchronisation comme en cours pour que l'interface le sache
                $userId = Auth::id();
                cache()->put("strava_sync_in_progress_{$userId}", true, now()->addMinutes(5));
                
                // Utiliser le job de synchronisation pour lancer la sync en arrière-plan
                \App\Jobs\StravaSyncJob::dispatch($userId);
                
                session()->flash('toast', [
                    'message' => __('strava.messages.connected_sync_started'),
                    'type' => 'success'
                ]);
            } else {
                session()->flash('toast', [
                    'message' => __('strava.messages.connected'),
                    'type' => 'success'
                ]);
            }

        } catch (\Exception $e) {
            session()->flash('toast', [
                'message' => __('strava.messages.connection_error', ['error' => $e->getMessage()]),
                'type' => 'error'
            ]);
        }

        // Vérifier si une URL de référence a été stockée lors de la redirection
        $referer = Session::get('strava.referer');
        Session::forget('strava.referer');
        
        if ($referer) {
            return redirect($referer);
        }
        
        // Fallback à la page d'accueil si aucune référence n'est disponible
        return redirect(route('dashboard'));
    }

    /**
     * Déconnecte l'utilisateur de Strava en rendant le token expiré
     */
    public function disconnect()
    {
        $user = User::find(Auth::user()->id);
        $user->strava_expires_at = now()->timestamp;
        // $user->strava_token = null;
        // $user->strava_refresh_token = null;
        $user->save();
        
        session()->flash('toast', [
            'message' => __('strava.messages.disconnected'),
            'type' => 'success'
        ]);
        
        return redirect()->route('settings.index');
    }
}