<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PurchaseOrder;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    public function test_can_list_purchase_orders()
    {
        PurchaseOrder::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/purchase-orders');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_purchase_orders()
    {
        PurchaseOrder::factory()->create(['pic_name' => 'Search Target']);
        PurchaseOrder::factory()->create(['pic_name' => 'Other Name']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/purchase-orders?search=Target');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_purchase_order()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();

        $poData = [
            'customer_id' => $customer->id,
            'po_no' => '001',
            'po_year' => 2024,
            'date' => '2024-03-06',
            'required_date' => '2024-04-06',
            'pic_name' => 'John PIC',
            'pic_phone' => '08123',
            'auth_name' => 'Boss',
            'auth_pos' => 'Manager',
            'items' => [
                [
                    'description' => 'Project A',
                    'quantity' => 1,
                    'unit' => 'Set',
                    'rate' => 5000000,
                ],
            ],
        ];

        $response = $this->postJson('/api/purchase-orders', $poData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('purchase_orders', [
            'po_no' => '001',
            'po_year' => '2024',
        ]);
    }

    public function test_can_show_purchase_order()
    {
        $this->actingAsAdmin();
        $po = PurchaseOrder::factory()->create();

        $response = $this->getJson("/api/purchase-orders/{$po->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $po->id);
    }

    public function test_can_update_purchase_order()
    {
        $this->actingAsAdmin();
        $po = PurchaseOrder::factory()->create(['pic_name' => 'Old Name']);

        $response = $this->putJson("/api/purchase-orders/{$po->id}", [
            'customer_id' => $po->customer_id,
            'date' => $po->date->format('Y-m-d'),
            'po_no' => $po->po_no,
            'po_year' => $po->po_year,
            'pic_name' => 'New Name',
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('purchase_orders', [
            'id' => $po->id,
            'pic_name' => 'New Name',
        ]);
    }

    public function test_can_delete_purchase_order()
    {
        $this->actingAsAdmin();
        $po = PurchaseOrder::factory()->create();

        $response = $this->deleteJson("/api/purchase-orders/{$po->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('purchase_orders', ['id' => $po->id]);
    }
}
