<?php

namespace Tests\Feature;

use App\Models\PurchaseRequisition;
use Tests\TestCase;

class PurchaseRequisitionTest extends TestCase
{
    public function test_can_list_purchase_requisitions()
    {
        PurchaseRequisition::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/purchase-requisitions');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_create_purchase_requisition()
    {
        $this->actingAsAdmin();

        $data = [
            'pr_no' => 'PR-2024-001',
            'date' => '2024-03-06',
            'supplier' => 'online',
            'routing' => 'online',
            'items' => [
                [
                    'qty' => 5,
                    'unit' => 'pcs',
                    'description' => 'Test Item',
                    'unit_price' => 10000,
                ],
            ],
        ];

        $response = $this->postJson('/api/purchase-requisitions', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('purchase_requisitions', [
            'pr_no' => 'PR-2024-001',
        ]);
    }

    public function test_supplier_and_routing_must_match()
    {
        $this->actingAsAdmin();

        $data = [
            'pr_no' => 'PR-ERROR',
            'date' => '2024-03-06',
            'supplier' => 'online',
            'routing' => 'offline', // Mismatch
            'items' => [['qty' => 1, 'unit' => 'pcs', 'description' => 'x', 'unit_price' => 1]],
        ];

        $response = $this->postJson('/api/purchase-requisitions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['supplier', 'routing']);
    }

    public function test_can_show_purchase_requisition()
    {
        $this->actingAsAdmin();
        $pr = PurchaseRequisition::factory()->create();

        $response = $this->getJson("/api/purchase-requisitions/{$pr->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $pr->id);
    }

    public function test_can_update_purchase_requisition()
    {
        $this->actingAsAdmin();
        $pr = PurchaseRequisition::factory()->create(['pr_no' => 'OLD']);

        $response = $this->putJson("/api/purchase-requisitions/{$pr->id}", [
            'pr_no' => 'NEW',
            'date' => $pr->date->format('Y-m-d'),
            'supplier' => 'offline',
            'routing' => 'offline',
            'items' => [
                [
                    'qty' => 10,
                    'unit' => 'box',
                    'description' => 'Updated Item',
                    'unit_price' => 20000,
                ],
            ],
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('purchase_requisitions', [
            'id' => $pr->id,
            'pr_no' => 'NEW',
        ]);
    }

    public function test_can_delete_purchase_requisition()
    {
        $this->actingAsAdmin();
        $pr = PurchaseRequisition::factory()->create();

        $response = $this->deleteJson("/api/purchase-requisitions/{$pr->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('purchase_requisitions', ['id' => $pr->id]);
    }
}
