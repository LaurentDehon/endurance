<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegisterWithRecaptchaTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_loads_with_recaptcha_script_when_configured(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        
        $response = $this->get('/register');
        
        $response->assertStatus(200);
        $response->assertSee('recaptcha/api.js', false);
        $response->assertSee('recaptcha_token', false);
    }

    public function test_register_page_loads_without_recaptcha_when_not_configured(): void
    {
        Config::set('services.recaptcha.site_key', '');
        
        $response = $this->get('/register');
        
        $response->assertStatus(200);
        $response->assertDontSee('recaptcha/api.js', false);
        $response->assertDontSee('recaptcha_token', false);
    }

    public function test_registration_succeeds_without_recaptcha_when_not_configured(): void
    {
        Config::set('services.recaptcha.site_key', '');
        Config::set('services.recaptcha.secret_key', '');
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        $response = $this->post('/register', $userData);
        
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_registration_fails_without_recaptcha_token_when_configured(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'test-secret-key');
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        $response = $this->post('/register', $userData);
        
        $response->assertSessionHasErrors(['recaptcha_token']);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_registration_succeeds_with_valid_recaptcha_token(): void
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
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'recaptcha_token' => 'valid-recaptcha-token',
        ];
        
        $response = $this->post('/register', $userData);
        
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_registration_fails_with_invalid_recaptcha_token(): void
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
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'recaptcha_token' => 'invalid-recaptcha-token',
        ];
        
        $response = $this->post('/register', $userData);
        
        $response->assertSessionHasErrors(['recaptcha_token']);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_registration_fails_with_low_recaptcha_score(): void
    {
        Config::set('services.recaptcha.site_key', 'test-site-key');
        Config::set('services.recaptcha.secret_key', 'test-secret-key');
        
        // Mock avec un score trop bas
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.2, // Score trop bas (< 0.5)
                'action' => 'register'
            ])
        ]);
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'recaptcha_token' => 'low-score-token',
        ];
        
        $response = $this->post('/register', $userData);
        
        $response->assertSessionHasErrors(['recaptcha_token']);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
    }
}
