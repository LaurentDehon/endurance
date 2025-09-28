<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecaptchaRule implements ValidationRule
{
    private string $action;
    
    public function __construct(string $action = 'register')
    {
        $this->action = $action;
    }
    
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptchaService = app(RecaptchaService::class);
        
        if (!$recaptchaService->isConfigured()) {
            // Si reCAPTCHA n'est pas configuré, on laisse passer (pour le développement)
            return;
        }
        
        $result = $recaptchaService->verify($value, $this->action);
        
        if (!$result['success']) {
            $fail(__('auth.register.recaptcha_failed'));
        }
    }
}
