<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertApiSuccess($response)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'phone', 'usertype'],
                    'token',
                ],
            ]);
    }

    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_login_fails_with_nonexistent_email()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_login_requires_email_and_password()
    {
        $response = $this->postJson('/api/auth/login', []);

        // LoginRequest handles this with 422 before controller
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_authenticated_user_can_get_profile()
    {
        $user = $this->actingAsTeknisi();

        $response = $this->getJson('/api/auth/me');

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $tokenPayload = $user->createToken('test-token');
        $token = $tokenPayload->plainTextToken;
        
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/auth/logout');

        $this->assertApiSuccess($response);
        
        // Assert token revoked in database
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_unauthenticated_cannot_access_me()
    {
        $this->getJson('/api/auth/me')->assertStatus(401);
    }
}
