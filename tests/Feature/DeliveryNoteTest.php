<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DeliveryNote;
use Tests\TestCase;

class DeliveryNoteTest extends TestCase
{
    public function test_can_list_delivery_notes()
    {
        DeliveryNote::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/delivery-notes');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_create_delivery_note_for_customer()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();

        $data = [
            'dn_no' => 'DN-2024-001',
            'isOther' => false,
            'date' => '2024-03-06',
            'customer_id' => $customer->id,
            'wo_no' => 'WO-001',
            'vehicle_plate' => 'B 1234 ABC',
            'items' => [
                [
                    'item_name' => 'Delivered Item',
                    'qty' => 5,
                ],
            ],
        ];

        $response = $this->postJson('/api/delivery-notes', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('delivery_notes', [
            'dn_no' => 'DN-2024-001',
            'customer_id' => $customer->id,
        ]);
    }

    public function test_can_create_delivery_note_for_other()
    {
        $this->actingAsAdmin();

        $data = [
            'dn_no' => 'DN-OTHER-001',
            'isOther' => true,
            'name' => 'External Recipient',
            'address' => 'Test Address',
            'date' => '2024-03-06',
            'wo_no' => 'WO-999',
            'vehicle_plate' => 'B 4321 CBA',
            'items' => [
                [
                    'item_name' => 'Other Item',
                    'qty' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/delivery-notes', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('delivery_notes', [
            'dn_no' => 'DN-OTHER-001',
            'other' => json_encode([
                'phone' => '',
                'address' => 'Test Address',
                'name' => 'External Recipient',
            ]),
        ]);
    }

    public function test_can_show_delivery_note()
    {
        $this->actingAsAdmin();
        $dn = DeliveryNote::factory()->create();

        $response = $this->getJson("/api/delivery-notes/{$dn->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $dn->id);
    }

    public function test_can_update_delivery_note()
    {
        $this->actingAsAdmin();
        $dn = DeliveryNote::factory()->create(['dn_no' => 'OLD']);

        $response = $this->putJson("/api/delivery-notes/{$dn->id}", [
            'dn_no' => 'NEW',
            'date' => $dn->date->format('Y-m-d'),
            'wo_no' => 'WO-NEW',
            'vehicle_plate' => 'B 5555 XYZ',
            'items' => [
                [
                    'item_name' => 'Updated Item',
                    'qty' => 10,
                ],
            ],
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('delivery_notes', [
            'id' => $dn->id,
            'dn_no' => 'NEW',
        ]);
    }

    public function test_can_delete_delivery_note()
    {
        $this->actingAsAdmin();
        $dn = DeliveryNote::factory()->create();

        $response = $this->deleteJson("/api/delivery-notes/{$dn->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('delivery_notes', ['id' => $dn->id]);
    }
}
