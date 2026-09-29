<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscription_reussie(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
    }

    public function test_connexion_reussie(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_connexion_mauvais_mot_de_passe(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'faux',
        ]);

        $response->assertStatus(401);
    }

    public function test_me_sans_token(): void
    {
        $this->getJson('/api/me')->assertStatus(401);
    }

    public function test_me_avec_access_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('access_token', ['access-api'])->plainTextToken;

        $this->withToken($token)->getJson('/api/me')->assertStatus(200);
    }

    public function test_me_avec_refresh_token_refuse(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('refresh_token', ['issue-access-token'])->plainTextToken;

        $this->withToken($token)->getJson('/api/me')->assertStatus(403);
    }
}