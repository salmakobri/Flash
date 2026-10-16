<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited(): void
    {
        $payload = ['email' => 'nobody@test.com', 'password' => 'wrong-password'];

        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/login', $payload)->assertStatus(401);
        }

        $this->postJson('/api/login', $payload)->assertStatus(429);
    }

    public function test_driver_without_profile_gets_403_not_a_crash(): void
    {
        $user = User::create([
            'name' => 'No Profile',
            'email' => 'noprofile@test.com',
            'password' => 'password',
            'role' => 'chauffeur',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/mes-livraisons')->assertForbidden();
    }
}