<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TrackRecordResource;
use App\Policies\TrackRecordPolicy;
use App\Services\TrackRecordService;
use Illuminate\Http\JsonResponse;

class TrackRecordController extends Controller
{
    public function __construct(
        private readonly TrackRecordService $trackRecordService
    ) {}

    /**
     * Display a listing of track records from work orders.
     * Supports filtering by date range and search.
     * 
     * Query Parameters:
     * - start_date: Filter by start date (Y-m-d format)
     * - end_date: Filter by end date (Y-m-d format)
     * - search: Search by worker name, scope of work, customer, or work location
     * - per_page: Number of items per page (default: 15)
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', TrackRecordPolicy::class);

        $trackRecords = $this->trackRecordService->getTrackRecords(
            startDate: request('start_date'),
            endDate: request('end_date'),
            search: request('search'),
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => TrackRecordResource::collection($trackRecords),
            'meta' => [
                'current_page' => $trackRecords->currentPage(),
                'last_page' => $trackRecords->lastPage(),
                'per_page' => $trackRecords->perPage(),
                'total' => $trackRecords->total(),
            ],
        ]);
    }
}
