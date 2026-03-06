<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Quotation;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    public function test_can_list_quotations()
    {
        Quotation::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/quotations');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_quotations()
    {
        Quotation::factory()->create(['ref_no' => 'REF-001']);
        Quotation::factory()->create(['ref_no' => 'REF-999']);
        $this->actingAsAdmin();

        $response = $this->getJson('/api/quotations?search=001');

        $this->assertApiSuccess($response)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_quotation()
    {
        $this->actingAsAdmin();
        $customer = Customer::factory()->create();

        $quotationData = [
            'customer_id' => $customer->id,
            'date' => '2024-03-06',
            'ref_no' => '001',
            'ref_year' => 2024,
            'pic_name' => 'John PIC',
            'pic_phone' => '08123',
            'subject' => 'Service Quotation',
            'top' => '30 Days',
            'valid_until' => 30,
            'auth_name' => 'Boss',
            'auth_position' => 'Manager',
            'items' => [
                [
                    'description' => 'Service A',
                    'quantity' => 1,
                    'unit' => 'Lot',
                    'rate' => 1000000,
                ],
            ],
        ];

        $response = $this->postJson('/api/quotations', $quotationData);

        $this->assertApiSuccess($response, 201);
        $this->assertDatabaseHas('quotations', [
            'ref_no' => '001',
            'ref_year' => '2024',
        ]);
    }

    public function test_can_show_quotation()
    {
        $this->actingAsAdmin();
        $quotation = Quotation::factory()->create();

        $response = $this->getJson("/api/quotations/{$quotation->id}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('data.id', $quotation->id);
    }

    public function test_can_update_quotation()
    {
        $this->actingAsAdmin();
        $quotation = Quotation::factory()->create(['subject' => 'Old Subject']);

        $response = $this->putJson("/api/quotations/{$quotation->id}", [
            'customer_id' => $quotation->customer_id,
            'date' => $quotation->date->format('Y-m-d'),
            'ref_no' => $quotation->ref_no,
            'ref_year' => $quotation->ref_year,
            'subject' => 'New Subject',
        ]);

        $this->assertApiSuccess($response);
        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'subject' => 'New Subject',
        ]);
    }

    public function test_can_delete_quotation()
    {
        $this->actingAsAdmin();
        $quotation = Quotation::factory()->create();

        $response = $this->deleteJson("/api/quotations/{$quotation->id}");

        $this->assertApiSuccess($response);
        $this->assertSoftDeleted('quotations', ['id' => $quotation->id]);
    }
}
