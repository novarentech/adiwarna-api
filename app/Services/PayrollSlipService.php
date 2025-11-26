<?php

namespace App\Services;

use App\Models\PayrollSlip;
use Illuminate\Support\Facades\DB;

class PayrollSlipService extends BaseService
{
    /**
     * Generate payroll slips for a period.
     */
    public function generateSlips(int $periodId, ?int $employeeId = null, ?string $slipType = null): array
    {
        return DB::transaction(function () use ($periodId, $employeeId, $slipType) {
            $query = \App\Models\PayrollEmployee::where('payroll_period_id', $periodId);

            if ($employeeId) {
                $query->where('id', $employeeId);
            }

            $employees = $query->get();
            $slips = [];

            foreach ($employees as $employee) {
                $slip = PayrollSlip::create([
                    'payroll_period_id' => $periodId,
                    'payroll_employee_id' => $employee->id,
                    'slip_type' => $slipType ?? 'monthly',
                    'generated_at' => now(),
                ]);

                $slips[] = $slip;
            }

            return $slips;
        });
    }

    /**
     * Get paginated slips for a period.
     */
    public function getPaginatedSlips(int $periodId, int $perPage = 15)
    {
        return PayrollSlip::where('payroll_period_id', $periodId)
            ->with(['period', 'employee'])
            ->paginate($perPage);
    }

    /**
     * Get a single slip by ID.
     */
    public function getSlipById(int $id): ?PayrollSlip
    {
        return PayrollSlip::with(['period', 'employee'])->find($id);
    }

    /**
     * Delete a slip.
     */
    public function deleteSlip(int $id): bool
    {
        $slip = PayrollSlip::findOrFail($id);
        return $slip->delete();
    }
}
