<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\UpdateWorkOrderRequest;
use App\Http\Resources\WorkOrderListResource;
use App\Http\Resources\WorkOrderResource;
use App\Models\WorkOrder;
use Illuminate\Http\JsonResponse;

class WorkOrderController extends Controller
{
    public function __construct() {
        //
    }

    /**
     * Display a listing of work orders.
     */
    public function index(): JsonResponse
    {
        $workOrders = WorkOrder::query()
            ->withRelations()
            ->search(request('search'))
            ->when(request('customer_id'), fn($q) => $q->filterByCustomer(request('customer_id')))
            ->when(request('status'), fn($q) => $q->filterByStatus(request('status')))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => WorkOrderListResource::collection($workOrders),
            'meta' => [
                'current_page' => $workOrders->currentPage(),
                'last_page' => $workOrders->lastPage(),
                'per_page' => $workOrders->perPage(),
                'total' => $workOrders->total(),
            ],
        ]);
    }

    /**
     * Store a newly created work order.
     */
    public function store(StoreWorkOrderRequest $request): JsonResponse
    {
        $workOrder = WorkOrder::createWithEmployees($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Work order created successfully',
            'data' => new WorkOrderResource($workOrder),
        ], 201);
    }

    /**
     * Display the specified work order.
     */
    public function show(WorkOrder $workOrder): JsonResponse
    {
        $workOrder->load(['customer', 'customerLocation', 'employees']);

        return response()->json([
            'success' => true,
            'data' => new WorkOrderResource($workOrder),
        ]);
    }

    /**
     * Update the specified work order.
     */
    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder): JsonResponse
    {
        $workOrder->updateWithEmployees($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Work order updated successfully',
            'data' => new WorkOrderResource($workOrder),
        ]);
    }

    /**
     * Remove the specified work order.
     */
    public function destroy(WorkOrder $workOrder): JsonResponse
    {
        $workOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work order deleted successfully',
        ]);
    }
}
