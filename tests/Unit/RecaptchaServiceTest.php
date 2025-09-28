<?php

namespace Tests\Unit;

use App\Services\RecaptchaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaServiceTest extends TestCase
{
    use RefreshDatabase;

    private RecaptchaService $recaptchaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recaptchaService = new RecaptchaService();
    }

    public function test_is_configured_returns_true_when_keys_are_set(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'test-secret-key');
        
        $service = new RecaptchaService();
        
        $this->assertTrue($service->isConfigured());
    }

    public function test_is_configured_returns_false_when_keys_are_missing(): void
    {
        Config::set('services.recaptcha.site_key', '');
        Config::set('services.recaptcha.secret_key', '');
        
        $service = new RecaptchaService();
        
        $this->assertFalse($service->isConfigured());
    }

    public function test_get_site_key_returns_configured_key(): void
    {
        $siteKey = 'test-site-key-123';
        Config::set('services.recaptcha.site_key', $siteKey);
        
        $service = new RecaptchaService();
        
        $this->assertEquals($siteKey, $service->getSiteKey());
    }

    public function test_verify_returns_error_when_secret_key_not_configured(): void
    {
        Config::set('services.recaptcha.secret_key', '');
        
        $service = new RecaptchaService();
        $result = $service->verify('test-token');
        
        $this->assertFalse($result['success']);
        $this->assertEquals('reCAPTCHA not configured', $result['error']);
    }

    public function test_verify_returns_error_when_token_is_empty(): void
    {
        Config::set('services.recaptcha.secret_key', 'test-secret');
        
        $service = new RecaptchaService();
        $result = $service->verify('');
        
        $this->assertFalse($result['success']);
        $this->assertEquals('Missing reCAPTCHA token', $result['error']);
    }

    public function test_verify_returns_success_with_valid_response(): void
    {
        Config::set('services.recaptcha.secret_key', 'test-secret');
        
        // Mock de la réponse HTTP de Google
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.8,
                'action' => 'register',
                'hostname' => 'localhost'
            ])
        ]);
        
        $service = new RecaptchaService();
        $result = $service->verify('valid-token', 'register');
        
        $this->assertTrue($result['success']);
        $this->assertEquals(0.8, $result['score']);
        $this->assertEquals('register', $result['action']);
    }

    public function test_verify_returns_error_with_low_score(): void
    {
        Config::set('services.recaptcha.secret_key', 'test-secret');
        
        // Mock avec un score trop bas
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.2, // Score trop bas
                'action' => 'register'
            ])
        ]);
        
        $service = new RecaptchaService();
        $result = $service->verify('valid-token', 'register');
        
        $this->assertFalse($result['success']);
        $this->assertEquals('reCAPTCHA score too low', $result['error']);
        $this->assertEquals(0.2, $result['score']);
    }

    public function test_verify_returns_error_with_wrong_action(): void
    {
        Config::set('services.recaptcha.secret_key', 'test-secret');
        
        // Mock avec une action différente
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.8,
                'action' => 'login' // Action différente
            ])
        ]);
        
        $service = new RecaptchaService();
        $result = $service->verify('valid-token', 'register');
        
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid reCAPTCHA action', $result['error']);
    }

    public function test_verify_handles_google_api_errors(): void
    {
        Config::set('services.recaptcha.secret_key', 'test-secret');
        
        // Mock d'une réponse d'erreur de Google
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-secret']
            ])
        ]);
        
        $service = new RecaptchaService();
        $result = $service->verify('test-token');
        
        $this->assertFalse($result['success']);
        $this->assertEquals('reCAPTCHA verification failed', $result['error']);
    }
}
