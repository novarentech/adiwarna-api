<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\WorkAssignment;
use Tests\TestCase;

class WorkAssignmentTest extends TestCase
{
    public function test_can_list_work_assignments()
    {
        WorkAssignment::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/work-assignments');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_work_assignments()
    {
        $customer = Customer::factory()->create(['name' => 'Search Target']);
        WorkAssignment::factory()->create(['customer_id' => $customer->id]);
        WorkAssignment::factory()->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/work-assignments?search=Target');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_work_assignment()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();
        $location = CustomerLocation::factory()->create(['customer_id' => $customer->id]);

        $waData = [
            'customer_id' => $customer->id,
            'customer_location_id' => $location->id,
            'assignment_no' => '001',
            'assignment_year' => 2024,
            'date' => '2024-03-06',
            'ref_no' => 'REF-001',
            'ref_year' => 2024,
            'scope' => 'Basic Scope',
            'auth_name' => 'Boss',
            'auth_pos' => 'Manager',
            'workers' => [
                [
                    'worker_name' => 'John Doe',
                    'position' => 'Lead Technician',
                ],
            ],
        ];

        $response = $this->postJson('/api/work-assignments', $waData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('work_assignments', [
            'assignment_no' => '001',
            'assignment_year' => '2024',
        ]);
    }

    public function test_can_show_work_assignment()
    {
        $this->actingAsAdmin();
        $wa = WorkAssignment::factory()->create();

        $response = $this->getJson("/api/work-assignments/{$wa->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $wa->id);
    }

    public function test_can_update_work_assignment()
    {
        $this->actingAsAdmin();
        $wa = WorkAssignment::factory()->create(['assignment_no' => 'OLD']);

        $response = $this->putJson("/api/work-assignments/{$wa->id}", [
            'customer_id' => $wa->customer_id,
            'customer_location_id' => $wa->customer_location_id,
            'assignment_no' => 'NEW',
            'assignment_year' => $wa->assignment_year,
            'date' => $wa->date->format('Y-m-d'),
            // DB requires these to be not null
            'ref_no' => $wa->ref_no,
            'ref_year' => $wa->ref_year,
            'scope' => $wa->scope,
            'auth_name' => $wa->auth_name,
            'auth_pos' => $wa->auth_pos,
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('work_assignments', [
            'id' => $wa->id,
            'assignment_no' => 'NEW',
        ]);
    }

    public function test_can_delete_work_assignment()
    {
        $this->actingAsAdmin();
        $wa = WorkAssignment::factory()->create();

        $response = $this->deleteJson("/api/work-assignments/{$wa->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('work_assignments', ['id' => $wa->id]);
    }
}
