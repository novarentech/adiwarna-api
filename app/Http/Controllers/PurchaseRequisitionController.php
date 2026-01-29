<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequisitionRequest;
use App\Http\Requests\UpdatePurchaseRequisitionRequest;
use App\Http\Resources\PurchaseRequisitionCollection;
use App\Http\Resources\PurchaseRequisitionResource;
use App\Http\Resources\PurchaseRequisitionListResource;
use App\Models\PurchaseRequisition;
use Illuminate\Http\JsonResponse;

class PurchaseRequisitionController extends Controller
{
    public function __construct() {
        //
    }

    /**
     * Display a listing of purchase requisitions.
     */
    public function index(): JsonResponse
    {
        $requisitions = PurchaseRequisition::query()
            ->search(request('search'))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->whereBetween('date', [request('start_date'), request('end_date')]))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => PurchaseRequisitionListResource::collection($requisitions),
            'meta' => [
                'current_page' => $requisitions->currentPage(),
                'last_page' => $requisitions->lastPage(),
                'per_page' => $requisitions->perPage(),
                'total' => $requisitions->total(),
            ],
        ]);
    }

    /**
     * Store a newly created purchase requisition.
     */
    public function store(StorePurchaseRequisitionRequest $request): JsonResponse
    {
        $requisition = PurchaseRequisition::createWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Purchase requisition created successfully',
            'data' => new PurchaseRequisitionResource($requisition),
        ], 201);
    }

    /**
     * Display the specified purchase requisition.
     */
    public function show(PurchaseRequisition $purchaseRequisition): JsonResponse
    {
        $purchaseRequisition->load('items');

        return response()->json([
            'success' => true,
            'data' => new PurchaseRequisitionResource($purchaseRequisition),
        ]);
    }

    /**
     * Update the specified purchase requisition.
     */
    public function update(UpdatePurchaseRequisitionRequest $request, PurchaseRequisition $purchaseRequisition): JsonResponse
    {
        $requisition = $purchaseRequisition->updateWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Purchase requisition updated successfully',
            'data' => new PurchaseRequisitionResource($requisition),
        ]);
    }

    /**
     * Remove the specified purchase requisition.
     */
    public function destroy(PurchaseRequisition $purchaseRequisition): JsonResponse
    {
        $purchaseRequisition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase requisition deleted successfully',
        ]);
    }
}
