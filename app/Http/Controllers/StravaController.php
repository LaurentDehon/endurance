<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use TallStackUi\Traits\Interactions;

class StravaController extends Controller
{
    use Interactions; 

    public function __construct(protected StravaAuthService $authService) {}

    public function redirect()
    {
        // S'assurer que l'URL de redirection est bien préservée pendant le processus d'authentification
        if (!session()->has('strava_redirect_url')) {
            session(['strava_redirect_url' => route('dashboard')]);
        }
        
        return redirect($this->authService->getRedirectUrl());
    }

    public function handleCallback(Request $request)
    {
        try {
            $this->authService->handleAuthorizationCode($request->code);
            $this->toast()->success('Strava connected')->send();

        } catch (\Exception $e) {
            $this->toast()->error('Connection failed : ' . $e->getMessage())->send();
        }

        // Récupérer l'URL d'origine depuis la session et y rediriger l'utilisateur
        $redirectUrl = session('strava_redirect_url', route('home'));
        session()->forget('strava_redirect_url'); // Nettoyer la session
        
        return redirect($redirectUrl);
    }

    public function showConnect(Request $request)
    {
        // Stocker l'URL précédente dans la session
        session(['strava_redirect_url' => url()->previous()]);
        
        return view('strava.connect');
    }
}