<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentGeneralRequest;
use App\Http\Requests\UpdateEquipmentGeneralRequest;
use App\Http\Resources\EquipmentGeneralCollection;
use App\Http\Resources\EquipmentGeneralResource;
use App\Models\EquipmentGeneral;
use Illuminate\Http\JsonResponse;

class EquipmentGeneralController extends Controller
{
    /**
     * Display a listing of general equipment.
     * Supports search by description, merk/type, serial number, agency, or condition.
     */
    public function index(): JsonResponse
    {
        //

        $query = EquipmentGeneral::query();

        if ($search = request('search')) {
            $query->search($search);
        }

        $equipment = $query->paginate(request('per_page', 15));

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
        //

        $equipment = EquipmentGeneral::create($request->validated());

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
        //

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
        //

        $equipmentGeneral->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'General equipment updated successfully',
            'data' => new EquipmentGeneralResource($equipmentGeneral),
        ]);
    }

    /**
     * Remove the specified general equipment.
     */
    public function destroy(EquipmentGeneral $equipmentGeneral): JsonResponse
    {
        //

        $equipmentGeneral->delete();

        return response()->json([
            'success' => true,
            'message' => 'General equipment deleted successfully',
        ]);
    }
}

