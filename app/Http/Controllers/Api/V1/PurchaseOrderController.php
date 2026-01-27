<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePurchaseOrderRequest;
use App\Http\Requests\Api\V1\UpdatePurchaseOrderRequest;
use App\Http\Resources\V1\PurchaseOrderListResource;
use App\Http\Resources\V1\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Services\PurchaseOrderService;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private readonly PurchaseOrderService $purchaseOrderService
    ) {
        $this->authorizeResource(PurchaseOrder::class, 'purchase_order');
    }

    public function index(): JsonResponse
    {
        $purchaseOrders = $this->purchaseOrderService->getPaginatedPurchaseOrders(
            perPage: request('per_page', 15),
            search: request('search'),
            sortOrder: request('sort_order', 'desc')
        );

        return response()->json([
            'success' => true,
            'data' => PurchaseOrderListResource::collection($purchaseOrders),
            'meta' => [
                'current_page' => $purchaseOrders->currentPage(),
                'last_page' => $purchaseOrders->lastPage(),
                'per_page' => $purchaseOrders->perPage(),
                'total' => $purchaseOrders->total(),
            ],
        ]);
    }

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order created successfully',
            'data' => new PurchaseOrderResource($purchaseOrder),
        ], 201);
    }

    public function show(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->load(['customer', 'items']);

        return response()->json([
            'success' => true,
            'data' => new PurchaseOrderResource($purchaseOrder),
        ]);
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->updatePurchaseOrder(
            $purchaseOrder->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order updated successfully',
            'data' => new PurchaseOrderResource($purchaseOrder),
        ]);
    }

    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $this->purchaseOrderService->deletePurchaseOrder($purchaseOrder->id);

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order deleted successfully',
        ]);
    }
}
