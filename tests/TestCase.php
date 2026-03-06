<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Authenticate as an admin.
     */
    protected function actingAsAdmin(): User
    {
        $user = User::factory()->admin()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Authenticate as a teknisi.
     */
    protected function actingAsTeknisi(): User
    {
        $user = User::factory()->teknisi()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Assert standard API success structure.
     */
    protected function assertApiSuccess($response, $status = 200)
    {
        return $response->assertStatus($status)
            ->assertJson(['success' => true]);
    }

    /**
     * Assert API paginated structure.
     */
    protected function assertApiPaginated($response)
    {
        return $response->assertJsonStructure([
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);
    }
}
