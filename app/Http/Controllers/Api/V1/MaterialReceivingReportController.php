<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreMaterialReceivingReportRequest;
use App\Http\Requests\Api\V1\UpdateMaterialReceivingReportRequest;
use App\Http\Resources\V1\MaterialReceivingReportCollection;
use App\Http\Resources\V1\MaterialReceivingReportResource;
use App\Models\MaterialReceivingReport;
use App\Services\MaterialReceivingReportService;
use Illuminate\Http\JsonResponse;

class MaterialReceivingReportController extends Controller
{
    public function __construct(
        private readonly MaterialReceivingReportService $materialReceivingReportService
    ) {
        $this->authorizeResource(MaterialReceivingReport::class, 'material_receiving_report');
    }

    /**
     * Display a listing of material receiving reports.
     */
    public function index(): JsonResponse
    {
        $reports = $this->materialReceivingReportService->getPaginatedMRRs(
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => new MaterialReceivingReportCollection($reports),
            'meta' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
        ]);
    }

    /**
     * Store a newly created material receiving report.
     */
    public function store(StoreMaterialReceivingReportRequest $request): JsonResponse
    {
        $report = $this->materialReceivingReportService->createMRRWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Material receiving report created successfully',
            'data' => new MaterialReceivingReportResource($report),
        ], 201);
    }

    /**
     * Display the specified material receiving report.
     */
    public function show(MaterialReceivingReport $materialReceivingReport): JsonResponse
    {
        $materialReceivingReport->load('items');

        return response()->json([
            'success' => true,
            'data' => new MaterialReceivingReportResource($materialReceivingReport),
        ]);
    }

    /**
     * Update the specified material receiving report.
     */
    public function update(UpdateMaterialReceivingReportRequest $request, MaterialReceivingReport $materialReceivingReport): JsonResponse
    {
        $report = $this->materialReceivingReportService->updateMRRWithItems(
            $materialReceivingReport->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Material receiving report updated successfully',
            'data' => new MaterialReceivingReportResource($report),
        ]);
    }

    /**
     * Remove the specified material receiving report.
     */
    public function destroy(MaterialReceivingReport $materialReceivingReport): JsonResponse
    {
        $this->materialReceivingReportService->deleteMRR($materialReceivingReport->id);

        return response()->json([
            'success' => true,
            'message' => 'Material receiving report deleted successfully',
        ]);
    }
}
