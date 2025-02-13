<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StravaAuthController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = env('STRAVA_CLIENT_ID');
        $this->clientSecret = env('STRAVA_CLIENT_SECRET');
        $this->redirectUri = env('STRAVA_REDIRECT_URI');
    }

    public function redirectToStrava()
    {
        $url = "https://www.strava.com/oauth/authorize?"
            . "client_id={$this->clientId}"
            . "&response_type=code"
            . "&redirect_uri={$this->redirectUri}"
            . "&scope=read,activity:read_all"
            . "&approval_prompt=auto";
        dd($url);

        return redirect()->away($url);
    }

    public function handleCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return redirect('/')->with('error', 'Autorisation refusée.');
        }

        // Échange le code contre un token
        $response = Http::post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);
        
        $data = $response->json();

        if (!isset($data['access_token'])) {
            return redirect('/')->with('error', 'Erreur d’authentification.');
        }

        // Stocke les credentials
        session([
            'strava_access_token' => $data['access_token'],
            'strava_refresh_token' => $data['refresh_token'],
            'strava_expires_at' => $data['expires_at'],
        ]);

        return redirect('/strava/success')->with('success', 'Connexion réussie à Strava.');
    }

    public function success()
    {
        return view('strava.success');
    }
}
