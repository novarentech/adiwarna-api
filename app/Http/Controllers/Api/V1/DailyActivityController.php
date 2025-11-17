<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDailyActivityRequest;
use App\Http\Requests\Api\V1\UpdateDailyActivityRequest;
use App\Http\Resources\V1\DailyActivityCollection;
use App\Http\Resources\V1\DailyActivityResource;
use App\Models\DailyActivity;
use App\Services\DailyActivityService;
use Illuminate\Http\JsonResponse;

class DailyActivityController extends Controller
{
    public function __construct(
        private readonly DailyActivityService $dailyActivityService
    ) {
        $this->authorizeResource(DailyActivity::class, 'daily_activity');
    }

    public function index(): JsonResponse
    {
        $dailyActivities = $this->dailyActivityService->getPaginatedDailyActivities(
            perPage: request('per_page', 15),
            userId: request()->user()->id
        );

        return response()->json([
            'success' => true,
            'data' => new DailyActivityCollection($dailyActivities),
            'meta' => [
                'current_page' => $dailyActivities->currentPage(),
                'last_page' => $dailyActivities->lastPage(),
                'per_page' => $dailyActivities->perPage(),
                'total' => $dailyActivities->total(),
            ],
        ]);
    }

    public function store(StoreDailyActivityRequest $request): JsonResponse
    {
        $dailyActivity = $this->dailyActivityService->createDailyActivity($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Daily Activity created successfully',
            'data' => new DailyActivityResource($dailyActivity),
        ], 201);
    }

    public function show(DailyActivity $dailyActivity): JsonResponse
    {
        $dailyActivity->load(['customer', 'members', 'descriptions']);

        return response()->json([
            'success' => true,
            'data' => new DailyActivityResource($dailyActivity),
        ]);
    }

    public function update(UpdateDailyActivityRequest $request, DailyActivity $dailyActivity): JsonResponse
    {
        $dailyActivity = $this->dailyActivityService->updateDailyActivity(
            $dailyActivity->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Daily Activity updated successfully',
            'data' => new DailyActivityResource($dailyActivity),
        ]);
    }

    public function destroy(DailyActivity $dailyActivity): JsonResponse
    {
        $this->dailyActivityService->deleteDailyActivity($dailyActivity->id);

        return response()->json([
            'success' => true,
            'message' => 'Daily Activity deleted successfully',
        ]);
    }
}
