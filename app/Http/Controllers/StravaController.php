<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StravaController extends Controller
{
    public function __construct(protected StravaAuthService $authService) {}

    public function redirect()
    {
        // L'URL de redirection est déjà stockée par le middleware
        return redirect($this->authService->getRedirectUrl());
    }

    public function handleCallback(Request $request)
    {
        try {
            $this->authService->handleAuthorizationCode($request->code);
            session()->flash('toast', [
                'message' => 'Strava connected successfully',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            session()->flash('toast', [
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }

        // Récupérer l'URL intentionnelle stockée par le middleware
        $redirectUrl = Session::pull('url.intended', route('home'));
        
        return redirect($redirectUrl);
    }

    public function showConnect(Request $request)
    {
        // L'URL est déjà stockée par le middleware dans Session::put('url.intended')
        return view('strava.connect');
    }
}