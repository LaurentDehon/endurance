<?php

namespace Tests\Feature;

use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RecaptchaIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_recaptcha_rule_passes_with_valid_token(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'test-secret-key');
        
        // Mock de la réponse reCAPTCHA réussie
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.8,
                'action' => 'register',
                'hostname' => 'localhost'
            ])
        ]);
        
        $validator = Validator::make(
            ['recaptcha_token' => 'valid-token'],
            ['recaptcha_token' => ['required', new RecaptchaRule('register')]]
        );
        
        $this->assertTrue($validator->passes());
    }

    public function test_recaptcha_rule_fails_with_invalid_token(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'test-secret-key');
        
        // Mock de la réponse reCAPTCHA échouée
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response']
            ])
        ]);
        
        $validator = Validator::make(
            ['recaptcha_token' => 'invalid-token'],
            ['recaptcha_token' => ['required', new RecaptchaRule('register')]]
        );
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('recaptcha_token', $validator->errors()->toArray());
    }

    public function test_recaptcha_rule_passes_when_not_configured(): void
    {
        Config::set('services.recaptcha.site_key', '');
        Config::set('services.recaptcha.secret_key', '');
        
        $validator = Validator::make(
            ['recaptcha_token' => 'any-token'],
            ['recaptcha_token' => ['required', new RecaptchaRule('register')]]
        );
        
        $this->assertTrue($validator->passes());
    }

    public function test_registration_validation_includes_recaptcha_when_configured(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'test-secret-key');
        
        // Simulation d'une requête POST sans token reCAPTCHA
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertSessionHasErrors(['recaptcha_token']);
    }

    public function test_registration_validation_ignores_recaptcha_when_not_configured(): void
    {
        Config::set('services.recaptcha.site_key', '');
        Config::set('services.recaptcha.secret_key', '');
        
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        // Devrait rediriger sans erreur car reCAPTCHA n'est pas requis
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'test2@example.com',
        ]);
    }
}