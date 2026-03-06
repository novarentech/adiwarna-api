<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\EquipmentGeneral;
use App\Models\EquipmentProject;
use Tests\TestCase;

class EquipmentProjectTest extends TestCase
{
    public function test_can_list_project_equipment()
    {
        EquipmentProject::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/equipment/project');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_create_project_equipment()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();
        $equipments = EquipmentGeneral::factory()->count(2)->create();

        $data = [
            'customer_id' => $customer->id,
            'project_date' => '2024-03-06',
            'prepared_by' => 'John Prep',
            'verified_by' => 'Jane Ver',
            'equipment_ids' => $equipments->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/equipment/project', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('equipment_projects', [
            'customer_id' => $customer->id,
            'prepared_by' => 'John Prep',
        ]);
    }

    public function test_can_show_project_equipment()
    {
        $this->actingAsAdmin();
        $project = EquipmentProject::factory()->create();

        $response = $this->getJson("/api/equipment/project/{$project->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $project->id);
    }

    public function test_can_update_project_equipment()
    {
        $this->actingAsAdmin();
        $project = EquipmentProject::factory()->create(['prepared_by' => 'OLD']);
        $newEquipments = EquipmentGeneral::factory()->count(1)->create();

        $response = $this->putJson("/api/equipment/project/{$project->id}", [
            'customer_id' => $project->customer_id,
            'project_date' => $project->project_date->format('Y-m-d'),
            'prepared_by' => 'NEW',
            'verified_by' => 'Jane Updated',
            'equipment_ids' => $newEquipments->pluck('id')->toArray(),
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('equipment_projects', [
            'id' => $project->id,
            'prepared_by' => 'NEW',
        ]);
    }

    public function test_can_delete_project_equipment()
    {
        $this->actingAsAdmin();
        $project = EquipmentProject::factory()->create();

        $response = $this->deleteJson("/api/equipment/project/{$project->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('equipment_projects', ['id' => $project->id]);
    }
}
