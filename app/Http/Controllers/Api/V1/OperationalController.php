<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOperationalRequest;
use App\Http\Requests\Api\V1\UpdateOperationalRequest;
use App\Http\Resources\V1\OperationalCollection;
use App\Http\Resources\V1\OperationalResource;
use App\Models\Operational;
use App\Services\OperationalService;
use Illuminate\Http\JsonResponse;

class OperationalController extends Controller
{
    public function __construct(
        private readonly OperationalService $operationalService
    ) {
        $this->authorizeResource(Operational::class, 'operational');
    }

    /**
     * Display a listing of operational records.
     */
    public function index(): JsonResponse
    {
        $operationals = $this->operationalService->getPaginatedOperational(
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => new OperationalCollection($operationals),
            'meta' => [
                'current_page' => $operationals->currentPage(),
                'last_page' => $operationals->lastPage(),
                'per_page' => $operationals->perPage(),
                'total' => $operationals->total(),
            ],
        ]);
    }

    /**
     * Store a newly created operational record.
     */
    public function store(StoreOperationalRequest $request): JsonResponse
    {
        $operational = $this->operationalService->createOperational($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Operational record created successfully',
            'data' => new OperationalResource($operational),
        ], 201);
    }

    /**
     * Display the specified operational record.
     */
    public function show(Operational $operational): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new OperationalResource($operational),
        ]);
    }

    /**
     * Update the specified operational record.
     */
    public function update(UpdateOperationalRequest $request, Operational $operational): JsonResponse
    {
        $operational = $this->operationalService->updateOperational(
            $operational->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Operational record updated successfully',
            'data' => new OperationalResource($operational),
        ]);
    }

    /**
     * Remove the specified operational record.
     */
    public function destroy(Operational $operational): JsonResponse
    {
        $this->operationalService->deleteOperational($operational->id);

        return response()->json([
            'success' => true,
            'message' => 'Operational record deleted successfully',
        ]);
    }
}
