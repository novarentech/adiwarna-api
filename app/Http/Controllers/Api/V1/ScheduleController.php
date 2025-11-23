<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreScheduleRequest;
use App\Http\Requests\Api\V1\UpdateScheduleRequest;
use App\Http\Resources\V1\ScheduleListResource;
use App\Http\Resources\V1\ScheduleResource;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    public function __construct(
        private readonly ScheduleService $scheduleService
    ) {
        $this->authorizeResource(Schedule::class, 'schedule');
    }

    /**
     * Display a listing of schedules.
     */
    public function index(): JsonResponse
    {
        $schedules = $this->scheduleService->getPaginatedSchedules(
            perPage: request('per_page', 15),
            search: request('search')
        );

        return response()->json([
            'success' => true,
            'data' => ScheduleListResource::collection($schedules),
            'meta' => [
                'current_page' => $schedules->currentPage(),
                'last_page' => $schedules->lastPage(),
                'per_page' => $schedules->perPage(),
                'total' => $schedules->total(),
            ],
        ]);
    }

    /**
     * Store a newly created schedule.
     */
    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = $this->scheduleService->createScheduleWithWorkOrders($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully',
            'data' => new ScheduleResource($schedule),
        ], 201);
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['customer', 'workOrders']);

        return response()->json([
            'success' => true,
            'data' => new ScheduleResource($schedule),
        ]);
    }

    /**
     * Update the specified schedule.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): JsonResponse
    {
        $schedule = $this->scheduleService->updateScheduleWithWorkOrders(
            $schedule->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully',
            'data' => new ScheduleResource($schedule),
        ]);
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule): JsonResponse
    {
        $this->scheduleService->deleteSchedule($schedule->id);

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully',
        ]);
    }
}
