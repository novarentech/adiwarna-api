<?php

namespace Tests\Feature;

use App\Models\MaterialReceivingReport;
use Tests\TestCase;

class MaterialReceivingReportTest extends TestCase
{
    public function test_can_list_material_receiving_reports()
    {
        MaterialReceivingReport::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/material-receiving-reports');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_create_material_receiving_report()
    {
        $this->actingAsAdmin();

        $data = [
            'po_no' => 'PO-2024-001',
            'receiving_date' => '2024-03-06',
            'supplier' => 'Test Supplier',
            'items' => [
                [
                    'description' => 'Received Item',
                    'order_qty' => 10,
                    'received_qty' => 10,
                    'remarks' => 'good',
                ],
            ],
        ];

        $response = $this->postJson('/api/material-receiving-reports', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('material_receiving_reports', [
            'po_no' => 'PO-2024-001',
        ]);
    }

    public function test_can_show_material_receiving_report()
    {
        $this->actingAsAdmin();
        $report = MaterialReceivingReport::factory()->create();

        $response = $this->getJson("/api/material-receiving-reports/{$report->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $report->id);
    }

    public function test_can_update_material_receiving_report()
    {
        $this->actingAsAdmin();
        $report = MaterialReceivingReport::factory()->create(['po_no' => 'OLD']);

        $response = $this->putJson("/api/material-receiving-reports/{$report->id}", [
            'po_no' => 'NEW',
            'receiving_date' => $report->receiving_date->format('Y-m-d'),
            'items' => [
                [
                    'description' => 'Updated Received Item',
                    'order_qty' => 20,
                    'received_qty' => 15,
                    'remarks' => 'reject',
                ],
            ],
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('material_receiving_reports', [
            'id' => $report->id,
            'po_no' => 'NEW',
        ]);
    }

    public function test_can_delete_material_receiving_report()
    {
        $this->actingAsAdmin();
        $report = MaterialReceivingReport::factory()->create();

        $response = $this->deleteJson("/api/material-receiving-reports/{$report->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('material_receiving_reports', ['id' => $report->id]);
    }
}
