<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home', absolute: false));
    }

    public function test_users_can_authenticate_with_remember_me_option(): void
    {
        $user = User::factory()->create([
            'last_login_at' => null, 
            'last_ip_address' => null
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home', absolute: false));
        
        // Check for remember token in the database that should be present when remember is used
        $this->assertNotNull($user->fresh()->remember_token);
        
        // Check that last_login_at has been updated
        $this->assertNotNull($user->fresh()->last_login_at);
        
        // Check that last_ip_address has been updated
        $this->assertNotNull($user->fresh()->last_ip_address);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user instanceof Authenticatable ? $user : null)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}