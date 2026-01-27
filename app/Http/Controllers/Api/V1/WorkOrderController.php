<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWorkOrderRequest;
use App\Http\Requests\Api\V1\UpdateWorkOrderRequest;
use App\Http\Resources\V1\WorkOrderListResource;
use App\Http\Resources\V1\WorkOrderResource;
use App\Models\WorkOrder;
use App\Services\WorkOrderService;
use Illuminate\Http\JsonResponse;

class WorkOrderController extends Controller
{
    public function __construct(
        private readonly WorkOrderService $workOrderService
    ) {
        $this->authorizeResource(WorkOrder::class, 'work_order');
    }

    /**
     * Display a listing of work orders.
     */
    public function index(): JsonResponse
    {
        $workOrders = $this->workOrderService->getPaginatedWorkOrders(
            perPage: request('per_page', 15),
            search: request('search'),
            sortOrder: request('sort_order', 'desc')
        );

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
        $workOrder = $this->workOrderService->createWorkOrderWithEmployees($request->validated());

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
        $workOrder = $this->workOrderService->updateWorkOrderWithEmployees(
            $workOrder->id,
            $request->validated()
        );

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
        $this->workOrderService->deleteWorkOrder($workOrder->id);

        return response()->json([
            'success' => true,
            'message' => 'Work order deleted successfully',
        ]);
    }
}
