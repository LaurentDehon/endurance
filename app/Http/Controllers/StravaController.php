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
        // mais seulement si ce n'est pas déjà une redirection forcée par le middleware
        if (!Session::has('strava.forced_redirect')) {
            // On mémorise que l'utilisateur a initié la connexion lui-même
            Session::forget('url.intended');
            
            // Stocker l'URL de référence
            if ($request->headers->has('referer')) {
                Session::put('strava.referer', $request->headers->get('referer'));
            }
            
            // Stocker l'ID de l'utilisateur pour le récupérer après l'authentification si nécessaire
            if (Auth::check()) {
                Session::put('auth_user_id', Auth::id());
            }
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
            
            session()->flash('toast', [
                'message' => 'Strava connected successfully',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            session()->flash('toast', [
                'message' => 'Error connecting to Strava: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }

        // Vérifier si la redirection a été forcée par le middleware
        $forcedRedirect = Session::get('strava.forced_redirect', false);
        
        // Supprimer la variable de session après l'avoir utilisée
        Session::forget('strava.forced_redirect');
        
        // Si la redirection a été forcée par le middleware, utiliser intended()
        if ($forcedRedirect) {
            return redirect()->intended(route('dashboard'));
        } else {
            // Vérifier si une URL de référence a été stockée lors de la redirection
            $referer = Session::get('strava.referer');
            Session::forget('strava.referer');
            
            if ($referer) {
                return redirect($referer);
            }
            
            // Fallback à la page d'accueil si aucune référence n'est disponible
            return redirect(route('dashboard'));
        }
    }

    public function showConnect(Request $request)
    {
        // L'URL est déjà stockée par le middleware dans Session::put('url.intended')
        return view('strava.connect');
    }

    /**
     * Déconnecte l'utilisateur de Strava en rendant le token expiré
     */
    public function disconnect()
    {
        $user = User::find(Auth::user()->id);
        $user->strava_expires_at = now()->timestamp;
        $user->strava_token = null;
        $user->strava_refresh_token = null;
        $user->save();
        
        session()->flash('toast', [
            'message' => 'Strava connection has been reset successfully',
            'type' => 'success'
        ]);
        
        return redirect()->route('settings.index');
    }
}