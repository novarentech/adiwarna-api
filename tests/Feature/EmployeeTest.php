<?php

namespace Tests\Feature;

use App\Models\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    public function test_can_list_employees()
    {
        Employee::factory()->count(20)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/employees');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_employees()
    {
        Employee::factory()->create(['name' => 'John Doe']);
        Employee::factory()->create(['name' => 'Jane Smith']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/employees?search=John');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_employee()
    {
        $this->actingAsAdmin();

        $employeeData = [
            'name' => 'New Employee',
            'employee_no' => 'EMP001',
            'position' => 'Manager',
        ];

        $response = $this->postJson('/api/employees', $employeeData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('employees', [
            'name' => 'New Employee',
            'employee_no' => 'EMP001',
        ]);
    }

    public function test_can_show_employee()
    {
        $this->actingAsAdmin();
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $employee->id);
    }

    public function test_can_update_employee()
    {
        $this->actingAsAdmin();
        $employee = Employee::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/employees/{$employee->id}", [
            'name' => 'New Name',
            'employee_no' => $employee->employee_no,
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_employee()
    {
        $this->actingAsAdmin();
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employees/{$employee->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }
}
