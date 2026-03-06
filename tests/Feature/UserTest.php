<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_can_list_users()
    {
        User::factory()->count(20)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/users');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_users()
    {
        $user1 = User::factory()->create(['name' => 'Search Target']);
        $user2 = User::factory()->create(['name' => 'Other Name']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/users?search=Target');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $user1->id);
    }

    public function test_can_filter_users_by_usertype()
    {
        // Clear existing if any (RefreshDatabase handles this but just to be safe in logic check)
        // User::query()->delete(); 
        
        // Create 2 admins
        User::factory()->count(2)->admin()->create();
        // Create 3 teknisi
        User::factory()->count(3)->teknisi()->create();
        // actingAsAdmin creates 1 more admin -> Total 3 admins
        $this->actingAsAdmin();

        $response = $this->getJson('/api/users?usertype=admin');

        $this->assertApiSuccess($response);
        
        // Let's debug the response if it fails
        if ($response->json('data') && count($response->json('data')) !== 3) {
             // dump($response->json('data'));
        }

        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_user()
    {
        $this->actingAsAdmin();

        $userData = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'usertype' => UserType::ADMIN->value, // Use Admin for variety
        ];

        $response = $this->postJson('/api/users', $userData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
        ]);
    }

    public function test_create_user_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'usertype']);
    }

    public function test_can_show_user()
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_can_update_user()
    {
        $this->actingAsAdmin();
        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
            'email' => $user->email,
            'phone' => $user->phone,
            'usertype' => $user->usertype->value,
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_user()
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $this->assertApiSuccess($response);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_unauthenticated_cannot_access_users()
    {
        $this->getJson('/api/users')->assertStatus(401);
    }
}
