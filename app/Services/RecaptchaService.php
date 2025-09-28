<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private string $secretKey;
    private string $siteKey;
    
    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->siteKey = config('services.recaptcha.site_key');
    }
    
    /**
     * Vérifie le token reCAPTCHA
     */
    public function verify(string $token, string $action = 'register'): array
    {
        if (empty($this->secretKey)) {
            Log::warning('reCAPTCHA secret key not configured');
            return ['success' => false, 'error' => 'reCAPTCHA not configured'];
        }
        
        if (empty($token)) {
            return ['success' => false, 'error' => 'Missing reCAPTCHA token'];
        }
        
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => request()->ip()
            ]);
            
            $result = $response->json();
            
            if (!$result) {
                return ['success' => false, 'error' => 'Invalid reCAPTCHA response'];
            }
            
            // Vérifier le succès de base
            if (!($result['success'] ?? false)) {
                $errors = $result['error-codes'] ?? ['unknown-error'];
                Log::warning('reCAPTCHA verification failed', ['errors' => $errors]);
                return ['success' => false, 'error' => 'reCAPTCHA verification failed'];
            }
            
            // Vérifier l'action (reCAPTCHA v3)
            if (isset($result['action']) && $result['action'] !== $action) {
                return ['success' => false, 'error' => 'Invalid reCAPTCHA action'];
            }
            
            // Vérifier le score (reCAPTCHA v3) - score entre 0.0 et 1.0
            $score = $result['score'] ?? 0.0;
            $minScore = 0.5; // Score minimum acceptable
            
            if ($score < $minScore) {
                Log::info('reCAPTCHA score too low', [
                    'score' => $score,
                    'min_score' => $minScore,
                    'ip' => request()->ip()
                ]);
                return ['success' => false, 'error' => 'reCAPTCHA score too low', 'score' => $score];
            }
            
            return [
                'success' => true,
                'score' => $score,
                'action' => $result['action'] ?? null,
                'hostname' => $result['hostname'] ?? null
            ];
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'message' => $e->getMessage(),
                'ip' => request()->ip()
            ]);
            return ['success' => false, 'error' => 'reCAPTCHA service error'];
        }
    }
    
    /**
     * Obtient la clé du site pour le frontend
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }
    
    /**
     * Vérifie si reCAPTCHA est configuré
     */
    public function isConfigured(): bool
    {
        return !empty($this->secretKey) && !empty($this->siteKey);
    }
}