<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePurchaseRequisitionRequest;
use App\Http\Requests\Api\V1\UpdatePurchaseRequisitionRequest;
use App\Http\Resources\V1\PurchaseRequisitionCollection;
use App\Http\Resources\V1\PurchaseRequisitionResource;
use App\Http\Resources\V1\PurchaseRequisitionListResource;
use App\Models\PurchaseRequisition;
use App\Services\PurchaseRequisitionService;
use Illuminate\Http\JsonResponse;

class PurchaseRequisitionController extends Controller
{
    public function __construct(
        private readonly PurchaseRequisitionService $purchaseRequisitionService
    ) {
        $this->authorizeResource(PurchaseRequisition::class, 'purchase_requisition');
    }

    /**
     * Display a listing of purchase requisitions.
     */
    public function index(): JsonResponse
    {
        $requisitions = $this->purchaseRequisitionService->getPaginatedPRs(
            perPage: request('per_page', 15),
            search: request('search')
        );

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
        $requisition = $this->purchaseRequisitionService->createPRWithItems($request->validated());

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
        $requisition = $this->purchaseRequisitionService->updatePRWithItems(
            $purchaseRequisition->id,
            $request->validated()
        );

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
        $this->purchaseRequisitionService->deletePR($purchaseRequisition->id);

        return response()->json([
            'success' => true,
            'message' => 'Purchase requisition deleted successfully',
        ]);
    }
}
