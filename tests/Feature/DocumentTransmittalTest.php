<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DocumentTransmittal;
use Tests\TestCase;

class DocumentTransmittalTest extends TestCase
{
    public function test_can_list_document_transmittals()
    {
        DocumentTransmittal::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/document-transmittals');

        $this->assertApiSuccess($response);
        // Note: The controller manually builds the response, so assertApiPaginated might not match perfectly if it expects 'meta'
        $response->assertJsonStructure([
            'success',
            'data',
            'meta' => ['current_page', 'last_page', 'per_page', 'total']
        ]);
    }

    public function test_can_search_document_transmittals()
    {
        DocumentTransmittal::factory()->create(['pic_name' => 'Search Target']);
        DocumentTransmittal::factory()->create(['pic_name' => 'Other']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/document-transmittals?search=Target');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_document_transmittal()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();

        $data = [
            'name' => 'Test Transmittal',
            'ta_no' => '001/VII/2024',
            'date' => '2024-07-24',
            'customer_id' => $customer->id,
            'pic_name' => 'John PIC',
            'report_type' => 'Standard',
            'documents' => [
                [
                    'wo_number' => 'WO-001',
                    'wo_year' => 2024,
                    'location' => 'Jakarta',
                ],
            ],
        ];

        $response = $this->postJson('/api/document-transmittals', $data);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('transmittals', [
            'name' => 'Test Transmittal',
            'ta_no' => '001/VII/2024',
        ]);
    }

    public function test_can_show_document_transmittal()
    {
        $this->actingAsAdmin();
        $transmittal = DocumentTransmittal::factory()->create();

        $response = $this->getJson("/api/document-transmittals/{$transmittal->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $transmittal->id);
    }

    public function test_can_update_document_transmittal()
    {
        $this->actingAsAdmin();
        $transmittal = DocumentTransmittal::factory()->create(['name' => 'OLD']);

        $response = $this->putJson("/api/document-transmittals/{$transmittal->id}", [
            'name' => 'NEW',
            'ta_no' => '002/VIII/2024',
            'date' => '2024-08-24',
            'customer_id' => $transmittal->customer_id,
            'pic_name' => 'New PIC',
            'report_type' => 'Updated',
            'documents' => [
                [
                    'wo_number' => 'WO-NEW',
                    'wo_year' => 2024,
                    'location' => 'Bandung',
                ],
            ],
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('transmittals', [
            'id' => $transmittal->id,
            'name' => 'NEW',
        ]);
    }

    public function test_can_delete_document_transmittal()
    {
        $this->actingAsAdmin();
        $transmittal = DocumentTransmittal::factory()->create();

        $response = $this->deleteJson("/api/document-transmittals/{$transmittal->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('transmittals', ['id' => $transmittal->id]);
    }
}
