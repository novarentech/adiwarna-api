<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEquipmentGeneralRequest;
use App\Http\Requests\Api\V1\UpdateEquipmentGeneralRequest;
use App\Http\Resources\V1\EquipmentGeneralCollection;
use App\Http\Resources\V1\EquipmentGeneralResource;
use App\Models\EquipmentGeneral;
use App\Services\EquipmentService;
use Illuminate\Http\JsonResponse;

class EquipmentGeneralController extends Controller
{
    public function __construct(
        private readonly EquipmentService $equipmentService
    ) {}

    /**
     * Display a listing of general equipment.
     * Supports search by description, merk/type, serial number, agency, or condition.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', EquipmentGeneral::class);

        $equipment = $this->equipmentService->getPaginatedGeneral(
            perPage: request('per_page', 15),
            search: request('search')
        );

        return response()->json([
            'success' => true,
            'data' => new EquipmentGeneralCollection($equipment),
            'meta' => [
                'current_page' => $equipment->currentPage(),
                'last_page' => $equipment->lastPage(),
                'per_page' => $equipment->perPage(),
                'total' => $equipment->total(),
            ],
        ]);
    }

    /**
     * Store a newly created general equipment.
     */
    public function store(StoreEquipmentGeneralRequest $request): JsonResponse
    {
        $this->authorize('create', EquipmentGeneral::class);

        $equipment = $this->equipmentService->createGeneral($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'General equipment created successfully',
            'data' => new EquipmentGeneralResource($equipment),
        ], 201);
    }

    /**
     * Display the specified general equipment.
     */
    public function show(EquipmentGeneral $equipmentGeneral): JsonResponse
    {
        $this->authorize('viewGeneral', [EquipmentGeneral::class, $equipmentGeneral]);

        return response()->json([
            'success' => true,
            'data' => new EquipmentGeneralResource($equipmentGeneral),
        ]);
    }

    /**
     * Update the specified general equipment.
     */
    public function update(UpdateEquipmentGeneralRequest $request, EquipmentGeneral $equipmentGeneral): JsonResponse
    {
        $this->authorize('updateGeneral', [EquipmentGeneral::class, $equipmentGeneral]);

        $equipment = $this->equipmentService->updateGeneral(
            $equipmentGeneral->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'General equipment updated successfully',
            'data' => new EquipmentGeneralResource($equipment),
        ]);
    }

    /**
     * Remove the specified general equipment.
     */
    public function destroy(EquipmentGeneral $equipmentGeneral): JsonResponse
    {
        $this->authorize('deleteGeneral', [EquipmentGeneral::class, $equipmentGeneral]);

        $this->equipmentService->deleteGeneral($equipmentGeneral->id);

        return response()->json([
            'success' => true,
            'message' => 'General equipment deleted successfully',
        ]);
    }
}
