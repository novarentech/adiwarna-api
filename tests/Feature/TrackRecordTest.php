<?php

namespace Tests\Feature;

use App\Models\WorkOrder;
use Tests\TestCase;

class TrackRecordTest extends TestCase
{
    public function test_can_list_track_records()
    {
        WorkOrder::factory()->count(10)->create();
        $this->actingAsAdmin();

        $response = $this->getJson('/api/track-records');

        $this->assertApiSuccess($response);
        $this->assertApiPaginated($response);
    }

    public function test_can_search_track_records()
    {
        // TrackRecord search depends on WorkOrder::scopeSearch
        // scopeSearch for WorkOrder includes customer name
        $workOrder = WorkOrder::factory()->create();
        $customerName = $workOrder->customer->name;

        $this->actingAsAdmin();

        $response = $this->getJson("/api/track-records?search={$customerName}");

        $this->assertApiSuccess($response)
            ->assertJsonPath('meta.total', 1);
    }

    public function test_can_filter_track_records_by_date()
    {
        WorkOrder::factory()->create(['date' => '2024-01-01']);
        WorkOrder::factory()->create(['date' => '2024-02-01']);
        
        $this->actingAsAdmin();

        $response = $this->getJson('/api/track-records?start_date=2024-01-01&end_date=2024-01-31');

        $this->assertApiSuccess($response)
            ->assertJsonPath('meta.total', 1);
    }
}
