<?php

namespace Tests\Feature;

use App\Models\EquipmentGeneral;
use Tests\TestCase;

class EquipmentGeneralTest extends TestCase
{
    public function test_can_list_general_equipment()
    {
        EquipmentGeneral::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/equipment/general');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_general_equipment()
    {
        EquipmentGeneral::factory()->create(['description' => 'Specific Drill']);
        EquipmentGeneral::factory()->create(['description' => 'Other Tool']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/equipment/general?search=Drill');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_general_equipment()
    {
        $this->actingAsAdmin();

        $data = [
            'description' => 'Multimeter',
            'merk_type' => 'Fluke 87V',
            'serial_number' => 'SN12345678',
            'calibration_date' => '2024-01-01',
            'duration_months' => 12,
            'calibration_agency' => 'internal',
            'condition' => 'ok',
        ];

        $response = $this->postJson('/api/equipment/general', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('equipment_general', [
            'serial_number' => 'SN12345678',
        ]);
    }

    public function test_can_show_general_equipment()
    {
        $this->actingAsAdmin();
        $equipment = EquipmentGeneral::factory()->create();

        $response = $this->getJson("/api/equipment/general/{$equipment->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $equipment->id);
    }

    public function test_can_update_general_equipment()
    {
        $this->actingAsAdmin();
        $equipment = EquipmentGeneral::factory()->create(['description' => 'OLD']);

        $response = $this->putJson("/api/equipment/general/{$equipment->id}", [
            'description' => 'NEW',
            'merk_type' => $equipment->merk_type,
            'serial_number' => 'NEW-SN-' . uniqid(),
            'calibration_date' => $equipment->calibration_date->format('Y-m-d'),
            'duration_months' => $equipment->duration_months->value,
            'calibration_agency' => $equipment->calibration_agency->value,
            'condition' => $equipment->condition->value,
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('equipment_general', [
            'id' => $equipment->id,
            'description' => 'NEW',
        ]);
    }

    public function test_can_delete_general_equipment()
    {
        $this->actingAsAdmin();
        $equipment = EquipmentGeneral::factory()->create();

        $response = $this->deleteJson("/api/equipment/general/{$equipment->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('equipment_general', ['id' => $equipment->id]);
    }
}
