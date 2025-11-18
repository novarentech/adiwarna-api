<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTrackRecordRequest;
use App\Http\Requests\Api\V1\UpdateTrackRecordRequest;
use App\Http\Resources\V1\TrackRecordCollection;
use App\Http\Resources\V1\TrackRecordResource;
use App\Models\TrackRecord;
use App\Services\TrackRecordService;
use Illuminate\Http\JsonResponse;

class TrackRecordController extends Controller
{
    public function __construct(
        private readonly TrackRecordService $trackRecordService
    ) {
        $this->authorizeResource(TrackRecord::class, 'track_record');
    }

    /**
     * Display a listing of track records.
     */
    public function index(): JsonResponse
    {
        $trackRecords = $this->trackRecordService->getPaginatedTrackRecords(
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => new TrackRecordCollection($trackRecords),
            'meta' => [
                'current_page' => $trackRecords->currentPage(),
                'last_page' => $trackRecords->lastPage(),
                'per_page' => $trackRecords->perPage(),
                'total' => $trackRecords->total(),
            ],
        ]);
    }

    /**
     * Store a newly created track record.
     */
    public function store(StoreTrackRecordRequest $request): JsonResponse
    {
        $trackRecord = $this->trackRecordService->createTrackRecord($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Track record created successfully',
            'data' => new TrackRecordResource($trackRecord),
        ], 201);
    }

    /**
     * Display the specified track record.
     */
    public function show(TrackRecord $trackRecord): JsonResponse
    {
        $trackRecord->load('customer');

        return response()->json([
            'success' => true,
            'data' => new TrackRecordResource($trackRecord),
        ]);
    }

    /**
     * Update the specified track record.
     */
    public function update(UpdateTrackRecordRequest $request, TrackRecord $trackRecord): JsonResponse
    {
        $trackRecord = $this->trackRecordService->updateTrackRecord(
            $trackRecord->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Track record updated successfully',
            'data' => new TrackRecordResource($trackRecord),
        ]);
    }

    /**
     * Remove the specified track record.
     */
    public function destroy(TrackRecord $trackRecord): JsonResponse
    {
        $this->trackRecordService->deleteTrackRecord($trackRecord->id);

        return response()->json([
            'success' => true,
            'message' => 'Track record deleted successfully',
        ]);
    }
}
