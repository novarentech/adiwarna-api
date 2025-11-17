<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePayrollTimesheetRequest;
use App\Http\Requests\Api\V1\UpdatePayrollTimesheetRequest;
use App\Http\Resources\V1\PayrollTimesheetResource;
use App\Models\PayrollTimesheet;
use App\Services\PayrollTimesheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollTimesheetController extends Controller
{
    public function __construct(
        private readonly PayrollTimesheetService $payrollTimesheetService
    ) {
        $this->authorizeResource(PayrollTimesheet::class, 'payroll_timesheet');
    }

    public function index(int $employeeId): JsonResponse
    {
        $timesheets = \App\Models\PayrollTimesheet::where('payroll_employee_id', $employeeId)->get();

        return response()->json([
            'success' => true,
            'data' => PayrollTimesheetResource::collection($timesheets),
        ]);
    }

    public function store(StorePayrollTimesheetRequest $request, int $employeeId): JsonResponse
    {
        $timesheet = $this->payrollTimesheetService->createTimesheet($employeeId, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payroll Timesheet created successfully',
            'data' => new PayrollTimesheetResource($timesheet),
        ], 201);
    }

    public function update(UpdatePayrollTimesheetRequest $request, int $employeeId, string $date): JsonResponse
    {
        $timesheet = $this->payrollTimesheetService->updateTimesheet($employeeId, $date, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payroll Timesheet updated successfully',
            'data' => new PayrollTimesheetResource($timesheet),
        ]);
    }

    public function destroy(int $employeeId, string $date): JsonResponse
    {
        $this->payrollTimesheetService->deleteTimesheet($employeeId, $date);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Timesheet deleted successfully',
        ]);
    }

    public function bulkUpdate(Request $request, int $employeeId): JsonResponse
    {
        $request->validate([
            'timesheets' => 'required|array',
            'timesheets.*.date' => 'required|date',
            'timesheets.*.attendance_status' => 'required|string',
            'timesheets.*.regular_hours' => 'nullable|numeric',
            'timesheets.*.overtime_hours' => 'nullable|numeric',
        ]);

        $this->payrollTimesheetService->bulkUpdateTimesheets($employeeId, $request->input('timesheets'));

        return response()->json([
            'success' => true,
            'message' => 'Timesheets updated successfully',
        ]);
    }
}
