<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderListResource;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(): JsonResponse
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with(['customer'])
            ->search(request('search'))
            ->when(request('customer_id'), fn($q) => $q->filterByCustomer(request('customer_id')))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

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
        $purchaseOrder = PurchaseOrder::createWithItems($request->validated());

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
        $purchaseOrder->updateWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order updated successfully',
            'data' => new PurchaseOrderResource($purchaseOrder),
        ]);
    }

    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order deleted successfully',
        ]);
    }
}
