<?php

namespace Tests\Feature;

use App\Models\Customer;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_can_list_customers()
    {
        Customer::factory()->count(20)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/customers');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_customers()
    {
        Customer::factory()->create(['name' => 'Search Target']);
        Customer::factory()->create(['name' => 'Other Name']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/customers?search=Target');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_customer()
    {
        $this->actingAsAdmin();

        $customerData = [
            'name' => 'New Customer',
            'customer_no' => 'CUST001',
            'email' => 'customer@example.com',
            'phone_number' => '08123456789',
            'address' => 'Jl. Merdeka No. 1',
        ];

        $response = $this->postJson('/api/customers', $customerData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('customers', [
            'name' => 'New Customer',
            'customer_no' => 'CUST001',
        ]);
    }

    public function test_can_show_customer()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $customer->id);
    }

    public function test_can_update_customer()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/customers/{$customer->id}", [
            'name' => 'New Name',
            'customer_no' => $customer->customer_no,
            'phone_number' => $customer->phone_number,
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_customer()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
}
