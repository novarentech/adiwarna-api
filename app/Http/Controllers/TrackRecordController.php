<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrackRecordResource;
use Illuminate\Http\JsonResponse;

class TrackRecordController extends Controller
{
    public function __construct() {}

    /**
     * Display a listing of track records from work orders.
     * Supports filtering by date range and search.
     * 
     * Query Parameters:
     * - start_date: Filter by start date (Y-m-d format)
     * - end_date: Filter by end date (Y-m-d format)
     * - search: Search by worker name, scope of work, customer, or work location
     * - per_page: Number of items per page (default: 15)
     * - sortBy: Sort by column (format: column:asc|desc, e.g., date:desc)
     */
    public function index(): JsonResponse
    {
        //

        $trackRecords = \App\Models\WorkOrder::query()
            ->withRelations()
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->search(request('search'))
            ->sort(request('sortBy'))
            ->paginate(request('per_page', 15));

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
