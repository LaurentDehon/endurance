<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class StravaAuthService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.strava.client_id');
        $this->clientSecret = config('services.strava.client_secret');
        $this->redirectUri = config('services.strava.redirect');
    }

    public function getRedirectUrl(): string
    {
        return "https://www.strava.com/oauth/authorize?" . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'read,activity:read_all',
            'approval_prompt' => 'force'
        ]);
    }

    public function handleAuthorizationCode(string $code): User
    {
        $tokens = $this->exchangeCodeForTokens($code);
        return $this->updateOrCreateUser($tokens);
    }

    private function exchangeCodeForTokens(string $code): array
    {
        $response = (new Client())->post('https://www.strava.com/oauth/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'grant_type' => 'authorization_code'
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function refreshUserToken(User $user): ?User
    {
        // Si le token est encore valide (avec une marge de 5 minutes), le retourner tel quel
        if ($user->strava_token && $user->strava_expires_at > now()->addMinutes(5)->timestamp) {
            return $user;
        }

        // Si pas de refresh token, impossible de renouveler
        if (!$user->strava_refresh_token) {
            return null;
        }

        try {
            $tokens = $this->refreshToken($user->strava_refresh_token);
            return $this->updateOrCreateUser($tokens, $user);
        } catch (\Exception $e) {
            // En cas d'erreur lors du renouvellement, retourner null
            \Illuminate\Support\Facades\Log::warning("Failed to refresh Strava token for user {$user->id}: " . $e->getMessage());
            return null;
        }
    }

    private function refreshToken(string $refreshToken): array
    {
        $response = (new Client())->post('https://www.strava.com/oauth/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function updateOrCreateUser(array $tokens, ?User $user = null): User
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            throw new \Exception(__('strava.auth.no_user_found'));
        }

        if (!$user instanceof User) {
            throw new \Exception(__('strava.auth.not_user_instance'));
        }

        $user->fill([
            'strava_token' => $tokens['access_token'],
            'strava_refresh_token' => $tokens['refresh_token'],
            'strava_expires_at' => $tokens['expires_at'],
        ]);
        $user->save();

        return $user;
    }
}