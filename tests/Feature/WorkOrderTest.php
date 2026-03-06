<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\Employee;
use App\Models\WorkOrder;
use Tests\TestCase;

class WorkOrderTest extends TestCase
{
    public function test_can_list_work_orders()
    {
        WorkOrder::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/work-orders');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_work_orders()
    {
        $customer = Customer::factory()->create(['name' => 'Search Target']);
        WorkOrder::factory()->create(['customer_id' => $customer->id]);
        WorkOrder::factory()->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/work-orders?search=Target');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_work_order()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();
        $location = CustomerLocation::factory()->create(['customer_id' => $customer->id]);
        $employee = Employee::factory()->create();

        $woData = [
            'customer_id' => $customer->id,
            'customer_location_id' => $location->id,
            'work_order_no' => '001',
            'work_order_year' => 2024,
            'date' => '2024-03-06',
            'scope_of_work' => ['Basic Service'],
            'employees' => [
                ['employee_id' => $employee->id]
            ],
        ];

        $response = $this->postJson('/api/work-orders', $woData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('work_orders', [
            'work_order_no' => '001',
            'work_order_year' => 2024,
        ]);
    }

    public function test_can_show_work_order()
    {
        $this->actingAsAdmin();
        $wo = WorkOrder::factory()->create();

        $response = $this->getJson("/api/work-orders/{$wo->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $wo->id);
    }

    public function test_can_update_work_order()
    {
        $this->actingAsAdmin();
        $wo = WorkOrder::factory()->create(['work_order_no' => 'OLD']);
        $employee = Employee::factory()->create();

        $response = $this->putJson("/api/work-orders/{$wo->id}", [
            'customer_id' => $wo->customer_id,
            'customer_location_id' => $wo->customer_location_id,
            'work_order_no' => 'NEW',
            'work_order_year' => $wo->work_order_year,
            'date' => $wo->date->format('Y-m-d'),
            'scope_of_work' => $wo->scope_of_work,
            'employees' => [
                ['employee_id' => $employee->id]
            ],
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('work_orders', [
            'id' => $wo->id,
            'work_order_no' => 'NEW',
        ]);
    }

    public function test_can_delete_work_order()
    {
        $this->actingAsAdmin();
        $wo = WorkOrder::factory()->create();

        $response = $this->deleteJson("/api/work-orders/{$wo->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('work_orders', ['id' => $wo->id]);
    }
}
